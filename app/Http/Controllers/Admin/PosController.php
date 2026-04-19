<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\MpesaTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    /**
     * Show the POS terminal.
     */
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        $products   = Product::where('is_active', true)
                             ->where('stock_quantity', '>', 0)
                             ->with('category')
                             ->orderBy('name')
                             ->get();

        return view('admin.pos.index', compact('categories', 'products'));
    }

    /**
     * Search products (AJAX).
     */
    public function searchProducts(Request $request)
    {
        $q          = $request->get('q', '');
        $categoryId = $request->get('category_id');

        $query = Product::where('is_active', true)
                        ->where('stock_quantity', '>', 0);

        if ($q) {
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%$q%")
                   ->orWhere('sku',  'like', "%$q%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->with('category:id,name')
                          ->select('id', 'name', 'sku', 'price', 'sale_price', 'stock_quantity', 'thumbnail', 'category_id')
                          ->get()
                          ->map(function ($p) {
                              $p->current_price    = $p->getCurrentPrice();
                              $p->discount_percent = $p->getDiscountPercent();
                              return $p;
                          });

        return response()->json($products);
    }

    /**
     * Process POS sale.
     */
    public function processSale(Request $request)
    {
        $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:products,id',
            'items.*.qty'    => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,mpesa,card',
            'customer_name'  => 'nullable|string|max:120',
            'customer_phone' => 'nullable|string|max:20',
            'amount_paid'    => 'required|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0',
            'mpesa_code'     => 'required_if:payment_method,mpesa|nullable|string|max:20',
        ]);

        DB::beginTransaction();

        try {
            $subtotal   = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['id']);

                if ($product->stock_quantity < $item['qty']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for {$product->name}.",
                    ], 422);
                }

                $unitPrice    = $product->getCurrentPrice();
                $lineTotal    = $unitPrice * $item['qty'];
                $subtotal    += $lineTotal;

                $orderItems[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,   // fillable in OrderItem
                    'price'        => $unitPrice,
                    'quantity'     => $item['qty'],      // OrderItem uses 'quantity'
                    'subtotal'     => $lineTotal,
                ];

                $product->decrement('stock_quantity', $item['qty']);
            }

            $discount = (float) ($request->discount ?? 0);
            $total    = max(0, $subtotal - $discount);

            // Cash underpayment guard
            if ($request->payment_method === 'cash' && $request->amount_paid < $total) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Amount paid is less than the total.',
                ], 422);
            }

            // Find or create walk-in customer
            $userId    = null;
            $firstName = 'Walk-in';
            $lastName  = 'Customer';

            if ($request->customer_phone) {
                $customer = User::firstOrCreate(
                    ['phone' => $request->customer_phone],
                    [
                        'name'     => $request->customer_name ?? 'Walk-in Customer',
                        'email'    => 'walkin_' . Str::random(6) . '@pos.local',
                        'password' => bcrypt(Str::random(16)),
                        'role'     => 'customer',
                    ]
                );
                $userId = $customer->id;
            }

            if ($request->customer_name) {
                $parts     = explode(' ', trim($request->customer_name), 2);
                $firstName = $parts[0];
                $lastName  = $parts[1] ?? 'Customer';
            }

            // Payment status
            $paymentStatus = match ($request->payment_method) {
                'cash'  => 'paid',
                'card'  => 'paid',
                'mpesa' => $request->mpesa_code ? 'paid' : 'pending',
                default => 'pending',
            };

            // Create order — only fillable fields used
            $order = Order::create([
                'user_id'        => $userId,
                'first_name'     => $firstName,
                'last_name'      => $lastName,
                'phone'          => $request->customer_phone,
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'shipping'       => 0,
                'tax'            => 0,
                'total'          => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => $paymentStatus,
                'status'         => 'delivered',   // POS = immediate fulfilment
                'notes'          => 'POS Sale',
                'paid_at'        => $paymentStatus === 'paid' ? now() : null,
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            // Log M-Pesa transaction
            if ($request->payment_method === 'mpesa' && $request->mpesa_code) {
                MpesaTransaction::create([
                    'order_id'             => $order->id,
                    'phone_number'         => $request->customer_phone,
                    'amount'               => $total,
                    'mpesa_receipt_number' => strtoupper($request->mpesa_code),
                    'status'               => 'success',
                ]);
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => 'Sale completed!',
                'order_number' => $order->order_number,
                'total'        => $total,
                'change'       => max(0, $request->amount_paid - $total),
                'order_id'     => $order->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POS order history.
     */
    public function orders(Request $request)
    {
        $orders = Order::whereNotNull('notes')
                       ->where('notes', 'POS Sale')
                       ->with(['items.product', 'user'])
                       ->latest()
                       ->paginate(20);

        return view('admin.pos.orders', compact('orders'));
    }

    /**
     * Receipt view (printable).
     */
    public function receipt(Order $order)
    {
        $order->load('items.product');
        return view('admin.pos.receipt', compact('order'));
    }
}
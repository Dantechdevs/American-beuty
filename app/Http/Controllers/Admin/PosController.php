<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $categories = \App\Models\Category::orderBy('name')->get();
        $products   = Product::where('status', 'active')
                             ->where('stock', '>', 0)
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
        $q = $request->get('q', '');
        $categoryId = $request->get('category_id');

        $query = Product::where('status', 'active')->where('stock', '>', 0);

        if ($q) {
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%$q%")
                   ->orWhere('sku', 'like', "%$q%")
                   ->orWhere('barcode', 'like', "%$q%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->select('id', 'name', 'sku', 'price', 'stock', 'image')->get();

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
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['id']);

                if ($product->stock < $item['qty']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for {$product->name}.",
                    ], 422);
                }

                $lineTotal  = $product->price * $item['qty'];
                $subtotal  += $lineTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'qty'        => $item['qty'],
                    'price'      => $product->price,
                    'total'      => $lineTotal,
                ];

                // Decrement stock
                $product->decrement('stock', $item['qty']);
            }

            $discount = (float) ($request->discount ?? 0);
            $total    = max(0, $subtotal - $discount);

            // Find or create walk-in customer
            $userId = null;
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

            // Create order
            $order = Order::create([
                'order_number'    => 'POS-' . strtoupper(Str::random(8)),
                'user_id'         => $userId,
                'first_name'      => $request->customer_name ?? 'Walk-in',
                'last_name'       => 'Customer',
                'phone'           => $request->customer_phone,
                'subtotal'        => $subtotal,
                'discount'        => $discount,
                'total'           => $total,
                'payment_method'  => $request->payment_method,
                'payment_status'  => $request->payment_method === 'cash' ? 'paid' : 'pending',
                'status'          => 'completed',
                'source'          => 'pos',
                'served_by'       => Auth::id(),
                'notes'           => 'POS Sale',
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            // Log M-PESA if needed
            if ($request->payment_method === 'mpesa' && $request->mpesa_code) {
                MpesaTransaction::create([
                    'order_id'             => $order->id,
                    'phone_number'         => $request->customer_phone,
                    'amount'               => $total,
                    'mpesa_receipt_number' => strtoupper($request->mpesa_code),
                    'status'               => 'success',
                ]);
                $order->update(['payment_status' => 'paid']);
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
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * POS Orders history.
     */
    public function orders(Request $request)
    {
        $orders = Order::where('source', 'pos')
                       ->with('items', 'servedBy')
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

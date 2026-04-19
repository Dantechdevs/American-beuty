<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\MpesaTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $cashier = Auth::user();

        return view('admin.pos.index', compact('categories', 'products', 'cashier'));
    }

    /**
     * Search products via AJAX.
     */
    public function searchProducts(Request $request)
    {
        $q          = trim($request->get('q', ''));
        $categoryId = $request->get('category_id');

        $query = Product::where('is_active', true)
                        ->where('stock_quantity', '>', 0);

        if ($q) {
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%")
                   ->orWhere('sku',  'like', "%{$q}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query
            ->with('category:id,name')
            ->select('id', 'name', 'sku', 'price', 'sale_price',
                     'stock_quantity', 'thumbnail', 'category_id')
            ->orderBy('name')
            ->get()
            ->map(function ($p) {
                $p->current_price    = $p->getCurrentPrice();
                $p->discount_percent = $p->getDiscountPercent();
                return $p;
            });

        return response()->json($products);
    }

    /**
     * Look up a customer by phone number (AJAX).
     */
    public function lookupCustomer(Request $request)
    {
        $request->validate(['phone' => 'required|string|max:20']);

        $customer = User::where('phone', $request->phone)->first();

        if (! $customer) {
            return response()->json(['found' => false]);
        }

        $orderCount = Order::where('user_id', $customer->id)->count();

        return response()->json([
            'found'       => true,
            'name'        => $customer->name,
            'order_count' => $orderCount,
            'returning'   => $orderCount > 0,
            'message'     => $orderCount > 0
                ? "Welcome back, {$customer->name}! 👋 ({$orderCount} previous orders)"
                : "New customer: {$customer->name}",
        ]);
    }

    /**
     * Process a POS sale.
     */
    public function processSale(Request $request)
    {
        // ── Step 1: Validate input ──────────────────────────────────────
        $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:products,id',
            'items.*.qty'    => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,mpesa,card',
            'customer_name'  => 'nullable|string|max:120',
            'customer_phone' => 'required_if:payment_method,mpesa|nullable|string|max:20',
            'amount_paid'    => 'required|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0|max:999999',
            'mpesa_code'     => 'required_if:payment_method,mpesa|nullable|string|max:20',
        ], [
            'customer_phone.required_if' => 'Customer phone number is required for M-Pesa payment.',
            'mpesa_code.required_if'     => 'M-Pesa transaction code is required.',
        ]);

        // ── Step 2: Extra M-Pesa guards ─────────────────────────────────
        if ($request->payment_method === 'mpesa') {
            if (empty(trim($request->customer_phone ?? ''))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer phone number is required for M-Pesa payment.',
                ], 422);
            }

            if (empty(trim($request->mpesa_code ?? ''))) {
                return response()->json([
                    'success' => false,
                    'message' => 'M-Pesa transaction code is required.',
                ], 422);
            }
        }

        DB::beginTransaction();

        try {
            // ── Step 3: Build order items + check stock ─────────────────
            $subtotal   = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['id']);

                if ($product->stock_quantity < $item['qty']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for \"{$product->name}\". "
                                   . "Only {$product->stock_quantity} left.",
                    ], 422);
                }

                $unitPrice  = $product->getCurrentPrice();
                $lineTotal  = $unitPrice * $item['qty'];
                $subtotal  += $lineTotal;

                $orderItems[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'price'        => $unitPrice,
                    'quantity'     => $item['qty'],
                    'subtotal'     => $lineTotal,
                ];

                // Deduct stock immediately
                $product->decrement('stock_quantity', $item['qty']);
            }

            // ── Step 4: Calculate totals ────────────────────────────────
            $discount = (float) ($request->discount ?? 0);
            $total    = max(0, $subtotal - $discount);
            $paid     = (float) $request->amount_paid;
            $change   = max(0, $paid - $total);

            // ── Step 5: Payment guards ──────────────────────────────────
            if ($request->payment_method === 'cash' && $paid < $total) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Amount paid (KSh " . number_format($paid, 2)
                               . ") is less than the total (KSh " . number_format($total, 2) . ").",
                ], 422);
            }

            // ── Step 6: Resolve / create customer ───────────────────────
            $userId      = null;
            $customerObj = null;
            $returning   = false;
            $firstName   = 'Walk-in';
            $lastName    = 'Customer';

            $rawPhone = trim($request->customer_phone ?? '');
            $rawName  = trim($request->customer_name  ?? '');

            if ($rawPhone) {
                $customerObj = User::firstOrCreate(
                    ['phone' => $rawPhone],
                    [
                        'name'     => $rawName ?: 'Walk-in Customer',
                        'email'    => 'walkin_' . Str::random(8) . '@pos.local',
                        'password' => bcrypt(Str::random(16)),
                        'role'     => 'customer',
                    ]
                );

                $userId    = $customerObj->id;
                $returning = Order::where('user_id', $customerObj->id)->exists();

                // Update name if provided and the stored name is generic
                if ($rawName && $customerObj->name === 'Walk-in Customer') {
                    $customerObj->update(['name' => $rawName]);
                }
            }

            if ($rawName) {
                $parts     = explode(' ', $rawName, 2);
                $firstName = $parts[0];
                $lastName  = $parts[1] ?? 'Customer';
            }

            // ── Step 7: Determine payment status ────────────────────────
            $paymentStatus = match ($request->payment_method) {
                'cash'  => 'paid',
                'card'  => 'paid',
                'mpesa' => ! empty(trim($request->mpesa_code ?? '')) ? 'paid' : 'pending',
                default => 'pending',
            };

            // ── Step 8: Create the order ─────────────────────────────────
            $order = Order::create([
                'user_id'        => $userId,
                'source'         => 'pos',
                'served_by'      => Auth::id(),
                'first_name'     => $firstName,
                'last_name'      => $lastName,
                'phone'          => $rawPhone ?: null,
                'email'          => $customerObj?->email ?? 'walkin@pos.local',
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'shipping'       => 0,
                'tax'            => 0,
                'total'          => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => $paymentStatus,
                'status'         => 'delivered',
                'notes'          => 'POS Sale — served by ' . Auth::user()->name,
                'paid_at'        => $paymentStatus === 'paid' ? now() : null,
            ]);

            // ── Step 9: Attach order items ───────────────────────────────
            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            // ── Step 10: Log M-Pesa transaction ─────────────────────────
            if ($request->payment_method === 'mpesa') {
                $mpesaPhone = $rawPhone;

                // Absolute safety — should never reach here due to guard above,
                // but prevents DB constraint error in any edge case.
                if (empty($mpesaPhone)) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Phone number is required for M-Pesa transactions.',
                    ], 422);
                }

                MpesaTransaction::create([
                    'order_id'             => $order->id,
                    'phone_number'         => $mpesaPhone,
                    'amount'               => $total,
                    'mpesa_receipt_number' => strtoupper(trim($request->mpesa_code)),
                    'status'               => 'success',
                ]);
            }

            DB::commit();

            // ── Step 11: Return success payload ─────────────────────────
            return response()->json([
                'success'      => true,
                'message'      => 'Sale completed successfully!',
                'order_id'     => $order->id,
                'order_number' => $order->order_number,
                'total'        => $total,
                'paid'         => $paid,
                'change'       => $change,
                'cashier'      => Auth::user()->name,
                'time'         => now()->format('D, d M Y  H:i'),
                'returning'    => $returning,
                'customer'     => trim("{$firstName} {$lastName}"),
                'payment'      => $request->payment_method,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('POS Sale Error', [
                'error'    => $e->getMessage(),
                'line'     => $e->getLine(),
                'file'     => $e->getFile(),
                'cashier'  => Auth::id(),
                'payload'  => $request->except(['_token']),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sale failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POS order history (paginated).
     */
    public function orders(Request $request)
    {
        $query = Order::where('source', 'pos')
                      ->with(['items.product', 'user', 'servedBy']);

        // Optional date filter
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Optional payment method filter
        if ($request->filled('payment')) {
            $query->where('payment_method', $request->payment);
        }

        // Optional search by order number or customer name
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->where('order_number', 'like', "%{$q}%")
                   ->orWhere('first_name',  'like', "%{$q}%")
                   ->orWhere('last_name',   'like', "%{$q}%")
                   ->orWhere('phone',       'like', "%{$q}%");
            });
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.pos.orders', compact('orders'));
    }

    /**
     * Printable receipt for a POS order.
     */
    public function receipt(Order $order)
    {
        // Only show POS receipts
        if ($order->source !== 'pos') {
            abort(404);
        }

        $order->load(['items.product', 'servedBy']);

        return view('admin.pos.receipt', compact('order'));
    }

    /**
     * Quick stock check for a single product (AJAX).
     */
    public function checkStock(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $product = Product::select('id', 'stock_quantity', 'name')
                          ->findOrFail($request->product_id);

        return response()->json([
            'id'    => $product->id,
            'name'  => $product->name,
            'stock' => $product->stock_quantity,
        ]);
    }
}
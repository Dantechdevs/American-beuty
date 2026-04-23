<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ReturnOrder;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnOrderController extends Controller
{
    public function index()
    {
        $returns = ReturnOrder::where('user_id', Auth::id())
            ->with(['order', 'product'])
            ->latest()
            ->paginate(10);

        return view('customer.return-orders.index', compact('returns'));
    }

    public function create(Order $order)
    {
        // Only allow returns on delivered orders belonging to this customer
        abort_if($order->user_id !== Auth::id(), 403);
        abort_if($order->status !== 'delivered', 403);

        $order->load('orderItems.product');
        return view('customer.return-orders.create', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id'      => 'required|exists:orders,id',
            'order_item_id' => 'required|exists:order_items,id',
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'required|integer|min:1',
            'reason'        => 'required|string',
            'description'   => 'required|string|max:1000',
            'photo'         => 'required|image|max:2048',
            'refund_method' => 'required|in:wallet,original_payment,store_credit,cash',
        ]);

        // Verify order belongs to customer
        $order = Order::findOrFail($request->order_id);
        abort_if($order->user_id !== Auth::id(), 403);

        // Verify not already returned for this item
        $alreadyReturned = ReturnOrder::where('order_item_id', $request->order_item_id)
            ->whereNotIn('status', ['rejected', 'closed'])
            ->exists();

        if ($alreadyReturned) {
            return back()->withErrors(['order_item_id' => 'A return request already exists for this item.']);
        }

        $photo = $request->file('photo')->store('return-orders', 'public');

        ReturnOrder::create([
            'order_id'      => $request->order_id,
            'order_item_id' => $request->order_item_id,
            'product_id'    => $request->product_id,
            'user_id'       => Auth::id(),
            'initiated_by'  => 'customer',
            'quantity'      => $request->quantity,
            'reason'        => $request->reason,
            'description'   => $request->description,
            'photo'         => $photo,
            'refund_method' => $request->refund_method,
            'status'        => 'pending',
        ]);

        return redirect()->route('customer.return-orders.index')
            ->with('success', 'Return request submitted successfully. We\'ll review it shortly.');
    }

    public function show(ReturnOrder $returnOrder)
    {
        abort_if($returnOrder->user_id !== Auth::id(), 403);
        $returnOrder->load(['order', 'product', 'reviewer']);
        return view('customer.return-orders.show', compact('returnOrder'));
    }
}
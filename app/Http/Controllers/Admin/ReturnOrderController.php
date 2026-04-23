<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnOrderController extends Controller
{
    public function index(Request $request)
    {
        $returns = ReturnOrder::with(['order', 'product', 'user'])
            ->when($request->search, fn($q) =>
                $q->where('return_number', 'like', '%'.$request->search.'%')
                  ->orWhereHas('user', fn($q) =>
                      $q->where('name', 'like', '%'.$request->search.'%')
                  )
            )
            ->when($request->status, fn($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->reason, fn($q) =>
                $q->where('reason', $request->reason)
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'     => ReturnOrder::count(),
            'pending'   => ReturnOrder::where('status', 'pending')->count(),
            'approved'  => ReturnOrder::where('status', 'approved')->count(),
            'refunded'  => ReturnOrder::where('status', 'refunded')->count(),
        ];

        return view('admin.return-orders.index', compact('returns', 'stats'));
    }

    public function show(ReturnOrder $returnOrder)
    {
        $returnOrder->load(['order.orderItems', 'product', 'user', 'reviewer']);
        return view('admin.return-orders.show', compact('returnOrder'));
    }

    public function updateStatus(Request $request, ReturnOrder $returnOrder)
    {
        $request->validate([
            'status'        => 'required|in:pending,reviewing,approved,rejected,refunded,closed',
            'admin_notes'   => 'nullable|string|max:1000',
            'refund_amount' => 'nullable|numeric|min:0',
            'refund_method' => 'nullable|in:wallet,original_payment,store_credit,cash',
        ]);

        $data = [
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ];

        if ($request->filled('refund_amount')) {
            $data['refund_amount'] = $request->refund_amount;
        }

        if ($request->filled('refund_method')) {
            $data['refund_method'] = $request->refund_method;
        }

        // Auto-restore stock on approval
        if ($request->status === 'approved' && ! $returnOrder->stock_restored) {
            $product = Product::find($returnOrder->product_id);
            if ($product) {
                $product->increment('stock', $returnOrder->quantity);
            }
            $data['stock_restored'] = true;
        }

        $returnOrder->update($data);

        return back()->with('success', 'Return ' . $returnOrder->return_number . ' updated to ' . ucfirst($request->status) . '.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id'      => 'required|exists:orders,id',
            'order_item_id' => 'required|exists:order_items,id',
            'product_id'    => 'required|exists:products,id',
            'user_id'       => 'required|exists:users,id',
            'quantity'      => 'required|integer|min:1',
            'reason'        => 'required|string',
            'description'   => 'required|string|max:1000',
            'photo'         => 'nullable|image|max:2048',
            'refund_amount' => 'nullable|numeric|min:0',
            'refund_method' => 'nullable|in:wallet,original_payment,store_credit,cash',
        ]);

        $photo = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('return-orders', 'public');
        }

        ReturnOrder::create([
            'order_id'      => $request->order_id,
            'order_item_id' => $request->order_item_id,
            'product_id'    => $request->product_id,
            'user_id'       => $request->user_id,
            'initiated_by'  => 'admin',
            'quantity'      => $request->quantity,
            'reason'        => $request->reason,
            'description'   => $request->description,
            'photo'         => $photo,
            'refund_amount' => $request->refund_amount,
            'refund_method' => $request->refund_method,
            'status'        => 'reviewing',
        ]);

        return back()->with('success', 'Return order created successfully.');
    }

    public function destroy(ReturnOrder $returnOrder)
    {
        $number = $returnOrder->return_number;
        $returnOrder->delete();
        return back()->with('success', 'Return ' . $number . ' deleted.');
    }
}
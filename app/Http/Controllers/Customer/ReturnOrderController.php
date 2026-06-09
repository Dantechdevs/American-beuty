<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ReturnOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnOrderController extends Controller
{
    public function index()
    {
        $returns = ReturnOrder::where('user_id', Auth::id())
            ->with('order')
            ->latest()
            ->get();
        return view('frontend.returns.index', compact('returns'));
    }

    public function create(Order $order)
    {
        return view('frontend.returns.create', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'reason'   => 'required|string|max:1000',
        ]);

        ReturnOrder::create([
            'user_id'  => Auth::id(),
            'order_id' => $request->order_id,
            'reason'   => $request->reason,
            'status'   => 'pending',
        ]);

        return redirect()->route('return-orders.index')->with('success', 'Return request submitted.');
    }

    public function show(ReturnOrder $returnOrder)
    {
        abort_if($returnOrder->user_id !== Auth::id(), 403);
        return view('frontend.returns.show', compact('returnOrder'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) $query->where('order_number', 'like', '%'.$request->search.'%');
        $orders = $query->paginate(20)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user', 'mpesa']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $previousStatus = $order->status;
        $newStatus      = $request->status;

        $order->update(['status' => $newStatus]);

        // ── Fire notifications on status transitions ────────────
        if ($order->user) {
            $notifications = app(NotificationService::class);
            $user          = $order->user;
            $orderNumber   = $order->order_number;
            $orderId       = $order->id;

            match ($newStatus) {

                // Order placed / moved back to pending
                'pending' => $notifications->notifyOrderPlaced(
                    $user, $orderNumber, $orderId
                ),

                // Payment confirmed when moved to processing
                'processing' => $notifications->notifyPaymentConfirmed(
                    $user,
                    $orderNumber,
                    $orderId,
                    $order->total ?? $order->grand_total ?? 0
                ),

                // Order collected / delivered
                'delivered' => (function () use ($notifications, $user, $orderNumber, $orderId) {
                    $notifications->notifyOrderCollected($user, $orderNumber, $orderId);
                    $notifications->notifyThankYou($user, $orderNumber, $orderId);
                })(),

                default => null,
            };
        }

        return back()->with('success', 'Order status updated.');
    }
}
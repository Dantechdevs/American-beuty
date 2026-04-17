<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private CartService $cart) {}

    public function createFromCart(array $data, float $discount = 0, ?int $couponId = null): Order
    {
        return DB::transaction(function () use ($data, $discount, $couponId) {
            $subtotal = $this->cart->subtotal();
            $shipping = $this->cart->shipping();
            $tax      = round($subtotal * ((float) Setting::get('tax_rate', 16) / 100), 2);
            $total    = max(0, $subtotal + $shipping + $tax - $discount);

            $order = Order::create([
                'user_id'        => Auth::id(),
                'coupon_id'      => $couponId,
                'status'         => 'pending',
                'first_name'     => $data['first_name'],
                'last_name'      => $data['last_name'],
                'email'          => $data['email'],
                'phone'          => $data['phone'],
                'address_line_1' => $data['address_line_1'],
                'address_line_2' => $data['address_line_2'] ?? null,
                'city'           => $data['city'],
                'county'         => $data['county'] ?? null,
                'country'        => $data['country'] ?? 'Kenya',
                'subtotal'       => $subtotal,
                'shipping'       => $shipping,
                'tax'            => $tax,
                'discount'       => $discount,
                'total'          => $total,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'unpaid',
                'notes'          => $data['notes'] ?? null,
            ]);

            foreach ($this->cart->items() as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product->name,
                    'price'        => $item->product->getCurrentPrice(),
                    'quantity'     => $item->quantity,
                    'subtotal'     => $item->product->getCurrentPrice() * $item->quantity,
                ]);

                // Decrement stock
                if ($item->product->track_stock) {
                    $item->product->decrement('stock_quantity', $item->quantity);
                }
            }

            if ($couponId) {
                \App\Models\Coupon::find($couponId)?->increment('used_count');
            }

            $this->cart->clear();
            return $order;
        });
    }
}

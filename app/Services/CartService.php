<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    private function identifier(): array
    {
        if (Auth::check()) {
            return ['user_id' => Auth::id()];
        }
        return ['session_id' => Session::getId()];
    }

    public function items()
    {
        return Cart::where($this->identifier())->with('product')->get();
    }

    public function add(int $productId, int $qty = 1): bool
    {
        $product = Product::find($productId);
        if (!$product || !$product->isInStock()) return false;

        $existing = Cart::where($this->identifier())->where('product_id', $productId)->first();
        if ($existing) {
            $existing->increment('quantity', $qty);
        } else {
            Cart::create(array_merge($this->identifier(), ['product_id' => $productId, 'quantity' => $qty]));
        }
        return true;
    }

    public function update(int $cartId, int $qty): void
    {
        $item = Cart::where($this->identifier())->find($cartId);
        if ($item) {
            if ($qty <= 0) {
                $item->delete();
            } else {
                $item->update(['quantity' => $qty]);
            }
        }
    }

    public function remove(int $cartId): void
    {
        Cart::where($this->identifier())->where('id', $cartId)->delete();
    }

    public function clear(): void
    {
        Cart::where($this->identifier())->delete();
    }

    public function count(): int
    {
        return Cart::where($this->identifier())->sum('quantity');
    }

    public function subtotal(): float
    {
        return $this->items()->sum(fn($item) => $item->product->getCurrentPrice() * $item->quantity);
    }

    public function shipping(): float
    {
        $freeMin = (float) Setting::get('free_shipping_min', 3000);
        $fee     = (float) Setting::get('shipping_fee', 200);
        return $this->subtotal() >= $freeMin ? 0 : $fee;
    }

    public function total(float $discount = 0): float
    {
        return max(0, $this->subtotal() + $this->shipping() - $discount);
    }

    public function mergeSessionCart(): void
    {
        if (!Auth::check()) return;
        $sessionId = Session::getId();
        Cart::where('session_id', $sessionId)->each(function ($item) {
            $existing = Cart::where('user_id', Auth::id())->where('product_id', $item->product_id)->first();
            if ($existing) {
                $existing->increment('quantity', $item->quantity);
                $item->delete();
            } else {
                $item->update(['user_id' => Auth::id(), 'session_id' => null]);
            }
        });
    }
}

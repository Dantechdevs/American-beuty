<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        $items    = $this->cart->items();
        $subtotal = $this->cart->subtotal();
        $shipping = $this->cart->shipping();
        $total    = $this->cart->total();
        return view('frontend.cart.index', compact('items', 'subtotal', 'shipping', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'integer|min:1']);
        $success = $this->cart->add($request->product_id, $request->get('quantity', 1));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => $success,
                'count'   => $this->cart->count(),
                'message' => $success ? 'Added to cart!' : 'Product unavailable.',
            ]);
        }
        return back()->with($success ? 'success' : 'error', $success ? 'Added to cart!' : 'Product unavailable.');
    }

    public function update(Request $request, int $id)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);
        $this->cart->update($id, $request->quantity);

        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'subtotal' => $this->cart->subtotal(),
                'shipping' => $this->cart->shipping(),
                'total'    => $this->cart->total(),
                'count'    => $this->cart->count(),
            ]);
        }
        return back()->with('success', 'Cart updated.');
    }

    public function remove(int $id)
    {
        $this->cart->remove($id);
        return back()->with('success', 'Item removed from cart.');
    }

    public function count()
    {
        return response()->json(['count' => $this->cart->count()]);
    }
}

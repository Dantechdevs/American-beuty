<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\MpesaTransaction;
use App\Models\PaymentGateway;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\Payment\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService  $cart,
        private OrderService $orderService,
        private MpesaService $mpesa,
    ) {}

    public function index()
    {
        if ($this->cart->count() === 0) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $user     = Auth::user();
        $address  = $user?->addresses()->where('is_default', true)->first();
        $gateways = PaymentGateway::where('is_active', true)->get();
        $subtotal = $this->cart->subtotal();
        $shipping = $this->cart->shipping();
        $total    = $this->cart->total();

        return view('frontend.checkout.index', compact('user','address','gateways','subtotal','shipping','total'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon || !$coupon->isValid($this->cart->subtotal())) {
            return back()->with('error', 'Invalid or expired coupon code.');
        }

        session(['coupon_id' => $coupon->id, 'coupon_code' => $coupon->code, 'coupon_discount' => $coupon->calculateDiscount($this->cart->subtotal())]);
        return back()->with('success', "Coupon applied! You saved KSh " . number_format($coupon->calculateDiscount($this->cart->subtotal()), 2));
    }

    public function removeCoupon()
    {
        session()->forget(['coupon_id','coupon_code','coupon_discount']);
        return back()->with('success', 'Coupon removed.');
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email',
            'phone'          => 'required|string',
            'address_line_1' => 'required|string',
            'city'           => 'required|string',
            'payment_method' => 'required|string|in:mpesa,cod,stripe',
        ]);

        if ($this->cart->count() === 0) {
            return back()->with('error', 'Your cart is empty.');
        }

        $discount  = (float) session('coupon_discount', 0);
        $couponId  = session('coupon_id');
        $order     = $this->orderService->createFromCart($request->all(), $discount, $couponId);
        session()->forget(['coupon_id','coupon_code','coupon_discount']);

        // Route to payment
        return match ($request->payment_method) {
            'mpesa'  => $this->initiateMpesa($order, $request->phone),
            'cod'    => $this->cashOnDelivery($order),
            default  => redirect()->route('order.success', $order->order_number)->with('success', 'Order placed!'),
        };
    }

    private function initiateMpesa($order, string $phone)
    {
        $result = $this->mpesa->stkPush($order, $phone);

        if ($result['success']) {
            return redirect()->route('checkout.mpesa.wait', [
                'order'               => $order->order_number,
                'checkout_request_id' => $result['checkout_request_id'],
            ]);
        }

        return redirect()->route('checkout')->with('error', $result['message']);
    }

    private function cashOnDelivery($order)
    {
        $order->update(['payment_status' => 'pending', 'status' => 'processing']);
        return redirect()->route('order.success', $order->order_number)->with('success', 'Order placed! Pay on delivery.');
    }

    public function mpesaWait(Request $request, string $orderNumber)
    {
        return view('frontend.checkout.mpesa-wait', [
            'orderNumber'        => $orderNumber,
            'checkoutRequestId'  => $request->checkout_request_id,
        ]);
    }

    public function mpesaStatus(Request $request)
    {
        $txn = MpesaTransaction::where('checkout_request_id', $request->checkout_request_id)->first();
        if (!$txn) return response()->json(['status' => 'pending']);
        return response()->json([
            'status'       => $txn->status,
            'order_number' => $txn->order?->order_number,
        ]);
    }

    public function success(string $orderNumber)
    {
        $order = \App\Models\Order::where('order_number', $orderNumber)
                     ->with('items.product')
                     ->firstOrFail();
        return view('frontend.checkout.success', compact('order'));
    }
}

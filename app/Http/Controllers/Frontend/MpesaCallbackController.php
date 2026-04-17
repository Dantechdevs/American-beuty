<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Payment\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MpesaCallbackController extends Controller
{
    public function __construct(private MpesaService $mpesa) {}

    /**
     * Safaricom will POST the payment result here.
     * Must be publicly accessible & HTTPS.
     */
    public function handle(Request $request)
    {
        Log::info('M-PESA Callback received', $request->all());

        $this->mpesa->handleCallback($request->all());

        // Safaricom expects a 200 JSON response
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}

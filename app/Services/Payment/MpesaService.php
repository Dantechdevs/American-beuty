<?php

namespace App\Services\Payment;

use App\Models\MpesaTransaction;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    private string $consumerKey;
    private string $consumerSecret;
    private string $shortcode;
    private string $passkey;
    private string $callbackUrl;
    private string $baseUrl;
    private bool   $isSandbox;

    public function __construct()
    {
        $this->consumerKey    = config('mpesa.consumer_key');
        $this->consumerSecret = config('mpesa.consumer_secret');
        $this->shortcode      = config('mpesa.shortcode');
        $this->passkey        = config('mpesa.passkey');
        $this->callbackUrl    = config('mpesa.callback_url');
        $this->isSandbox      = config('mpesa.env') === 'sandbox';
        $this->baseUrl        = $this->isSandbox
            ? 'https://sandbox.safaricom.co.ke'
            : 'https://api.safaricom.co.ke';
    }

    /**
     * Generate OAuth access token from Safaricom
     */
    public function getAccessToken(): ?string
    {
        try {
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get("{$this->baseUrl}/oauth/v1/generate?grant_type=client_credentials");

            if ($response->successful()) {
                return $response->json('access_token');
            }
            Log::error('M-PESA: Failed to get access token', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('M-PESA: Access token exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Generate the Lipa na M-PESA password
     */
    private function generatePassword(): string
    {
        $timestamp = now()->format('YmdHis');
        return base64_encode($this->shortcode . $this->passkey . $timestamp);
    }

    private function getTimestamp(): string
    {
        return now()->format('YmdHis');
    }

    /**
     * Initiate STK Push (Lipa na M-PESA Online)
     */
    public function stkPush(Order $order, string $phone): array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Could not connect to M-PESA. Please try again.'];
        }

        // Format phone: remove leading 0 or +254, ensure 254XXXXXXXXX
        $phone = $this->formatPhone($phone);

        $timestamp = $this->getTimestamp();
        $password  = $this->generatePassword();
        $amount    = (int) ceil((float) $order->total);

        $payload = [
            'BusinessShortCode' => $this->shortcode,
            'Password'          => $password,
            'Timestamp'         => $timestamp,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => $amount,
            'PartyA'            => $phone,
            'PartyB'            => $this->shortcode,
            'PhoneNumber'       => $phone,
            'CallBackURL'       => $this->callbackUrl,
            'AccountReference'  => $order->order_number,
            'TransactionDesc'   => 'American Beauty Order ' . $order->order_number,
        ];

        try {
            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/mpesa/stkpush/v1/processrequest", $payload);

            $result = $response->json();

            if ($response->successful() && isset($result['ResponseCode']) && $result['ResponseCode'] === '0') {
                // Log the transaction
                MpesaTransaction::create([
                    'order_id'            => $order->id,
                    'merchant_request_id' => $result['MerchantRequestID'],
                    'checkout_request_id' => $result['CheckoutRequestID'],
                    'phone_number'        => $phone,
                    'amount'              => $amount,
                    'status'              => 'pending',
                    'raw_response'        => $result,
                ]);

                return [
                    'success'              => true,
                    'checkout_request_id'  => $result['CheckoutRequestID'],
                    'merchant_request_id'  => $result['MerchantRequestID'],
                    'message'              => 'STK Push sent. Please check your phone and enter your M-PESA PIN.',
                ];
            }

            $errorMessage = $result['errorMessage'] ?? ($result['ResponseDescription'] ?? 'STK Push failed.');
            Log::error('M-PESA STK Push failed', ['response' => $result, 'order' => $order->order_number]);

            return ['success' => false, 'message' => $errorMessage];

        } catch (\Exception $e) {
            Log::error('M-PESA STK Push exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Payment initiation failed. Please try again.'];
        }
    }

    /**
     * Query STK Push status (for polling)
     */
    public function stkQuery(string $checkoutRequestId): array
    {
        $token = $this->getAccessToken();
        if (!$token) return ['success' => false, 'message' => 'Token error'];

        $timestamp = $this->getTimestamp();
        $password  = $this->generatePassword();

        try {
            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/mpesa/stkpushquery/v1/query", [
                    'BusinessShortCode' => $this->shortcode,
                    'Password'          => $password,
                    'Timestamp'         => $timestamp,
                    'CheckoutRequestID' => $checkoutRequestId,
                ]);

            return array_merge(['success' => true], $response->json());
        } catch (\Exception $e) {
            Log::error('M-PESA STK Query error', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Handle the callback from Safaricom
     * Called by the webhook endpoint
     */
    public function handleCallback(array $data): void
    {
        try {
            $body    = $data['Body']['stkCallback'] ?? null;
            if (!$body) {
                Log::warning('M-PESA Callback: missing stkCallback body');
                return;
            }

            $merchantRequestId  = $body['MerchantRequestID'] ?? null;
            $checkoutRequestId  = $body['CheckoutRequestID'] ?? null;
            $resultCode         = $body['ResultCode'] ?? -1;
            $resultDesc         = $body['ResultDesc'] ?? 'Unknown';

            $transaction = MpesaTransaction::where('checkout_request_id', $checkoutRequestId)->first();
            if (!$transaction) {
                Log::warning('M-PESA Callback: no transaction found', ['checkout_request_id' => $checkoutRequestId]);
                return;
            }

            if ((int) $resultCode === 0) {
                // SUCCESS
                $items = collect($body['CallbackMetadata']['Item'] ?? []);
                $receipt = $items->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;
                $txDate  = $items->firstWhere('Name', 'TransactionDate')['Value'] ?? null;
                $amount  = $items->firstWhere('Name', 'Amount')['Value'] ?? null;

                $transaction->update([
                    'status'               => 'success',
                    'mpesa_receipt_number' => $receipt,
                    'transaction_date'     => $txDate,
                    'amount'               => $amount ?? $transaction->amount,
                    'result_description'   => $resultDesc,
                    'raw_response'         => $data,
                ]);

                // Update order
                if ($transaction->order) {
                    $transaction->order->update([
                        'payment_status' => 'paid',
                        'status'         => 'processing',
                        'paid_at'        => now(),
                    ]);
                }

                Log::info('M-PESA Payment SUCCESS', [
                    'receipt'  => $receipt,
                    'order'    => $transaction->order?->order_number,
                ]);

            } else {
                // FAILED / CANCELLED
                $status = $resultCode == 1032 ? 'cancelled' : 'failed';
                $transaction->update([
                    'status'             => $status,
                    'result_description' => $resultDesc,
                    'raw_response'       => $data,
                ]);

                Log::info('M-PESA Payment FAILED', ['code' => $resultCode, 'desc' => $resultDesc]);
            }
        } catch (\Exception $e) {
            Log::error('M-PESA Callback error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }

    /**
     * Format phone number to 254XXXXXXXXX
     */
    public function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        }
        if (str_starts_with($phone, '+')) {
            $phone = ltrim($phone, '+');
        }
        if (!str_starts_with($phone, '254')) {
            $phone = '254' . $phone;
        }
        return $phone;
    }
}

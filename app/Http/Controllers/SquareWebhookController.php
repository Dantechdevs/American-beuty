// app/Http/Controllers/SquareWebhookController.php
public function handle(Request $request)
{
    // 1. Verify Square signature (IMPORTANT for security)
    $signature = $request->header('X-Square-Hmacsha256-Signature');
    $secret    = config('services.square.webhook_secret');
    $body      = $request->getContent();
    $expected  = base64_encode(hash_hmac('sha256', config('services.square.webhook_url') . $body, $secret, true));

    if (!hash_equals($expected, $signature ?? '')) {
        return response()->json(['error' => 'Invalid signature'], 401);
    }

    // 2. Parse the event
    $payload = $request->json()->all();
    $type    = $payload['type'] ?? null;

    if (in_array($type, ['booking.created', 'booking.updated', 'booking.cancelled'])) {
        $booking = $payload['data']['object']['booking'] ?? [];

        Booking::updateOrCreate(
            ['square_booking_id' => $booking['id']],
            [
                'customer_name'  => $booking['customer_note'] ?? null,
                'status'         => match($type) {
                    'booking.created'   => 'confirmed',
                    'booking.cancelled' => 'cancelled',
                    default             => 'confirmed',
                },
                'appointment_at' => $booking['start_at'] ?? null,
                'raw_payload'    => $booking,
            ]
        );
    }

    return response()->json(['ok' => true]);
}
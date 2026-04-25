// app/Models/Booking.php
protected $fillable = [
    'square_booking_id','customer_name','customer_email',
    'customer_phone','service_name','status','amount',
    'appointment_at','raw_payload'
];

protected $casts = [
    'raw_payload' => 'array',
    'appointment_at' => 'datetime',
];
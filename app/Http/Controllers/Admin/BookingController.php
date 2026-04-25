<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        return view('admin.bookings', [
            'bookings'  => Booking::latest('appointment_at')->paginate(20),
            'total'     => Booking::count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'today'     => Booking::whereDate('appointment_at', today())->count(),
        ]);
    }
}
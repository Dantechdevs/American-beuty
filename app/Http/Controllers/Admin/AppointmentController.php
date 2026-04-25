<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // ── GET /admin/appointments ──────────────────────────────
    public function index(Request $request)
    {
        $query = Appointment::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('service_category', $request->category);
        }

        // Search by client name or phone
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('client_name', 'like', '%' . $request->search . '%')
                  ->orWhere('client_phone', 'like', '%' . $request->search . '%')
                  ->orWhere('service_name', 'like', '%' . $request->search . '%');
            });
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
                              ->orderBy('appointment_time', 'desc')
                              ->paginate(20)
                              ->withQueryString();

        // Summary counts
        $total     = Appointment::count();
        $pending   = Appointment::pending()->count();
        $confirmed = Appointment::confirmed()->count();
        $today     = Appointment::today()->count();
        $cancelled = Appointment::cancelled()->count();
        $completed = Appointment::completed()->count();

        // Categories for filter dropdown
        $categories = Appointment::select('service_category')
                                  ->distinct()
                                  ->pluck('service_category')
                                  ->filter()
                                  ->sort()
                                  ->values();

        return view('admin.appointments.index', compact(
            'appointments',
            'total',
            'pending',
            'confirmed',
            'today',
            'cancelled',
            'completed',
            'categories'
        ));
    }

    // ── GET /admin/appointments/{appointment} ────────────────
    public function show(Appointment $appointment)
    {
        return view('admin.appointments.show', compact('appointment'));
    }

    // ── PATCH /admin/appointments/{appointment}/status ───────
    public function status(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $appointment->update(['status' => $request->status]);

        $label = ucfirst($request->status);

        return back()->with('success', "Appointment marked as {$label}.");
    }

    // ── PATCH /admin/appointments/{appointment}/payment ──────
    public function payment(Request $request, Appointment $appointment)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,paid',
            'mpesa_code'     => 'nullable|string|max:20',
            'deposit_amount' => 'nullable|numeric|min:0',
        ]);

        $appointment->update([
            'payment_status' => $request->payment_status,
            'mpesa_code'     => $request->mpesa_code,
            'deposit_amount' => $request->deposit_amount ?? $appointment->deposit_amount,
        ]);

        return back()->with('success', 'Payment status updated.');
    }

    // ── DELETE /admin/appointments/{appointment} ─────────────
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
                         ->with('success', 'Appointment deleted.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Employee;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    // -- List all appointments ----------------------------------------
    public function index(Request $request)
    {
        $appointments = Appointment::with(['employee', 'assignedBy'])
            ->when($request->status,   fn($q) => $q->where('status', $request->status))
            ->when($request->category, fn($q) => $q->where('service_category', $request->category))
            ->when($request->date,     fn($q) => $q->whereDate('appointment_date', $request->date))
            ->when($request->search,   fn($q) => $q->where(fn($q) =>
                $q->where('client_name',   'like', '%'.$request->search.'%')
                  ->orWhere('client_phone', 'like', '%'.$request->search.'%')
                  ->orWhere('service_name', 'like', '%'.$request->search.'%')
            ))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'     => Appointment::count(),
            'pending'   => Appointment::pending()->count(),
            'confirmed' => Appointment::confirmed()->count(),
            'today'     => Appointment::today()->count(),
            'completed' => Appointment::completed()->count(),
            'cancelled' => Appointment::cancelled()->count(),
        ];

        $categories = Appointment::distinct()->pluck('service_category')->filter()->sort()->values();
        $employees  = Employee::where('is_active', true)
                        ->whereIn('role', ['beautician', 'receptionist', 'manager'])
                        ->orderBy('name')->get();

        return view('admin.appointments.index', compact('appointments', 'stats', 'categories', 'employees'));
    }

    // -- View single appointment --------------------------------------
    public function show(Appointment $appointment)
    {
        $appointment->load(['employee', 'assignedBy']);
        $employees = Employee::where('is_active', true)
                      ->whereIn('role', ['beautician', 'receptionist', 'manager'])
                      ->orderBy('name')->get();

        return view('admin.appointments.show', compact('appointment', 'employees'));
    }

    // -- Update status ------------------------------------------------
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status'              => 'required|in:pending,confirmed,completed,cancelled',
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $data = ['status' => $request->status];

        match($request->status) {
            'confirmed' => $data['confirmed_at'] = now(),
            'completed' => $data['completed_at'] = now(),
            'cancelled' => $data += [
                'cancelled_at'        => now(),
                'cancellation_reason' => $request->cancellation_reason,
            ],
            default => null,
        };

        $appointment->update($data);

        // Send SMS based on new status
        match($request->status) {
            'confirmed' => $this->sendConfirmationSms($appointment),
            'completed' => $this->sendCompletedSms($appointment),
            'cancelled' => $this->sendCancelledSms($appointment),
            default     => null,
        };

        // Send confirmation email
        if ($request->status === 'confirmed' && $appointment->client_email) {
            try {
                Mail::to($appointment->client_email)
                    ->send(new \App\Mail\AppointmentConfirmed($appointment));
            } catch (\Exception $e) {
                \Log::error('Appointment email failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Appointment status updated to ' . ucfirst($request->status) . '.');
    }

    // -- Assign employee ----------------------------------------------
    public function assignEmployee(Request $request, Appointment $appointment)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $appointment->update([
            'employee_id' => $request->employee_id,
            'assigned_by' => auth()->id(),
        ]);

        return back()->with('success', 'Beautician assigned successfully.');
    }

    // -- Unassign employee --------------------------------------------
    public function unassignEmployee(Appointment $appointment)
    {
        $appointment->update(['employee_id' => null, 'assigned_by' => null]);
        return back()->with('success', 'Beautician unassigned.');
    }

    // -- Destroy ------------------------------------------------------
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return back()->with('success', 'Appointment deleted.');
    }

    // -- SMS: appointment confirmed -----------------------------------
    private function sendConfirmationSms(Appointment $appointment): void
    {
        $message = "Hi {$appointment->client_name}! ✅\n"
            . "Your appointment at American Beauty is CONFIRMED.\n"
            . "Service: {$appointment->service_name}\n"
            . "Date: {$appointment->appointment_date->format('M d, Y')} at {$appointment->appointment_time}\n"
            . "Please arrive 5 mins early. See you soon! 💜";

        app(NotificationService::class)->sendRawSms($appointment->client_phone, $message);
    }

    // -- SMS: appointment completed -----------------------------------
    private function sendCompletedSms(Appointment $appointment): void
    {
        $message = "Hi {$appointment->client_name}! 💜\n"
            . "Thank you for visiting American Beauty today!\n"
            . "We hope you loved your {$appointment->service_name}.\n"
            . "Book your next appointment at americanbeauty.co.ke";

        app(NotificationService::class)->sendRawSms($appointment->client_phone, $message);
    }

    // -- SMS: appointment cancelled -----------------------------------
    private function sendCancelledSms(Appointment $appointment): void
    {
        $reason  = $appointment->cancellation_reason
            ? "\nReason: {$appointment->cancellation_reason}"
            : '';

        $message = "Hi {$appointment->client_name}, your appointment at American Beauty on "
            . "{$appointment->appointment_date->format('M d, Y')} has been cancelled.{$reason}\n"
            . "To rebook, visit americanbeauty.co.ke or call us. Sorry for any inconvenience.";

        app(NotificationService::class)->sendRawSms($appointment->client_phone, $message);
    }
}
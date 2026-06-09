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


    // -- Store walk-in / manual appointment --------------------------
    public function store(Request $request)
    {
        $request->validate([
            "client_name"       => "required|string|max:150",
            "client_phone"      => "nullable|string|max:20",
            "service_name"      => "required|string|max:150",
            "service_category"  => "nullable|string|max:100",
            "service_price"     => "nullable|numeric|min:0",
            "amount_paid"       => "nullable|numeric|min:0",
            "appointment_date"  => "required|date",
            "appointment_time"  => "required",
            "payment_status"    => "required|in:unpaid,deposit,paid",
            "status"            => "required|in:pending,confirmed,completed,cancelled",
            "served_by"         => "nullable|exists:employees,id",
            "notes"             => "nullable|string",
        ]);

        $appointment = Appointment::create([
            "source"           => "walkin",
            "client_name"      => $request->client_name,
            "client_phone"     => $request->client_phone,
            "client_email"     => null,
            "service_name"     => $request->service_name,
            "service_category" => $request->service_category,
            "service_price"    => $request->service_price,
            "amount_paid"      => $request->amount_paid ?? 0,
            "appointment_date" => $request->appointment_date,
            "appointment_time" => $request->appointment_time,
            "payment_status"   => $request->payment_status,
            "status"           => $request->status,
            "served_by"        => $request->served_by,
            "payment_method"   => $request->payment_method,
            "mpesa_code"       => $request->payment_method === "mpesa" ? strtoupper(trim($request->mpesa_code ?? "")) : null,
            "notes"            => $request->notes,
            "confirmed_at"     => in_array($request->status, ["confirmed","completed"]) ? now() : null,
            "completed_at"     => $request->status === "completed" ? now() : null,
            "assigned_by"      => auth()->id(),
        ]);

        return redirect()->route("admin.appointments.show", $appointment)
                         ->with("success", "Walk-in appointment recorded successfully.");
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


    // -- Update payment ----------------------------------------------
    public function payment(Request $request, Appointment $appointment)
    {
        $request->validate([
            "payment_method" => "required|in:cash,mpesa,card,bank_transfer",
            "payment_status" => "required|in:unpaid,deposit,paid",
            "amount_paid"    => "nullable|numeric|min:0",
            "mpesa_code"     => "nullable|string|max:20",
        ]);

        $appointment->update([
            "payment_method" => $request->payment_method,
            "payment_status" => $request->payment_status,
            "amount_paid"    => $request->amount_paid ?? 0,
            "mpesa_code"     => $request->payment_method === "mpesa" ? strtoupper(trim($request->mpesa_code ?? "")) : $appointment->mpesa_code,
        ]);

        return back()->with("success", "Payment updated successfully.");
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
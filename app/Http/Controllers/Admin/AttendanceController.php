<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // ── Terminal page ──────────────────────────────────────────
    public function terminal()
    {
        $currentlyIn = Attendance::with('employee')
            ->whereDate('date', today())
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->get();

        $totalEmployees = Employee::where('is_active', true)->count();

        $todayStats = [
            'present' => Attendance::whereDate('date', today())->where('status', 'present')->count(),
            'late'    => Attendance::whereDate('date', today())->where('status', 'late')->count(),
            'absent'  => $totalEmployees - Attendance::whereDate('date', today())->count(),
        ];

        return view('admin.attendance.terminal', compact('currentlyIn', 'totalEmployees', 'todayStats'));
    }

    // ── PIN lookup (AJAX) ──────────────────────────────────────
    public function pinLookup(Request $request)
    {
        $employee = Employee::where('pin', $request->pin)
            ->where('is_active', true)
            ->first();

        if (!$employee) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found'         => true,
            'id'            => $employee->id,
            'name'          => $employee->name,
            'initials'      => $employee->initials,
            'role'          => $employee->role_label,
            'is_clocked_in' => $employee->isCurrentlyClockedIn(),
        ]);
    }

    // ── Clock In (AJAX) ────────────────────────────────────────
    public function clockIn(Request $request)
    {
        $employee = Employee::where('pin', $request->pin)
            ->where('is_active', true)
            ->firstOrFail();

        // Already clocked in today
        if ($employee->isCurrentlyClockedIn()) {
            return response()->json(['success' => false, 'message' => 'Already clocked in']);
        }

        $shift  = $employee->shift;
        $now    = now();

        $attendance = Attendance::firstOrCreate(
            ['employee_id' => $employee->id, 'date' => today()],
            [
                'shift_id'  => $shift?->id,
                'clock_in'  => $now,
                'status'    => $shift && $shift->isLate($now) ? 'late' : 'present',
            ]
        );

        return response()->json([
            'success' => true,
            'name'    => $employee->name,
            'time'    => $now->format('H:i'),
            'status'  => $attendance->status,
        ]);
    }

    // ── Clock Out (AJAX) ───────────────────────────────────────
    public function clockOut(Request $request)
    {
        $employee = Employee::where('pin', $request->pin)
            ->where('is_active', true)
            ->firstOrFail();

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->first();

        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'Not clocked in']);
        }

        $now = now();
        $attendance->update([
            'clock_out'    => $now,
            'hours_worked' => $attendance->calculateHoursWorked(),
            'status'       => $attendance->determineStatus($employee->shift),
        ]);

        return response()->json([
            'success' => true,
            'name'    => $employee->name,
            'time'    => $now->format('H:i'),
            'hours'   => $attendance->fresh()->hours_worked_formatted,
        ]);
    }

    // ── Records list ───────────────────────────────────────────
    public function index(Request $request)
    {
        $attendances = Attendance::with(['employee', 'shift'])
            ->when($request->date, fn($q) => $q->whereDate('date', $request->date))
            ->when($request->employee_id, fn($q) => $q->where('employee_id', $request->employee_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest('date')->latest('clock_in')
            ->paginate(25);

        $employees = Employee::where('is_active', true)->orderBy('name')->get();

        return view('admin.attendance.index', compact('attendances', 'employees'));
    }

    // ── Report ─────────────────────────────────────────────────
    public function report(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->toDateString();

        $employees = Employee::where('is_active', true)
            ->with(['attendances' => fn($q) =>
                $q->whereBetween('date', [$from, $to])
            ])
            ->get();

        return view('admin.attendance.report', compact('employees', 'from', 'to'));
    }

    // ── Employees manager ──────────────────────────────────────
    public function employees()
    {
        $employees = Employee::with('shift')->latest()->paginate(20);
        $shifts    = Shift::where('is_active', true)->get();
        return view('admin.attendance.employees', compact('employees', 'shifts'));
    }

    // ── Shifts manager ─────────────────────────────────────────
    public function shifts()
    {
        $shifts = Shift::withCount('employees')->latest()->get();
        return view('admin.attendance.shifts', compact('shifts'));
    }
}
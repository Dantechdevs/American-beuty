<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'total'     => $totalEmployees,
            'present'   => Attendance::whereDate('date', today())->where('status', 'present')->count(),
            'late'      => Attendance::whereDate('date', today())->where('status', 'late')->count(),
            'early_out' => Attendance::whereDate('date', today())->where('status', 'early_out')->count(),
            'absent'    => $totalEmployees - Attendance::whereDate('date', today())->count(),
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

        if ($employee->isCurrentlyClockedIn()) {
            return response()->json(['success' => false, 'message' => 'Already clocked in']);
        }

        $shift = $employee->shift;
        $now   = now();

        $attendance = Attendance::firstOrCreate(
            ['employee_id' => $employee->id, 'date' => today()],
            [
                'shift_id' => $shift?->id,
                'clock_in' => $now,
                'status'   => $shift && $shift->isLate($now) ? 'late' : 'present',
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
            ->when($request->employee_id, fn($q) => $q->where('employee_id', $request->employee_id))
            ->when($request->status,      fn($q) => $q->where('status', $request->status))
            ->when($request->date,        fn($q) => $q->whereDate('date', $request->date))
            ->when($request->date_from,   fn($q) => $q->whereDate('date', '>=', $request->date_from))
            ->when($request->date_to,     fn($q) => $q->whereDate('date', '<=', $request->date_to))
            ->when(
                !$request->hasAny(['date_from', 'date_to', 'date']),
                fn($q) => $q->whereDate('date', today())
            )
            ->latest('date')
            ->latest('clock_in')
            ->paginate(25);

        $employees      = Employee::where('is_active', true)->orderBy('name')->get();
        $totalEmployees = Employee::where('is_active', true)->count();

        $todayStats = [
            'total'     => $totalEmployees,
            'present'   => Attendance::whereDate('date', today())->where('status', 'present')->count(),
            'late'      => Attendance::whereDate('date', today())->where('status', 'late')->count(),
            'early_out' => Attendance::whereDate('date', today())->where('status', 'early_out')->count(),
            'absent'    => $totalEmployees - Attendance::whereDate('date', today())->count(),
        ];

        return view('admin.attendance.index', compact('attendances', 'employees', 'todayStats'));
    }

    // ── Today's attendance ─────────────────────────────────────
    public function today()
    {
        $attendances = Attendance::with(['employee', 'shift'])
            ->whereDate('date', today())
            ->latest('clock_in')
            ->get();

        $totalEmployees = Employee::where('is_active', true)->count();

        $stats = [
            'total'       => $totalEmployees,
            'present'     => $attendances->where('status', 'present')->count(),
            'late'        => $attendances->where('status', 'late')->count(),
            'early_out'   => $attendances->where('status', 'early_out')->count(),
            'absent'      => $totalEmployees - $attendances->count(),
            'clocked_out' => $attendances->whereNotNull('clock_out')->count(),
        ];

        $employees = Employee::where('is_active', true)
            ->with(['todayAttendance', 'shift'])
            ->orderBy('name')
            ->get();

        return view('admin.attendance.today', compact('attendances', 'stats', 'employees'));
    }

    // ── Single employee attendance ─────────────────────────────
    public function show(Employee $employee, Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->toDateString();

        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$from, $to])
            ->with('shift')
            ->latest('date')
            ->paginate(20);

        $summary = [
            'present'    => $attendances->where('status', 'present')->count(),
            'late'       => $attendances->where('status', 'late')->count(),
            'absent'     => $attendances->where('status', 'absent')->count(),
            'early_out'  => $attendances->where('status', 'early_out')->count(),
            'half_day'   => $attendances->where('status', 'half_day')->count(),
            'total_mins' => $attendances->sum('hours_worked'),
        ];

        return view('admin.attendance.show', compact('employee', 'attendances', 'summary', 'from', 'to'));
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
            ->orderBy('name')
            ->get();

        $employees->each(function ($emp) {
            $emp->summary = [
                'present'   => $emp->attendances->where('status', 'present')->count(),
                'late'      => $emp->attendances->where('status', 'late')->count(),
                'absent'    => $emp->attendances->where('status', 'absent')->count(),
                'early_out' => $emp->attendances->where('status', 'early_out')->count(),
                'half_day'  => $emp->attendances->where('status', 'half_day')->count(),
                'total_hrs' => $emp->attendances->sum('hours_worked'),
            ];
        });

        return view('admin.attendance.report', compact('employees', 'from', 'to'));
    }

    // ── Export (CSV) ───────────────────────────────────────────
    public function export(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->toDateString();

        $attendances = Attendance::with(['employee', 'shift'])
            ->whereBetween('date', [$from, $to])
            ->latest('date')
            ->get();

        $filename = 'attendance_' . $from . '_to_' . $to . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($attendances) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Employee', 'Role', 'Date', 'Shift',
                'Clock In', 'Clock Out', 'Hours Worked', 'Status', 'Note',
            ]);
            foreach ($attendances as $att) {
                fputcsv($file, [
                    $att->employee->name,
                    $att->employee->role_label,
                    $att->date->format('d M Y'),
                    $att->shift?->name ?? '—',
                    $att->clock_in?->format('H:i')  ?? '—',
                    $att->clock_out?->format('H:i') ?? '—',
                    $att->hours_worked_formatted,
                    $att->status_label,
                    $att->note ?? '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── Manual entry ───────────────────────────────────────────
    public function manual(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date'        => 'required|date',
            'status'      => 'required|in:present,late,early_out,absent,half_day',
            'clock_in'    => 'nullable|date_format:H:i',
            'clock_out'   => 'nullable|date_format:H:i',
            'note'        => 'nullable|string|max:500',
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        $clockIn  = $request->clock_in
            ? Carbon::parse($request->date . ' ' . $request->clock_in)
            : null;
        $clockOut = $request->clock_out
            ? Carbon::parse($request->date . ' ' . $request->clock_out)
            : null;

        $hoursWorked = ($clockIn && $clockOut)
            ? (int) $clockIn->diffInMinutes($clockOut)
            : null;

        Attendance::updateOrCreate(
            ['employee_id' => $request->employee_id, 'date' => $request->date],
            [
                'shift_id'       => $employee->shift_id,
                'clock_in'       => $clockIn,
                'clock_out'      => $clockOut,
                'hours_worked'   => $hoursWorked,
                'status'         => $request->status,
                'note'           => $request->note,
                'admin_override' => true,
                'overridden_by'  => Auth::id(),
            ]
        );

        return back()->with('success', 'Attendance entry saved for ' . $employee->name . '.');
    }

    // ── Override existing record ───────────────────────────────
    public function override(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status'    => 'required|in:present,late,early_out,absent,half_day',
            'clock_in'  => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'note'      => 'nullable|string|max:500',
        ]);

        $clockIn  = $request->clock_in
            ? Carbon::parse($attendance->date->toDateString() . ' ' . $request->clock_in)
            : $attendance->clock_in;

        $clockOut = $request->clock_out
            ? Carbon::parse($attendance->date->toDateString() . ' ' . $request->clock_out)
            : $attendance->clock_out;

        $hoursWorked = ($clockIn && $clockOut)
            ? (int) $clockIn->diffInMinutes($clockOut)
            : $attendance->hours_worked;

        $attendance->update([
            'clock_in'       => $clockIn,
            'clock_out'      => $clockOut,
            'hours_worked'   => $hoursWorked,
            'status'         => $request->status,
            'note'           => $request->note,
            'admin_override' => true,
            'overridden_by'  => Auth::id(),
        ]);

        return back()->with('success', 'Attendance record updated successfully.');
    }

    // ── Delete record ──────────────────────────────────────────
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Attendance record deleted.');
    }

    // ── Employees manager ──────────────────────────────────────
    public function employees()
    {
        $employees = Employee::with('shift')
            ->when(request('search'), fn($q) =>
                $q->where('name', 'like', '%'.request('search').'%')
                  ->orWhere('email', 'like', '%'.request('search').'%')
                  ->orWhere('phone', 'like', '%'.request('search').'%')
            )
            ->when(request('role'),     fn($q) => $q->where('role', request('role')))
            ->when(request('shift_id'), fn($q) => $q->where('shift_id', request('shift_id')))
            ->when(request('status') === 'active',   fn($q) => $q->where('is_active', true))
            ->when(request('status') === 'inactive', fn($q) => $q->where('is_active', false))
            ->latest()
            ->paginate(20);

        $shifts = Shift::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total'      => Employee::count(),
            'active'     => Employee::where('is_active', true)->count(),
            'inactive'   => Employee::where('is_active', false)->count(),
            'clocked_in' => Attendance::whereDate('date', today())
                                ->whereNotNull('clock_in')
                                ->whereNull('clock_out')
                                ->count(),
        ];

        return view('admin.attendance.employees', compact('employees', 'shifts', 'stats'));
    }

    // ── Shifts manager ─────────────────────────────────────────
    public function shifts()
    {
        $shifts = Shift::withCount('employees')->latest()->get();
        return view('admin.attendance.shifts', compact('shifts'));
    }
}
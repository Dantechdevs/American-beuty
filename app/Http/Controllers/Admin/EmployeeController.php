<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('shift')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name',  'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('shift_id')) {
            $query->where('shift_id', $request->shift_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $employees = $query->paginate(20)->withQueryString();
        $shifts    = Shift::where('is_active', true)->orderBy('name')->get();

        // Stat counts expected by the view
        $clockedInIds = Attendance::whereDate('date', today())
                            ->whereNotNull('clock_in')
                            ->whereNull('clock_out')
                            ->pluck('employee_id');

        $stats = [
            'total'      => Employee::count(),
            'active'     => Employee::where('is_active', true)->count(),
            'inactive'   => Employee::where('is_active', false)->count(),
            'clocked_in' => $clockedInIds->count(),
        ];

        return view('admin.employees.index', compact('employees', 'shifts', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'email'       => 'nullable|email|max:150|unique:employees,email',
            'phone'       => 'nullable|string|max:20',
            'pin'         => 'nullable|digits_between:4,10|unique:employees,pin',
            'role'        => 'required|string|max:50',
            'shift_id'    => 'nullable|exists:shifts,id',
            'joined_date' => 'nullable|date',
            'is_active'   => 'nullable|boolean',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'pin', 'role', 'shift_id', 'joined_date']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        Employee::create($data);

        return back()->with('success', 'Employee "' . $request->name . '" added successfully.');
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'email'       => 'nullable|email|max:150|unique:employees,email,' . $employee->id,
            'phone'       => 'nullable|string|max:20',
            'pin'         => 'nullable|digits_between:4,10|unique:employees,pin,' . $employee->id,
            'role'        => 'required|string|max:50',
            'shift_id'    => 'nullable|exists:shifts,id',
            'joined_date' => 'nullable|date',
            'is_active'   => 'nullable|boolean',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'role', 'shift_id', 'joined_date']);
        $data['is_active'] = $request->boolean('is_active', true);

        // Only update PIN if a new one was provided
        if ($request->filled('pin')) {
            $data['pin'] = $request->pin;
        }

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($data);

        return back()->with('success', 'Employee "' . $employee->name . '" updated successfully.');
    }

    public function toggle(Employee $employee)
    {
        $employee->update(['is_active' => ! $employee->is_active]);

        $status = $employee->is_active ? 'activated' : 'deactivated';

        return back()->with('success', 'Employee "' . $employee->name . '" ' . $status . '.');
    }

    public function destroy(Employee $employee)
    {
        $name = $employee->name;

        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return back()->with('success', 'Employee "' . $name . '" deleted.');
    }

    // ── Used by attendance routes ──────────────────────────────

    public function show(Employee $employee)
    {
        $employee->load(['shift', 'attendances' => fn ($q) => $q->latest()->limit(30)]);
        return view('admin.employees.show', compact('employee'));
    }

    public function create()
    {
        $shifts = Shift::where('is_active', true)->orderBy('name')->get();
        return view('admin.employees.create', compact('shifts'));
    }

    public function edit(Employee $employee)
    {
        $shifts = Shift::where('is_active', true)->orderBy('name')->get();
        return view('admin.employees.edit', compact('employee', 'shifts'));
    }
}
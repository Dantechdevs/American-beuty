<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    // ── List ──────────────────────────────────────────────────
    public function index(Request $request)
    {
        $employees = Employee::with(['shift', 'user'])
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('phone', 'like', '%'.$request->search.'%')
            )
            ->when($request->role,     fn($q) => $q->where('role', $request->role))
            ->when($request->shift_id, fn($q) => $q->where('shift_id', $request->shift_id))
            ->when($request->status === 'active',   fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $shifts = Shift::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total'        => Employee::count(),
            'active'       => Employee::where('is_active', true)->count(),
            'inactive'     => Employee::where('is_active', false)->count(),
            'with_account' => Employee::whereNotNull('user_id')->count(),
        ];

        return view('admin.employees.index', compact('employees', 'shifts', 'stats'));
    }

    // ── Create ────────────────────────────────────────────────
    public function create()
    {
        $shifts = Shift::where('is_active', true)->orderBy('name')->get();

        // Users without an employee record linked yet
        $availableUsers = User::whereDoesntHave('employee')
            ->whereIn('role', ['manager', 'pos_operator', 'delivery'])
            ->orderBy('name')
            ->get();

        return view('admin.employees.create', compact('shifts', 'availableUsers'));
    }

    // ── Store ─────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'nullable|email|unique:employees,email',
            'phone'      => 'nullable|string|max:20',
            'pin'        => 'nullable|string|max:10|unique:employees,pin',
            'role'       => 'required|string',
            'shift_id'   => 'nullable|exists:shifts,id',
            'joined_date'=> 'nullable|date',
            'photo'      => 'nullable|image|max:2048',
            // User account fields
            'create_account'  => 'nullable|boolean',
            'user_id'         => 'nullable|exists:users,id',
            'account_role'    => 'nullable|in:manager,pos_operator,delivery',
            'account_email'   => 'nullable|email|unique:users,email',
            'account_password'=> 'nullable|string|min:8',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('employees', 'public');
        }

        $userId = null;

        // Option A — link existing user account
        if ($request->filled('user_id')) {
            $userId = $request->user_id;
        }
        // Option B — auto-create new user account
        elseif ($request->boolean('create_account') && $request->filled('account_email')) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->account_email,
                'phone'    => $request->phone,
                'role'     => $request->account_role ?? 'manager',
                'password' => Hash::make($request->account_password ?? str()->random(12)),
                'is_active'=> true,
            ]);
            $userId = $user->id;
        }

        Employee::create([
            'user_id'     => $userId,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'pin'         => $request->pin,
            'role'        => $request->role,
            'shift_id'    => $request->shift_id,
            'joined_date' => $request->joined_date,
            'photo'       => $photoPath,
            'is_active'   => true,
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    // ── Show ──────────────────────────────────────────────────
    public function show(Employee $employee)
    {
        $employee->load(['shift', 'user', 'attendances' => fn($q) =>
            $q->latest()->limit(10)
        ]);

        return view('admin.employees.show', compact('employee'));
    }

    // ── Edit ──────────────────────────────────────────────────
    public function edit(Employee $employee)
    {
        $shifts = Shift::where('is_active', true)->orderBy('name')->get();

        $availableUsers = User::whereDoesntHave('employee')
            ->whereIn('role', ['manager', 'pos_operator', 'delivery'])
            ->orderBy('name')
            ->get();

        return view('admin.employees.edit', compact('employee', 'shifts', 'availableUsers'));
    }

    // ── Update ────────────────────────────────────────────────
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'nullable|email|unique:employees,email,'.$employee->id,
            'phone'      => 'nullable|string|max:20',
            'pin'        => 'nullable|string|max:10|unique:employees,pin,'.$employee->id,
            'role'       => 'required|string',
            'shift_id'   => 'nullable|exists:shifts,id',
            'joined_date'=> 'nullable|date',
            'photo'      => 'nullable|image|max:2048',
            'user_id'    => 'nullable|exists:users,id',
        ]);

        $data = $request->only([
            'name', 'email', 'phone', 'pin',
            'role', 'shift_id', 'joined_date', 'user_id',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($data);

        return redirect()->route('admin.employees.index')
            ->with('success', $employee->name . ' updated successfully.');
    }

    // ── Toggle active ─────────────────────────────────────────
    public function toggle(Employee $employee)
    {
        $employee->update(['is_active' => !$employee->is_active]);
        return back()->with('success', 'Employee status updated.');
    }

    // ── Assign user account (AJAX-friendly) ───────────────────
    public function assignUser(Request $request, Employee $employee)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:employees,user_id',
        ]);

        $employee->update(['user_id' => $request->user_id]);

        return back()->with('success', 'User account linked to ' . $employee->name . '.');
    }

    // ── Unlink user account ───────────────────────────────────
    public function unlinkUser(Employee $employee)
    {
        $employee->update(['user_id' => null]);
        return back()->with('success', 'User account unlinked from ' . $employee->name . '.');
    }

    // ── Create account for employee on the fly ────────────────
    public function createAccount(Request $request, Employee $employee)
    {
        $request->validate([
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:manager,pos_operator,delivery',
            'password' => 'required|string|min:8',
        ]);

        if ($employee->hasLoginAccount()) {
            return back()->with('error', 'This employee already has a login account.');
        }

        $user = User::create([
            'name'      => $employee->name,
            'email'     => $request->email,
            'phone'     => $employee->phone,
            'role'      => $request->role,
            'password'  => Hash::make($request->password),
            'is_active' => true,
        ]);

        $employee->update(['user_id' => $user->id]);

        return back()->with('success', 'Login account created and linked to ' . $employee->name . '.');
    }

    // ── Destroy ───────────────────────────────────────────────
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return back()->with('success', $employee->name . ' removed.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ── Administrators ─────────────────────────────────────────
    public function administrators(Request $request)
    {
        $users = User::where('role', 'admin')
            ->when($request->search, fn($q) => $q->where(fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
            ))
            ->latest()->paginate(20)->withQueryString();

        $stats = $this->roleStats();
        return view('admin.users.administrators', compact('users', 'stats'));
    }

    // ── Managers ───────────────────────────────────────────────
    public function managers(Request $request)
    {
        $users = User::where('role', 'manager')
            ->when($request->search, fn($q) => $q->where(fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
            ))
            ->latest()->paginate(20)->withQueryString();

        $stats = $this->roleStats();
        return view('admin.users.managers', compact('users', 'stats'));
    }

    // ── POS Operators ──────────────────────────────────────────
    public function posOperators(Request $request)
    {
        $users = User::where('role', 'pos_operator')
            ->when($request->search, fn($q) => $q->where(fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
            ))
            ->latest()->paginate(20)->withQueryString();

        $stats = $this->roleStats();
        return view('admin.users.pos_operators', compact('users', 'stats'));
    }

    // ── Delivery Personnel ─────────────────────────────────────
    public function delivery(Request $request)
    {
        $users = User::where('role', 'delivery')
            ->when($request->search, fn($q) => $q->where(fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
            ))
            ->latest()->paginate(20)->withQueryString();

        $stats = $this->roleStats();
        return view('admin.users.delivery', compact('users', 'stats'));
    }

    // ── Customers ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $users = User::where('role', 'customer')
            ->when($request->search, fn($q) => $q->where(fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
            ))
            ->withCount('orders')
            ->latest()->paginate(20)->withQueryString();

        $stats = $this->roleStats();
        return view('admin.users.index', compact('users', 'stats'));
    }

    // ── Create / Store ─────────────────────────────────────────
    public function create(Request $request)
    {
        $role = $request->get('role', 'customer');
        return view('admin.users.create', compact('role'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|in:admin,manager,pos_operator,delivery,customer',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'role'      => $request->role,
            'password'  => Hash::make($request->password),
            'is_active' => true,
        ]);

        return redirect($this->redirectForRole($request->role))
            ->with('success', ucfirst($request->role) . ' created successfully.');
    }

    // ── Edit / Update ──────────────────────────────────────────
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,'.$user->id,
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|in:admin,manager,pos_operator,delivery,customer',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role'  => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect($this->redirectForRole($user->role))
            ->with('success', $user->name . ' updated successfully.');
    }

    // ── Toggle Status ──────────────────────────────────────────
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated.');
    }

    // ── Destroy ────────────────────────────────────────────────
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return back()->with('success', $user->name . ' deleted successfully.');
    }

    // ── Helpers ────────────────────────────────────────────────
    private function roleStats(): array
    {
        return [
            'admin'        => User::where('role', 'admin')->count(),
            'manager'      => User::where('role', 'manager')->count(),
            'pos_operator' => User::where('role', 'pos_operator')->count(),
            'delivery'     => User::where('role', 'delivery')->count(),
            'customer'     => User::where('role', 'customer')->count(),
            'total'        => User::count(),
        ];
    }

    private function redirectForRole(string $role): string
    {
        return match($role) {
            'admin'        => route('admin.users.administrators'),
            'manager'      => route('admin.users.managers'),
            'pos_operator' => route('admin.users.pos-operators'),
            'delivery'     => route('admin.users.delivery'),
            default        => route('admin.users.index'),
        };
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')
            ->with('permissions')
            ->orderBy('created_at')
            ->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get()
            ->groupBy('group');

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:50', 'unique:roles', 'regex:/^[a-z0-9\-]+$/'],
            'display_name' => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string', 'max:255'],
            'color'        => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'permissions'  => ['nullable', 'array'],
            'permissions.*'=> ['string', 'exists:permissions,name'],
        ]);

        $role = Role::create([
            'name'         => $data['name'],
            'display_name' => $data['display_name'],
            'description'  => $data['description'] ?? null,
            'color'        => $data['color'] ?? '#6366f1',
        ]);

        if (! empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', "Role \"{$role->display_name}\" created successfully.");
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get()
            ->groupBy('group');

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        // Prevent editing super-admin role name
        if ($role->name === 'super-admin' && $request->name !== 'super-admin') {
            return back()->withErrors(['name' => 'Cannot rename the super-admin role.']);
        }

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:50', Rule::unique('roles')->ignore($role), 'regex:/^[a-z0-9\-]+$/'],
            'display_name' => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string', 'max:255'],
            'color'        => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'permissions'  => ['nullable', 'array'],
            'permissions.*'=> ['string', 'exists:permissions,name'],
        ]);

        $role->update([
            'name'         => $data['name'],
            'display_name' => $data['display_name'],
            'description'  => $data['description'] ?? null,
            'color'        => $data['color'] ?? '#6366f1',
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', "Role \"{$role->display_name}\" updated successfully.");
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super-admin') {
            return back()->withErrors(['role' => 'Cannot delete the super-admin role.']);
        }

        if ($role->users()->count() > 0) {
            return back()->withErrors(['role' => "Cannot delete role with {$role->users()->count()} assigned user(s). Reassign them first."]);
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', "Role \"{$role->display_name}\" deleted.");
    }

    // ─── Assign roles to a user ───────────────────────────────────────────

    public function assignToUser(Request $request, User $user)
    {
        $data = $request->validate([
            'roles'   => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user->syncRoles($data['roles']);

        return back()->with('success', "Roles updated for {$user->name}.");
    }
}
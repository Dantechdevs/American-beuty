<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

trait HasRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    // ─── Role Checks ────────────────────────────────────────────────────────

    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;
        return $this->roles->whereIn('name', $roles)->isNotEmpty();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->hasRole($roles);
    }

    public function hasAllRoles(array $roles): bool
    {
        $userRoles = $this->roles->pluck('name')->toArray();
        return empty(array_diff($roles, $userRoles));
    }

    public function assignRole(string|array $roles): void
    {
        $ids = Role::whereIn('name', (array) $roles)->pluck('id');
        $this->roles()->syncWithoutDetaching($ids);
    }

    public function removeRole(string|array $roles): void
    {
        $ids = Role::whereIn('name', (array) $roles)->pluck('id');
        $this->roles()->detach($ids);
    }

    public function syncRoles(array $roles): void
    {
        $ids = Role::whereIn('name', $roles)->pluck('id');
        $this->roles()->sync($ids);
    }

    // ─── Permission Checks ───────────────────────────────────────────────────

    /**
     * All permissions this user has via their roles (deduplicated).
     */
    public function getAllPermissions(): Collection
    {
        return $this->roles
            ->load('permissions')
            ->flatMap(fn ($role) => $role->permissions)
            ->unique('id')
            ->values();
    }

    /**
     * Override Laravel's can() — must match base signature (no strict types).
     */
    public function can($permission, $arguments = []): bool
    {
        // Super-admin bypasses all checks
        if ($this->hasRole('super-admin')) {
            return true;
        }

        return $this->getAllPermissions()->contains('name', $permission);
    }

    public function cannot($permission, $arguments = []): bool
    {
        return ! $this->can($permission);
    }

    public function hasPermission(string $permission): bool
    {
        return $this->can($permission);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->can($permission)) return true;
        }
        return false;
    }

    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->cannot($permission)) return false;
        }
        return true;
    }

    // ─── Convenience ────────────────────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(['super-admin', 'admin']);
    }
}
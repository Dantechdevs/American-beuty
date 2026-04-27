<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = ['name', 'display_name', 'group', 'description'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }

    /**
     * Return all permissions grouped by their 'group' field.
     * Used in the admin UI to render permission checkboxes grouped by module.
     */
    public static function groupedPermissions(): array
    {
        return static::all()
            ->groupBy('group')
            ->map(fn ($perms) => $perms->values())
            ->toArray();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'is_default'];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function hasPermission($slug): bool
    {
        return $this->permissions()->where('slug', $slug)->exists();
    }

    public function hasAnyPermission(array $slugs): bool
    {
        return $this->permissions()->whereIn('slug', $slugs)->exists();
    }

    public function hasAllPermissions(array $slugs): bool
    {
        $count = $this->permissions()->whereIn('slug', $slugs)->count();
        return $count === count($slugs);
    }

    public function givePermission($slug): void
    {
        $permission = Permission::where('slug', $slug)->first();
        if ($permission && !$this->hasPermission($slug)) {
            $this->permissions()->attach($permission);
        }
    }

    public function revokePermission($slug): void
    {
        $permission = Permission::where('slug', $slug)->first();
        if ($permission) {
            $this->permissions()->detach($permission);
        }
    }

    public function syncPermissions(array $slugs): void
    {
        $permissions = Permission::whereIn('slug', $slugs)->pluck('id');
        $this->permissions()->sync($permissions);
    }
}
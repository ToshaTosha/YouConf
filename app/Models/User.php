<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'vk_id',
        'first_name',
        'last_name',
        'avatar',
        'email',
        'role_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($roleName): bool
    {
        return $this->role->name === $roleName;
    }
}

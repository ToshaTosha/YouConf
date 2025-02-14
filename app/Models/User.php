<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;
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

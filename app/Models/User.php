<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use HasFactory;
    use HasRoles;

    protected $fillable = [
        'vk_id',
        'first_name',
        'last_name',
        'avatar',
        'email',
        'password'
    ];

    public function getRoleIdAttribute()
    {
        return $this->roles->first()?->id;
    }

    public function setRoleIdAttribute($value)
    {
        $this->syncRoles(Role::find($value));
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'section_user');
    }
}

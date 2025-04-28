<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'vk_id',
        'first_name',
        'last_name',
        'avatar',
        'email',
        'password'
    ];

    // protected $appends = [
    //     'role_id',
    //     'unread_notifications_count',
    //     'full_name'
    // ];

    public function getRoleIdAttribute()
    {
        return $this->roles->first()?->id;
    }

    public function setRoleIdAttribute($value)
    {
        $this->syncRoles(Role::find($value));
    }
    public function performances()
    {
        return $this->hasMany(Performance::class);
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}

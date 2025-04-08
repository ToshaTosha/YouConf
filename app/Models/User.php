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

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'section_user');
    }

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }

    // Количество непрочитанных уведомлений
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    // Полное имя пользователя
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    // Последние уведомления (для удобства)
    public function recentNotifications($limit = 5)
    {
        return $this->notifications()
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }
}

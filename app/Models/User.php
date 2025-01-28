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
        'role',
    ];

    public function isExpert(): bool
    {
        return $this->role === 'expert';
    }

    public function isParticipant(): bool
    {
        return $this->role === 'participant';
    }
}

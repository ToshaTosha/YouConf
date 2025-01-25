<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'vk_id',       // ID пользователя в VK
        'first_name',  // Имя
        'last_name',   // Фамилия
        'avatar',      // Ссылка на аватар
        'email',       // Email (если есть)
    ];
}

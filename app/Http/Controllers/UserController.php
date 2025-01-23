<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;

class UserController extends Controller
{
    public function show($id)
    {
        //$user = User::findOrFail($id);
        $user = [
            'name' => 'Тестовый Пользователь',
            'email' => 'test@example.com',
            'vk_id' => '123456789',
        ];
        return Inertia::render('UserProfile', [
            'user' => $user,
        ]);
    }
}

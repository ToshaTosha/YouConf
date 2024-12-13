<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);
        return Inertia::render('User', [
            'user' => $user,
        ]);
    }
}

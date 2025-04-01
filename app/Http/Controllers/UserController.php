<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Performance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function show($id)
    {
        Log::info('showLoginForm called');

        $user = User::with('roles')->findOrFail($id);

        $performances = Performance::where('user_id', $user->id)
            ->with(['status:id,name', 'section:id,name'])
            ->get();
        return Inertia::render('UserProfile', [
            'performances' => $performances,
        ]);
    }

    public function switchUser($userId)
    {
        $user = User::find($userId);

        if ($user) {
            Auth::login($user);
            return redirect()->back()->with('success', 'Вы успешно переключились на пользователя: ' . $user->name);
        }

        return redirect()->back()->with('error', 'Пользователь не найден.');
    }
}

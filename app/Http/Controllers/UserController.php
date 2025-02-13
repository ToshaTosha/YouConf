<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show($id)
    {

        $user = User::with('role')->findOrFail($id);
        $applications = Application::where('user_id', $user->id)
            ->with(['status:id,name', 'section:id,name'])
            ->get();
        return Inertia::render('UserProfile', [
            'applications' => $applications,
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

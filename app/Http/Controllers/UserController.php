<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Performance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

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

    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Проверяем, является ли текущий пользователь владельцем профиля
        if (Auth::user()->id !== $user->id) {
            return response()->json(['error' => 'У вас нет прав для редактирования этого профиля.'], 403);
        }

        return Inertia::render('EditUserProfile', [
            'user_data' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Проверяем, является ли текущий пользователь владельцем профиля
        if (Auth::user()->id !== $user->id) {
            return response()->json(['error' => 'У вас нет прав для редактирования этого профиля.'], 403);
        }

        // Валидация данных
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        // Обновляем данные пользователя
        $user->update($request->only(['first_name', 'last_name', 'email']));

        return redirect()->route('user.show', ['id' => $user->id]);
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

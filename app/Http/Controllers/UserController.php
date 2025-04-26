<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Performance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
            return redirect()->back()->withErrors(['error' => 'У вас нет прав для редактирования этого профиля.'], 403);
        }


        return Inertia::render('EditUserProfile', [
            'user_data' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|alpha|max:255',
                'last_name' => 'required|string|alpha|max:255',
            ]);
            $user = User::findOrFail($id);
            $user->update($validated);

            return redirect()->route('user.show', ['id' => $user->id]);
        } catch (ValidationException $e) {
            return Inertia::render('EditUserProfile', [
                'errors' => $e->validator->errors(),
            ]);
        }
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

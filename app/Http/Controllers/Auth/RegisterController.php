<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|alpha|max:255',
                'last_name' => 'required|string|alpha|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $participantRole = Role::where('name', 'participant')->first();
            $user->assignRole($participantRole);

            Auth::login($user);

            // Редиректим на страницу пользователя
            return redirect()->intended(route('user.show', ['user' => $user->id]));
        } catch (ValidationException $e) {
            return Inertia::render('Auth/Register', [
                'errors' => $e->validator->errors(),
                'user_data' => $request->all(), // Возвращаем введенные данные
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return back()->with([
                'message' => 'Произошла ошибка при регистрации: ' . $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }
}

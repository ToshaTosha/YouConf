<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('user.show', ['id' => Auth::user()->id]);
        }
        return Inertia::render('Auth/Login');
    }

    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('vkontakte')->user();

            $authUser = User::where('vk_id', $user->id)->first();
            Log::info($user->user);
            // Log::info($authUser);

            if (!$authUser) {
                $authUser = User::create([
                    'vk_id' => $user['id'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'avatar' => $user['photo_200'],
                    'email' => $user['email'],
                    'role_id' => Role::where('name', 'participant')->first()->id,
                ]);
            }

            Auth::login($authUser, true);

            return redirect()->intended(route('user.show', ['id' => Auth::user()->id]));
        } catch (\Exception $e) {
            Log::error('VK auth error: ' . $e->getMessage());
            return redirect('/login')->withErrors(['error' => 'Ошибка авторизации: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

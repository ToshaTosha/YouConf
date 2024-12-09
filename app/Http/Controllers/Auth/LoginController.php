<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('user.show', ['id' => Auth::user()->id]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function handleProviderCallback()
    {
        Log::info('handleProviderCallback called');
        try {
            $user = Socialite::driver('vkontakte')->user();
            $authUser = User::where('vk_id', $user->getId())->first();

            if (!$authUser) {
                $authUser = User::create([
                    'vk_id' => $user->getId(),
                    'name' => $user->getName(),
                ]);
            }

            Auth::login($authUser, true);

            return redirect()->route('user.show', ['id' => Auth::user()->id]);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect('/login')->withErrors(['error' => 'Ошибка авторизации: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('user.profile');
        }

        return back()->withErrors([
            'email' => 'Неверные учетные данные.',
        ]);
    }

    public function redirectToProvider()
    {
        return Socialite::driver('vkontakte')->redirect();
    }

    public function handleProviderCallback()
    {
        $vkUser = Socialite::driver('vk')->user();
        $user = Auth::user();

        if (is_null($user->vk_id)) {
            $user->vk_id = $vkUser->getId();
            $user->save();
        }

        return redirect()->route('user.profile');
    }
}

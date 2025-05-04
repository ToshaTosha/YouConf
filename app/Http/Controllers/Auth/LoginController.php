<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('user.show', ['id' => Auth::user()->id]);
        }
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($validated, $request->remember)) {
                $request->session()->regenerate();
                return Inertia::location(route('user.show', ['id' => Auth::user()->id]));
            }

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        } catch (ValidationException $e) {
            return Inertia::render('Auth/Login', [
                'errors' => $e->validator->errors(),
                'user_data' => $request->only('email', 'remember'),
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return back()->with([
                'message' => 'Произошла ошибка при входе: ' . $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }

    public function handleProviderCallback()
    {
        try {
            Log::info('Starting VK auth callback handling.');
            $user = Socialite::driver('vkontakte')->user();
            Log::info('VK user data received.', ['user_id' => $user->id]);

            $authUser = User::where('vk_id', $user->id)->first();
            if (!$authUser) {
                $authUser = User::create([
                    'vk_id' => $user['id'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'avatar' => $user['photo_200'],
                    'email' => $user['email'],
                ]);

                $participantRole = Role::where('name', 'participant')->first();
                $authUser->assignRole($participantRole);
            }

            Auth::login($authUser, true);

            return Redirect::to(session()->get('url.intended', route('user.show', ['id' => Auth::user()->id])));
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

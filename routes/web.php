<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Models\Section;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/about', function () {
    return Inertia::render('About');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/auth/vk', function () {
    return Socialite::driver('vkontakte')->redirect();
});
Route::get('vk/auth/callback', [LoginController::class, 'handleProviderCallback'])->name('auth.vk.callback');

Route::middleware(['auth'])->group(function () {
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
});

use App\Http\Controllers\SectionController;

Route::get('/sections', [SectionController::class, 'index']);
Route::get('/sections/{section}', [SectionController::class, 'show']);

<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Models\Section;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApplicationController;


Route::get('/about', function () {
    return Inertia::render('About');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/auth/vk', function () {
    return Socialite::driver('vkontakte')->redirect();
})->name('auth.vk');
Route::get('vk/auth/callback', [LoginController::class, 'handleProviderCallback'])->name('auth.vk.callback');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/', function () {
        return Inertia::render('Home');
    });
    Route::get('/sections', [SectionController::class, 'index']);
    Route::get('/sections/{section}', [SectionController::class, 'show']);

    Route::post('/sections/{section}/apply', [ApplicationController::class, 'store'])->name('sections.apply');
});

if (app()->environment('local')) {
    Route::get('/switch-user/{userId}', [UserController::class, 'switchUser'])->name('switch.user');
}

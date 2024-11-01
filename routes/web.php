<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\Auth\LoginController;

Route::get('login', function () {
    return view('auth.login');
})->name('login');

Route::post('login', [LoginController::class, 'login']);

Route::get('auth/vk', [LoginController::class, 'redirectToProvider'])->name('auth.vk');
Route::get('auth/vk/callback', [LoginController::class, 'handleProviderCallback'])->name('auth.vk.callback');

use App\Http\Controllers\Auth\RegisterController;

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('register', [RegisterController::class, 'register'])->name('register');

Route::get('user/profile', function () {
    return view('user.profile');
})->name('user.profile');

use Illuminate\Support\Facades\Auth;

Route::post('logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

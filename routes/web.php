<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\SectionController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\ChatController;


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

    Route::get('/performances/create/{section_id}', [PerformanceController::class, 'create'])->name('performances.create');
    Route::post('/performances/{section_id}/apply', [PerformanceController::class, 'apply'])->name('performances.apply');

    Route::middleware('check.performance.owner')->group(function () {
        Route::get('/performances/{id}', [PerformanceController::class, 'show'])->name('performances.show');
        Route::get('/performances/{id}/edit', [PerformanceController::class, 'edit'])->name('performances.edit');
        Route::post('/performances/{id}/update', [PerformanceController::class, 'update'])->name('performances.update');
    });
    Route::post('/chats/{chat}/messages', [ChatController::class, 'storeMessage']);
});

Route::get('/', function () {
    return Inertia::render('Home');
});
Route::get('/sections', [SectionController::class, 'index']);
Route::get('/sections/{section}', [SectionController::class, 'show']);

Route::middleware(['auth'])->group(function () {
    Route::get('/performances', [PerformanceController::class, 'index'])->name('performances.index');
    Route::post('/performances/{id}/status', [PerformanceController::class, 'updateStatus'])->name('performances.updateStatus');
});


// routes/web.php

if (app()->environment('local')) {
    Route::get('/switch-user/{userId}', [UserController::class, 'switchUser'])->name('switch.user');
}

use App\Http\Controllers\ScheduleController;

Route::get('/schedules', [ScheduleController::class, 'show'])->name('schedules.show');

Route::get('/schedules/section/{sectionId}', [ScheduleController::class, 'getPerformancesBySection']);

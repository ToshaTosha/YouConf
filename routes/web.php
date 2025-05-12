<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\SectionController;
use App\Http\Controllers\ThesisController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\QuillUploadController;


Route::get('/about', function () {
    return Inertia::render('About');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/auth/vk', function () {
    return Socialite::driver('vkontakte')->redirect();
})->name('auth.vk');
Route::get('vk/auth/callback', [LoginController::class, 'handleProviderCallback'])->name('auth.vk.callback');

use App\Http\Controllers\Auth\RegisterController;

// Добавляем рядом с другими auth роутами
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    Route::get('/theses/create/{section_id}', [ThesisController::class, 'create'])->name('theses.create');
    Route::post('/theses/{section_id}/apply', [ThesisController::class, 'apply'])->name('theses.apply');

    Route::middleware('check.thesis.owner')->group(function () {
        Route::get('/theses/{id}', [ThesisController::class, 'show'])->name('theses.show');
        Route::get('/theses/{id}/edit', [ThesisController::class, 'edit'])->name('theses.edit');
        Route::post('/theses/{id}/update', [ThesisController::class, 'update'])->name('theses.update');
    });
    Route::delete('/theses/{thesis}/media/{media}', [ThesisController::class, 'deleteMedia']);
    Route::post('/chats/{chat}/messages', [ChatController::class, 'storeMessage']);

    Route::post('/theses/{thesis}/files', [FileController::class, 'store']);
    ////Route::delete('/theses/{thesis}/media/{media}', [FileController::class, 'destroy']);
});

Route::get('/', [StaticPageController::class, 'show'])
    ->defaults('slug', '');

Route::get('/api/pages', [StaticPageController::class, 'getAllPages']);

Route::get('/sections', [SectionController::class, 'index']);
Route::get('/sections/{section}', [SectionController::class, 'show']);

Route::middleware(['auth'])->group(function () {
    Route::get('/theses', [ThesisController::class, 'index'])->name('theses.index');
    Route::post('/theses/{id}/status', [ThesisController::class, 'updateStatus'])->name('theses.updateStatus');
});


// routes/web.php

if (app()->environment('local')) {
    Route::get('/switch-user/{userId}', [UserController::class, 'switchUser'])->name('switch.user');
}

use App\Http\Controllers\ScheduleController;

Route::get('/schedules', [ScheduleController::class, 'show'])->name('schedules.show');

Route::get('/schedules/section/{sectionId}', [ScheduleController::class, 'getThesesBySection']);


Route::post('/quill/upload', [QuillUploadController::class, 'upload'])
    ->name('moonshine.quill.upload');


Route::get('/{slug}', [StaticPageController::class, 'show'])
    ->name('static-pages.show');

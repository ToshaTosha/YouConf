<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!method_exists($user, 'notifications')) {
            dd(
                'Notifiable trait methods missing',
                class_uses($user), // Покажет все используемые трейты
                get_class_methods($user) // Покажет все методы класса
            );
        }
        return inertia('Notifications/Index', [
            // 'notifications' => Auth::user()->notifications()->paginate(10),
            'unreadCount' => Auth::user()->unreadNotifications->count(),
        ]);
    }
}

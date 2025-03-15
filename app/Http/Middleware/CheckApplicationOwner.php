<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class CheckApplicationOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        // Получаем текущего пользователя
        $user = $request->user();

        // Если пользователь эксперт, пропускаем его
        if ($user->hasRole('expert')) {
            return $next($request);
        }

        // Получаем ID заявки из маршрута
        $applicationId = $request->route('id');
        $application = Application::findOrFail($applicationId);

        // Проверяем, является ли текущий пользователь владельцем заявки
        if ($application->user_id !== $user->id) {
            abort(403, 'У вас нет доступа к этой заявке.');
        }

        return $next($request);
    }
}

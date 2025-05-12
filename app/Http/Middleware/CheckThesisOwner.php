<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Thesis;
use Illuminate\Support\Facades\Auth;

class CheckThesisOwner
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
        $thesisId = $request->route('id');
        $thesis = Thesis::findOrFail($thesisId);

        // Проверяем, является ли текущий пользователь владельцем заявки
        if ($thesis->user_id !== $user->id) {
            abort(403, 'У вас нет доступа к этой заявке.');
        }

        return $next($request);
    }
}

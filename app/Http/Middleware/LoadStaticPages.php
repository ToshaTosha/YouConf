<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;
use App\Models\StaticPage;

class LoadStaticPages
{
    public function handle($request, Closure $next)
    {
        // Получаем все статические страницы
        $allPages = StaticPage::all(['slug', 'title']);

        // Добавляем их в пропсы Inertia
        Inertia::share([
            'allPages' => $allPages,
        ]);

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class HandleAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Передача данных о пользователе
        Inertia::share('auth', function () use ($request) {
            return [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'first_name' => $request->user()->name,
                    'avatar' => $request->user()->avatar,
                ] : null,
            ];
        });

        return $next($request);
    }
}

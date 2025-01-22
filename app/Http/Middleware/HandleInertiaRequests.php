<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class HandleInertiaRequests
{
    public function handle(Request $request, Closure $next)
    {
        // Передача данных о пользователе
        Inertia::share('auth', function () use ($request) {
            return [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'avatar' => $request->user()->avatar,
                ] : null,
            ];
        });

        Log::info('HandleInertiaRequests');

        return $next($request);
    }
}

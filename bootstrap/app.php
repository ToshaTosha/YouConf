<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\CheckThesisOwner;
use App\Http\Middleware\LoadStaticPages;
use App\Http\Middleware\HandleAuth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(HandleInertiaRequests::class);
        $middleware->append(LoadStaticPages::class);
        $middleware->alias([
            'check.thesis.owner' => CheckThesisOwner::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

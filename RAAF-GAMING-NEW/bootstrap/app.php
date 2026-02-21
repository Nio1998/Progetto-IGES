<?php

use App\Http\Middleware\Prodotto\GestoreOrdiniMiddleware;
use App\Http\Middleware\Prodotto\GestoreProdottiMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Profilo\RedirectIfAuthenticated;
use App\Http\Middleware\Profilo\IsAuthenticated;
use App\Http\Middleware\Profilo\IsAuthenticatedAdmin;
use App\Http\Middleware\Profilo\RedirectAdminIfAuthenticated;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'redirectIfAuthenticated' => RedirectIfAuthenticated::class,
            'isAutenticated' => IsAuthenticated::class,
            'isAutenticatedAdmin' => IsAuthenticatedAdmin::class,
            'redirectIfAuthenticatedAdmin' => RedirectAdminIfAuthenticated::class,
            'GestoreOrdini' => GestoreOrdiniMiddleware::class,
            'GestoreProdotti' => GestoreProdottiMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

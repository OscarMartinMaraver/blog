<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        //registro archivos de rutas personalizados
        then:function()
        {
            Route::middleware(['web', 'auth']) //El middleware 'web' indica que las rutas de admin.php tendrán las mismas características que las rutas web.php.
                ->prefix('admin') //este prefijo indica que las rutas de admin.php estarán bajo el prefijo /admin, por ejemplo, /admin/dashboard.
                ->name('admin.') //este nombre indica que las rutas de admin.php tendrán un prefijo de nombre admin., por ejemplo, admin.dashboard.
                ->group(base_path('routes/admin.php'));//registro el archivo de rutas admin.php
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

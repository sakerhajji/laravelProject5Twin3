<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'superadmin' => \App\Http\Middleware\superadmin::class,
            'admin' => \App\Http\Middleware\admin::class,
            'user' => \App\Http\Middleware\user::class,
            
            // Partner Management Middlewares
            'partner.management' => \App\Http\Middleware\Partner\PartnerManagement::class,
            'partner.validate' => \App\Http\Middleware\Partner\ValidatePartner::class,
            'partner.data' => \App\Http\Middleware\Partner\ValidatePartnerData::class,
            'partner.status' => \App\Http\Middleware\Partner\CheckPartnerStatus::class,
            'partner.log' => \App\Http\Middleware\Partner\LogPartnerActivity::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

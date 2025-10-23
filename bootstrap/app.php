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
            
            // Asymptome Management Middlewares
            'asymptome.check' => \App\Http\Middleware\Asymptome\AsymptomeCheck::class,
            'asymptome.management' => \App\Http\Middleware\Asymptome\AsymptomeManagement::class,
            'asymptome.validate' => \App\Http\Middleware\Asymptome\AsymptomeValidate::class,
            'asymptome.data' => \App\Http\Middleware\Asymptome\AsymptomeValidateData::class,
            'asymptome.log' => \App\Http\Middleware\Asymptome\AsymptomeLog::class,
            
            // Maladie Management Middlewares  
            'maladie.check' => \App\Http\Middleware\Maladie\MaladieCheck::class,
            'maladie.management' => \App\Http\Middleware\Maladie\MaladieManagement::class,
            'maladie.validate' => \App\Http\Middleware\Maladie\MaladieValidate::class,
            'maladie.data' => \App\Http\Middleware\Maladie\MaladieValidateData::class,
            'maladie.log' => \App\Http\Middleware\Maladie\MaladieLog::class,
            
            // Activity Management Middlewares
            'activity.management' => \App\Http\Middleware\Activity\ActivityManagement::class,
            'activity.data' => \App\Http\Middleware\Activity\ActivityValidateData::class,
            
            // Category Management Middlewares
            'category.management' => \App\Http\Middleware\Category\CategoryManagement::class,
            'category.data' => \App\Http\Middleware\Category\CategoryValidateData::class,
            
            // Frontend Access Control
            'no.admin.frontend' => \App\Http\Middleware\RestrictAdminFromFrontend::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

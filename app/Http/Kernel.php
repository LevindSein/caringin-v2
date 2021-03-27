<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        
        'ceklogin' => \App\Http\Middleware\CekLogin::class,
        'dashboard' => \App\Http\Middleware\Dashboard::class,
        'layanan' => \App\Http\Middleware\Layanan::class,
        'pedagang' => \App\Http\Middleware\Pedagang::class,
        'tempatusaha' => \App\Http\Middleware\TempatUsaha::class,
        'tagihan' => \App\Http\Middleware\Tagihan::class,
        'pemakaian' => \App\Http\Middleware\Pemakaian::class,
        'pendapatan' => \App\Http\Middleware\Pendapatan::class,
        'datausaha' => \App\Http\Middleware\DataUsaha::class,
        'tarif' => \App\Http\Middleware\Tarif::class,
        'alatmeter' => \App\Http\Middleware\AlatMeter::class,
        'harilibur' => \App\Http\Middleware\HariLibur::class,
        'blok' => \App\Http\Middleware\Blok::class,
        'simulasi' => \App\Http\Middleware\Simulasi::class,
        'master' => \App\Http\Middleware\Master::class,
        'user' => \App\Http\Middleware\User::class,
        'information' => \App\Http\Middleware\Information::class,
        'log' => \App\Http\Middleware\Log::class,
        'human' => \App\Http\Middleware\Human::class,

        'kasir' => \App\Http\Middleware\Kasir::class,
        
        'keuangan' => \App\Http\Middleware\Keuangan::class,
    ];
}

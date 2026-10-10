<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Set locale for every request (API and web)
        \App\Http\Middleware\AutoSetLocale::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\AutoSetLocale::class,
        ],
        'api' => [
            // Locale only; all responses rely on key-based translations
            \App\Http\Middleware\AutoSetLocale::class,
            \App\Http\Middleware\RequireApiKey::class,
        ],
    ];

    protected $middlewareAliases = [
        'admin' => \App\Http\Middleware\EnsureAdmin::class,
        'single.device' => \App\Http\Middleware\CheckSingleDeviceSession::class,
    ];
}

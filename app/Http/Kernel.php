<?php

namespace FI\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \FI\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \FI\Http\Middleware\VerifyCsrfToken::class,
        \FI\Http\Middleware\BeforeMiddleware::class,
        \FI\Http\Middleware\AfterMiddleware::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'              => \FI\Http\Middleware\Authenticate::class,
        'auth.admin'        => \FI\Http\Middleware\AuthenticateAdmin::class,
        'auth.api'          => \FI\Http\Middleware\AuthenticateAPI::class,
        'auth.basic'        => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.clientCenter' => \FI\Http\Middleware\AuthenticateClientCenter::class,
        'guest'             => \FI\Http\Middleware\RedirectIfAuthenticated::class,
    ];
}

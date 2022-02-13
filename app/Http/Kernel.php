<?php

namespace App\Http;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DebugBarMiddleware;
use App\Http\Middleware\IntegrationEnabled;
use App\Http\Middleware\LogOutDisabledUsers;
use App\Http\Middleware\SetActiveAt;
use App\Http\Middleware\SetupCheck;
use App\Http\Middleware\WelcomeCheck;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * La pile de middleware HTTP globale de l'application.
     *
     * Ces middleware sont exécutés lors de chaque demande de votre application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fideloper\Proxy\TrustProxies::class,
    ];

    /**
     * Les groupes de middleware de routage de l'application.
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
            \App\Http\Middleware\HttpsProtocol::class,
            LogOutDisabledUsers::class,
            DebugBarMiddleware::class,
            SetActiveAt::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
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
        'auth'                 => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic'           => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings'             => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can'                  => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'                => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle'             => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'setup_required'       => SetupCheck::class,
        'welcome_passed'       => WelcomeCheck::class,
        'role'                 => \Laratrust\Middleware\LaratrustRole::class,
        'permission'           => \Laratrust\Middleware\LaratrustPermission::class,
        'ability'              => \Laratrust\Middleware\LaratrustAbility::class,
        'admin'                => AdminMiddleware::class,
        'integration_required' => IntegrationEnabled::class,
    ];
}

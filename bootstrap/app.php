<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// --- CORE FRAMEWORK MIDDLEWARE IMPORTS ---
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken; 
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Routing\Middleware\ThrottleRequests;

// --- AUTH & INERTIA MIDDLEWARE IMPORTS ---
use Laravel\Sanctum\Http\Middleware\AuthenticateSession;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

// --- CUSTOM APPLICATION MIDDLEWARE IMPORTS ---
use App\Http\Middleware\CheckCustomMaintenanceMode;
use App\Http\Middleware\AdminMiddleware; 



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,            // Session starts here
            ShareErrorsFromSession::class,  // Errors from session are shared
            VerifyCsrfToken::class,         // CSRF token is validated (uses session)
            ValidatePostSize::class,        // Max request size check
            TrimStrings::class,             // Trims string input data
            ConvertEmptyStringsToNull::class, // Converts empty strings to null

            CheckCustomMaintenanceMode::class, 
            
            AuthenticateSession::class, 

            HandleInertiaRequests::class,            
            AddLinkHeadersForPreloadedAssets::class, 

            SubstituteBindings::class, 
        ]);


        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
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

// --- YOUR CUSTOM APPLICATION MIDDLEWARE IMPORTS ---
use App\Http\Middleware\CheckCustomMaintenanceMode;
use App\Http\Middleware\AdminMiddleware; 

// --- Global middleware are typically configured during Application::configure, not inside withMiddleware directly like global() ---
// These are standard Laravel 11/12 defaults usually applied:
// use Illuminate\Http\Middleware\TrustProxies;
// use Illuminate\Http\Middleware\HandleCors;
// use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;


return Application::configure(basePath: dirname(__DIR__))
    // TrustProxies, HandleCors, PreventRequestsDuringMaintenance are often applied here directly
    // or as part of the base configuration, not within withMiddleware() in this manner.
    // For a standard Laravel 11/12 install, these are usually implicit or handled
    // within the 'boot' method of a ServiceProvider if customized.
    // Given your original app structure didn't show Kernel.php, let's assume
    // these are part of Laravel's default base setup and don't need explicit re-adding.
    // If you need to explicitly define them, they are usually in the Application::configure chain
    // like ->withRouting()->withProviders()->withExceptions()->create();
    // For now, let's remove the problematic global() call.
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
            // Laravel's fundamental web middleware stack (order is crucial)
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,            // Session starts here
            ShareErrorsFromSession::class,  // Errors from session are shared
            VerifyCsrfToken::class,         // CSRF token is validated (uses session)
            ValidatePostSize::class,        // Max request size check
            TrimStrings::class,             // Trims string input data
            ConvertEmptyStringsToNull::class, // Converts empty strings to null

            // YOUR CUSTOM MAINTENANCE MODE MIDDLEWARE
            CheckCustomMaintenanceMode::class, 
            
            // Standard Auth Middleware
            AuthenticateSession::class, 

            // Inertia.js specific middleware
            HandleInertiaRequests::class,            
            AddLinkHeadersForPreloadedAssets::class, 

            // Route related middleware
            SubstituteBindings::class, 
        ]);

        // --- REMOVED THE PROBLEMATIC $middleware->global([...]) CALL ---

        $middleware->alias([
            'admin' => AdminMiddleware::class,
            // ... other aliases ...
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
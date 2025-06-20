<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckCustomMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('Maintenance Check: Request received.', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'is_inertia' => $request->header('X-Inertia') ? 'Yes' : 'No',
            'has_session_bypass' => session('maintenance_bypass') ? 'Yes' : 'No',
            'auth_check' => Auth::check() ? 'Yes' : 'No',
            'route_name' => $request->route() ? $request->route()->getName() : 'N/A',
        ]);

        // 1. Check if maintenance mode is ON.
        if (config('app.maintenance_mode_enabled') === true) {
            Log::info('Maintenance Check: Mode is ENABLED.', [
                'current_route' => $request->route() ? $request->route()->getName() : 'N/A',
                'method' => $request->method(),
            ]);

            // 2. IMMEDIATE BYPASS FOR AUTHENTICATED ADMINS (identified by session flag set on login)
            if (session('maintenance_bypass') === true && Auth::check()) {
                Log::info('Maintenance Check: Admin bypass active, allowing normal access.');
                return $next($request); // Let the authenticated admin pass
            }

            // 3. Define public routes allowed *in* maintenance for everyone (unauthenticated users)
            $allowedPublicRoutesInMaintenance = [
                'login',
                'logout',
                'password.request',
                'password.email',
                'password.reset',
                'password.store',
            ];

            $requestPath = trim($request->path(), '/');

            foreach ($allowedPublicRoutesInMaintenance as $allowedPath) {
                if (str_starts_with($requestPath, $allowedPath)) {
                    Log::info("Maintenance Check: Path '{$requestPath}' MATCHED against allowed '{$allowedPath}', allowing through.");
                    return $next($request); // Let these public auth routes pass
                }
            }

            // 4. If maintenance mode is ON, and NOT an admin bypass, and NOT an allowed public route,
            //    then render the MaintenanceMode Inertia page.
            Log::info('Maintenance Check: Rendering maintenance page (no bypass, not allowed public route).', [
                'current_path' => $requestPath,
                'method' => $request->method(),
            ]);
            return Inertia::render('MaintenanceMode', [
                'status' => session('status') ?: null,
                'canResetPassword' => Route::has('password.request'),
            ]);
        }

        // 5. If maintenance mode is OFF, let all requests pass normally.
        Log::info('Maintenance Check: Mode is OFF, proceeding normally.');
        return $next($request);
    }
}
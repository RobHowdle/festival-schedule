<?php

// app/Http/Controllers/Auth/AuthenticatedSessionController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{

        /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate(); // This attempts login and handles validation errors

        $request->session()->regenerate();

        // Set a session flag to bypass maintenance mode if maintenance is currently active.
        // This means ANY successfully logged-in user can bypass it.
        if (config('app.maintenance_mode_enabled') === true && Auth::user()->is_admin) {
             session(['maintenance_bypass' => true]);
        }

        return redirect()->intended(self::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear maintenance bypass flag on logout
        session()->forget('maintenance_bypass'); 

        return redirect('/');
    }
}
<?php

// routes/api.php

use App\Http\Controllers\Api\UserController; // Import your new controller
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ... (any existing API routes) ...

Route::middleware('auth:sanctum')->group(function () {
    // Authenticated API routes, for user-specific data or sensitive operations
    // You might want to protect your admin API routes here with a 'can:admin-access' middleware later
});

// Public or implicitly authenticated API routes (handled by Inertia's X-Requested-With)
Route::get('/admin/users/api', [UserController::class, 'index'])->middleware('auth'); // <-- ADD THIS LINE
// The 'auth' middleware here protects this API route, so only logged-in users can access it.
// Ensure 'sanctum' is configured if you're using API tokens for other API parts.
// For this specific use-case with Inertia, 'auth' middleware is sufficient as it relies on Laravel's session.
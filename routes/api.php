<?php

// routes/api.php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
});

// Public or implicitly authenticated API routes (handled by Inertia's X-Requested-With)
Route::get('/admin/users/api', [UserController::class, 'index'])->middleware('auth');
// The 'auth' middleware here protects this API route, so only logged-in users can access it.
// For this specific use-case with Inertia, 'auth' middleware is sufficient as it relies on Laravel's session.
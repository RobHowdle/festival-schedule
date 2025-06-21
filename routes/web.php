<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\LayoutSettingsController;
use App\Http\Controllers\Admin\StageController as AdminStageController;
use App\Http\Controllers\Admin\ArtistController as AdminArtistController;
use App\Http\Controllers\Admin\VendorController as AdminVendorController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Dashboard Route - Only accessible by admin users
Route::get('/admin-dashboard', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes group
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users/api', [UserController::class, 'index'])->name('users.api');

    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/artists/api', [AdminArtistController::class, 'apiIndex'])->name('artists.api');

    Route::get('/stages/api', [AdminStageController::class, 'apiIndex'])->name('stages.api');

    Route::resource('artists', AdminArtistController::class);
    Route::resource('stages', AdminStageController::class);
    Route::resource('vendors', AdminVendorController::class);

    // Layout Settings
    Route::get('/layout', [LayoutSettingsController::class, 'index'])->name('layout.index');
    Route::post('/layout/colors', [LayoutSettingsController::class, 'updateColors'])->name('layout.colors.update');
    Route::post('/layout/background', [LayoutSettingsController::class, 'updateLayout'])->name('layout.background');
    Route::post('/layout/apply-palette', [LayoutSettingsController::class, 'applyColorPalette'])->name('layout.apply-palette');
    Route::delete('/layout/palette', [LayoutSettingsController::class, 'deleteColorPalette'])->name('layout.delete-palette');
});


require __DIR__ . '/auth.php';

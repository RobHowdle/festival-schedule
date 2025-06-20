<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        return Inertia::render('AdminDashboard', [
            'users_count' => \App\Models\User::count(),
            'artists_count' => \App\Models\Artist::count(),
        ]);
    }
}
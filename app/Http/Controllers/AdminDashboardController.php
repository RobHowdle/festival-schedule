<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Artist;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('AdminDashboard', [
            'users_count' => \App\Models\User::count(),
            'artists_count' => \App\Models\Artist::count(),
            'vendors_count' => \App\Models\Vendor::count() ?? 0,
            'stages_count' => \App\Models\Stage::count() ?? 0,
        ]);
    }
}
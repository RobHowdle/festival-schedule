<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StageController extends Controller
{
    public function index()
    {
        // Redirect to admin dashboard instead of dedicated index
        return redirect()->route('admin.dashboard');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'w3w_link' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'type' => 'nullable|string|max:255',
        ]);

        $stage = Stage::create($validated);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Stage created successfully!',
                'stage' => $stage
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Stage created successfully!');
    }

    public function show(Stage $stage)
    {
        return redirect()->route('admin.dashboard');
    }

    public function edit(Stage $stage)
    {
        return redirect()->route('admin.dashboard');
    }

    public function update(Request $request, Stage $stage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'w3w_link' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'type' => 'nullable|string|max:255',
        ]);

        $stage->update($validated);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Stage updated successfully!',
                'stage' => $stage->fresh()
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Stage updated successfully!');
    }

    public function destroy(Stage $stage)
    {
        $stage->delete();

        if (request()->expectsJson() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Stage deleted successfully!'
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Stage deleted successfully!');
    }
}
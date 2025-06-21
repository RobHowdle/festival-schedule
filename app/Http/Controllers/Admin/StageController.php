<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
class StageController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Store a newly created stage.
     * Corresponds to POST /admin/stages
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:stages,name',
            'location' => 'nullable|string|max:255',
            'w3w_link' => 'nullable|string|max:255|url',
            'capacity' => 'nullable|integer|min:1',
            'type' => 'nullable|string|max:255',
        ]);

        $stage = Stage::create($validated);

        // Always return RedirectResponse for Inertia after successful store
        return Redirect::route('admin.dashboard', [], 303)
            ->with('message', 'Stage created successfully!');
    }

    /**
     * Get a list of stages for API requests.
     * Corresponds to GET /admin/stages/api
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $query = Stage::query();

        $query->orderBy('name');

        $stages = $query->get([
            'id',
            'name',
            'location',
            'w3w_link',
            'capacity',
            'type',
        ]);

        return response()->json($stages);
    }

    public function show(Stage $stage)
    {
        return redirect()->route('admin.dashboard');
    }

    public function edit(Stage $stage)
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Update the specified stage in storage.
     * Corresponds to PATCH /admin/stages/{stage}
     */
    public function update(Request $request, Stage $stage): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('stages', 'name')->ignore($stage->id),
            ],
            'location' => 'nullable|string|max:255',
            'w3w_link' => 'nullable|string|max:255|url',
            'capacity' => 'nullable|integer|min:1',
            'type' => 'nullable|string|max:255',
        ]);

        $stage->update($validated);

        // Always return RedirectResponse for Inertia after successful update
        return Redirect::route('admin.dashboard', [], 303)
            ->with('message', 'Stage updated successfully!');
    }

    /**
     * Remove the specified stage from storage.
     * Corresponds to DELETE /admin/stages/{stage}
     */
    public function destroy(Stage $stage): RedirectResponse
    {
        $stage->delete();

        return Redirect::route('admin.dashboard', [], 303)
            ->with('message', 'Stage deleted successfully!');
    }
}
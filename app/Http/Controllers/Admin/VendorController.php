<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VendorController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.dashboard');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'categories' => 'nullable|string',
            'description' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'social_links' => 'nullable|string',
            'logo' => 'nullable|url',
            'tags' => 'nullable|string',
            'restrictions' => 'nullable|string',
        ];

        // Only validate menu and dietary_restrictions if type is food
        if ($request->type === 'food') {
            $rules['menu'] = 'nullable|string';
            $rules['dietary_restrictions'] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        // Convert comma-separated strings to JSON arrays
        $jsonFields = ['categories', 'social_links', 'tags', 'dietary_restrictions'];
        foreach ($jsonFields as $field) {
            if (!empty($validated[$field])) {
                // Split by comma, trim whitespace, and filter empty values
                $items = array_filter(array_map('trim', explode(',', $validated[$field])));
                $validated[$field] = json_encode($items);
            } else {
                $validated[$field] = null;
            }
        }

        // Handle restrictions as JSON too if it contains commas
        if (!empty($validated['restrictions'])) {
            if (strpos($validated['restrictions'], ',') !== false) {
                $items = array_filter(array_map('trim', explode(',', $validated['restrictions'])));
                $validated['restrictions'] = json_encode($items);
            }
        }

        $vendor = Vendor::create($validated);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Vendor created successfully!',
                'vendor' => $vendor
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Vendor created successfully!');
    }

    public function show(Vendor $vendor)
    {
        return redirect()->route('admin.dashboard');
    }

    public function edit(Vendor $vendor)
    {
        return redirect()->route('admin.dashboard');
    }

    public function update(Request $request, Vendor $vendor)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'categories' => 'nullable|string',
            'description' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'social_links' => 'nullable|string',
            'logo' => 'nullable|url',
            'tags' => 'nullable|string',
            'restrictions' => 'nullable|string',
        ];

        if ($request->type === 'food') {
            $rules['menu'] = 'nullable|string';
            $rules['dietary_restrictions'] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        // Convert comma-separated strings to JSON arrays
        $jsonFields = ['categories', 'social_links', 'tags', 'dietary_restrictions'];
        foreach ($jsonFields as $field) {
            if (!empty($validated[$field])) {
                $items = array_filter(array_map('trim', explode(',', $validated[$field])));
                $validated[$field] = json_encode($items);
            } else {
                $validated[$field] = null;
            }
        }

        // Handle restrictions as JSON too if it contains commas
        if (!empty($validated['restrictions'])) {
            if (strpos($validated['restrictions'], ',') !== false) {
                $items = array_filter(array_map('trim', explode(',', $validated['restrictions'])));
                $validated['restrictions'] = json_encode($items);
            }
        }

        $vendor->update($validated);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Vendor updated successfully!',
                'vendor' => $vendor->fresh()
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Vendor updated successfully!');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        if (request()->expectsJson() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Vendor deleted successfully!'
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Vendor deleted successfully!');
    }
}
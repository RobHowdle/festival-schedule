<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ArtistController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.dashboard');
    }

    public function create()
    {
        return redirect()->route('admin.dashboard');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
            'website' => 'nullable|url',
            'social_links' => 'nullable|string',
        ]);

        // Generate unique slug
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;

        while (Artist::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;
        $artist = Artist::create($validated);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Artist created successfully!',
                'artist' => $artist
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Artist created successfully!');
    }

    public function show(Artist $artist)
    {
        return redirect()->route('admin.dashboard');
    }

    public function edit(Artist $artist)
    {
        return redirect()->route('admin.dashboard');
    }

    public function update(Request $request, Artist $artist)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
            'website' => 'nullable|url',
            'social_links' => 'nullable|string',
        ]);

        // Only regenerate slug if name changed
        if ($validated['name'] !== $artist->name) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;

            while (Artist::where('slug', $slug)->where('id', '!=', $artist->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $validated['slug'] = $slug;
        }

        $artist->update($validated);

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Artist updated successfully!',
                'artist' => $artist->fresh()
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Artist updated successfully!');
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();

        if (request()->expectsJson() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Artist deleted successfully!'
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Artist deleted successfully!');
    }
}
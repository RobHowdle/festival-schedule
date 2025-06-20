<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artists = Artist::latest()->paginate(10);

        return Inertia::render('Admin/Artists/Index', [
            'artists' => $artists
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Artists/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

        // Handle different response types based on request
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Artist created successfully!',
                'artist' => $artist
            ]);
        }

        return redirect()->route('admin.artists.index')
            ->with('success', 'Artist created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Artist $artist)
    {
        return Inertia::render('Admin/Artists/Show', [
            'artist' => $artist
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Artist $artist)
    {
        return Inertia::render('Admin/Artists/Edit', [
            'artist' => $artist
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
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

        return redirect()->route('admin.artists.index')
            ->with('success', 'Artist updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artist $artist)
    {
        $artist->delete();

        if (request()->expectsJson() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Artist deleted successfully!'
            ]);
        }

        return redirect()->route('admin.artists.index')
            ->with('success', 'Artist deleted successfully!');
    }

    /**
     * Custom method for toggling featured status
     */
    public function toggleFeatured(Artist $artist)
    {
        $artist->update([
            'is_featured' => !$artist->is_featured
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Artist featured status updated!',
            'is_featured' => $artist->is_featured
        ]);
    }
}
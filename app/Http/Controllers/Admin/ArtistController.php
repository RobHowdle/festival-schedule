<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse; 
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

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
            'name' => 'required|string|max:255|unique:artists,name',
            'description' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
            'social_links' => 'nullable|string',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;

        while (Artist::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;

        if (isset($validated['social_links'])) {
            $socialLinksArray = array_map('trim', explode(',', $validated['social_links']));
            $socialLinksArray = array_filter($socialLinksArray);
            $validated['social_links'] = $socialLinksArray;
        } else {
            $validated['social_links'] = [];
        }

        $artist = Artist::create($validated);

        return Redirect::route('admin.dashboard', [], 303)
            ->with('message', 'Artist created successfully!');
    }

    /**
     * Get a list of artists for API requests, with optional search filtering.
     * Corresponds to GET /admin/artists/api
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $query = Artist::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('genre', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy('name');

        $artists = $query->get([
            'id',
            'name',
            'slug',
            'genre',
            'image_url',
            'description',
            'social_links',
        ]);

        return response()->json($artists);
    }

    public function show(Artist $artist)
    {
        return redirect()->route('admin.dashboard');
    }

    public function edit(Artist $artist)
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Update the specified artist in storage.
     * Corresponds to PATCH /admin/artists/{artist}
     */
    public function update(Request $request, Artist $artist)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('artists', 'name')->ignore($artist->id),
            ],
            'description' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
            'social_links' => 'nullable|string',
        ]);

        if ($validated['name'] !== $artist->name) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;

            while (Artist::where('slug', $slug)->where('id', '!=', $artist->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        } else {
            $validated['slug'] = $artist->slug;
        }

        if (isset($validated['social_links'])) {
            $socialLinksArray = array_map('trim', explode(',', $validated['social_links']));
            $socialLinksArray = array_filter($socialLinksArray);
            $validated['social_links'] = $socialLinksArray;
        } else {
            $validated['social_links'] = [];
        }
        
        $artist->update($validated);

        return Redirect::route('admin.dashboard', [], 303)
            ->with('message', 'Artist updated successfully!');
    }

    /**
     * Remove the specified artist from storage.
     * Corresponds to DELETE /admin/artists/{artist}
     */
    public function destroy(Artist $artist)
    {
        $artist->delete();

        return Redirect::route('admin.dashboard', [], 303)
            ->with('message', 'Artist deleted successfully!');
    }
}
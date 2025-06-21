<?php
// filepath: /app/Http/Controllers/Admin/LayoutSettingsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class LayoutSettingsController extends Controller
{
    public function index()
    {
        $layoutSettings = SystemSetting::getLayoutSettings();
        $colorSettings = SystemSetting::getColorSettings();

        // Get color palettes
        $colorPalettes = SystemSetting::get('color_palettes', [
            'default' => [
                'name' => 'Default',
                'primary' => '#8B5CF6',
                'secondary' => '#06B6D4',
                'accent' => '#F59E0B',
                'background' => '#1F2937',
                'text' => '#FFFFFF',
                'textSecondary' => '#9CA3AF'
            ]
        ]);

        return Inertia::render('Admin/LayoutSettings', [
            'layoutSettings' => $layoutSettings,
            'colorSettings' => $colorSettings,
            'colorPalettes' => $colorPalettes,
            'currentBackground' => SystemSetting::get('background_image'),
            'currentBackgroundType' => SystemSetting::get('background_type', 'color'),
            'currentBackgroundColor' => SystemSetting::get('background_color', '#1F2937')
        ]);
    }

    public function updateLayout(Request $request)
    {
        $validated = $request->validate([
            'background_type' => 'required|in:color,image',
            'background_color' => 'nullable|string',
            'background_image' => 'nullable|image|max:5120', // 5MB max
            'remove_background' => 'nullable|boolean'
        ]);

        // Handle background removal
        if ($request->remove_background) {
            $oldImage = SystemSetting::get('background_image');
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
            SystemSetting::set('background_image', null, 'string', 'layout');
        }

        // Handle background type
        SystemSetting::set('background_type', $validated['background_type'], 'string', 'layout');

        // Handle background color
        if ($validated['background_type'] === 'color' && $validated['background_color']) {
            SystemSetting::set('background_color', $validated['background_color'], 'string', 'layout');
        }

        // Handle background image upload
        if ($validated['background_type'] === 'image' && $request->hasFile('background_image')) {
            // Delete old background image
            $oldImage = SystemSetting::get('background_image');
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            // Store new image
            $path = $request->file('background_image')->store('backgrounds', 'public');
            SystemSetting::set('background_image', $path, 'string', 'layout');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Layout settings updated successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Layout settings updated successfully!');
    }

    public function updateColors(Request $request)
    {
        $validated = $request->validate([
            'palette_name' => 'required|string|max:255',
            'colors' => 'required|array',
            'colors.primary' => 'required|string',
            'colors.secondary' => 'required|string',
            'colors.accent' => 'required|string',
            'colors.background' => 'required|string',
            'colors.text' => 'required|string',
            'colors.textSecondary' => 'required|string',
            'apply_immediately' => 'nullable|boolean'
        ]);

        // Get existing palettes
        $colorPalettes = SystemSetting::get('color_palettes', []);

        // Add or update the palette
        $colorPalettes[$validated['palette_name']] = [
            'name' => $validated['palette_name'],
            ...$validated['colors']
        ];

        // Save palettes
        SystemSetting::set('color_palettes', $colorPalettes, 'json', 'colors');

        // Apply immediately if requested
        if ($request->apply_immediately) {
            SystemSetting::set('active_color_palette', $validated['palette_name'], 'string', 'colors');

            // Save individual color settings for easy access
            foreach ($validated['colors'] as $key => $value) {
                SystemSetting::set("color_{$key}", $value, 'string', 'colors');
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Color palette saved successfully!',
                'palettes' => $colorPalettes
            ]);
        }

        return redirect()->back()->with('success', 'Color palette saved successfully!');
    }

    public function applyColorPalette(Request $request)
    {
        $validated = $request->validate([
            'palette_name' => 'required|string'
        ]);

        $colorPalettes = SystemSetting::get('color_palettes', []);

        if (!isset($colorPalettes[$validated['palette_name']])) {
            return response()->json([
                'success' => false,
                'message' => 'Color palette not found!'
            ], 404);
        }

        $palette = $colorPalettes[$validated['palette_name']];

        // Set as active palette
        SystemSetting::set('active_color_palette', $validated['palette_name'], 'string', 'colors');

        // Save individual color settings
        foreach ($palette as $key => $value) {
            if ($key !== 'name') {
                SystemSetting::set("color_{$key}", $value, 'string', 'colors');
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Color palette applied successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Color palette applied successfully!');
    }

    public function deleteColorPalette(Request $request)
    {
        $validated = $request->validate([
            'palette_name' => 'required|string'
        ]);

        $colorPalettes = SystemSetting::get('color_palettes', []);

        if (isset($colorPalettes[$validated['palette_name']])) {
            unset($colorPalettes[$validated['palette_name']]);
            SystemSetting::set('color_palettes', $colorPalettes, 'json', 'colors');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Color palette deleted successfully!',
                'palettes' => $colorPalettes
            ]);
        }

        return redirect()->back()->with('success', 'Color palette deleted successfully!');
    }
}

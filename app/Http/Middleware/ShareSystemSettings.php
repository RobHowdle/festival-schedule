<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShareSystemSettings
{
    public function handle(Request $request, Closure $next)
    {
        // Share system settings with all Inertia pages
        Inertia::share([
            'systemSettings' => function () {
                return [
                    'background_type' => SystemSetting::get('background_type', 'color'),
                    'background_color' => SystemSetting::get('background_color', '#1F2937'),
                    'background_image' => SystemSetting::get('background_image'),
                    'active_color_palette' => SystemSetting::get('active_color_palette', 'default'),
                    'color_primary' => SystemSetting::get('color_primary', '#8B5CF6'),
                    'color_secondary' => SystemSetting::get('color_secondary', '#06B6D4'),
                    'color_accent' => SystemSetting::get('color_accent', '#F59E0B'),
                    'color_background' => SystemSetting::get('color_background', '#1F2937'),
                    'color_text' => SystemSetting::get('color_text', '#FFFFFF'),
                    'color_textSecondary' => SystemSetting::get('color_textSecondary', '#9CA3AF'),
                ];
            }
        ]);

        return $next($request);
    }
}

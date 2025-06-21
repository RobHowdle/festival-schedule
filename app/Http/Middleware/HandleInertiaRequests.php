<?php

namespace App\Http\Middleware;

use DB;
use Inertia\Middleware;
use Illuminate\Http\Request;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? $request->user()->only('id', 'name', 'email', 'is_admin', 'is_dev') : null,
            ],
            'errors' => fn() => $request->session()->get('errors')
                ? $request->session()->get('errors')->getBag('default')->getMessages()
                : (object) [],
            'status' => fn() => $request->session()->get('status'),

            'flash' => [
                'message' => fn() => $request->session()->get('message'),
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
            ],
            'systemSettings' => function () {
                return DB::table('system_settings')
                    ->whereIn('key', [
                        'color_primary',
                        'color_secondary',
                        'color_accent',
                        'color_background',
                        'color_text',
                        'color_textSecondary'
                    ])
                    ->pluck('value', 'key')
                    ->toArray();
            }
        ]);
    }
}

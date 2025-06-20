<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

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
        return array_merge(parent::share($request), [ // Use array_merge to ensure parent::share() props are included
            'auth' => [
                'user' => $request->user() ? $request->user()->only('id', 'name', 'email') : null, // Good practice to limit user data
            ],
            // ADD THESE LINES FOR ERROR AND STATUS SHARING
            'errors' => fn () => $request->session()->get('errors')
                                    ? $request->session()->get('errors')->getBag('default')->getMessages()
                                    : (object) [], // Pass an empty object if no errors
            'status' => fn () => $request->session()->get('status'), // For general success/info messages
        ]);
    }
}

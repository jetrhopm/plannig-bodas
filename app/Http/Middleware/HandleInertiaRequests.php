<?php

namespace App\Http\Middleware;

use App\Models\Wedding;
use App\Support\WeddingNavigation;
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
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'notifications' => [
                'unreadCount' => fn () => $request->user()?->unreadNotifications()->count() ?? 0,
            ],
            'appBasePath' => rtrim($request->getBaseUrl(), '/'),
            'weddingContext' => function () use ($request) {
                $user = $request->user();
                $wedding = $request->route('wedding');

                return $user && $wedding instanceof Wedding
                    ? WeddingNavigation::for($user, $wedding)
                    : null;
            },
        ];
    }
}

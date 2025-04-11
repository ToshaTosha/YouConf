<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {

        return array_merge(parent::share($request), [
            'unreadNotificationsCount' => fn() => Auth::check()
                ? Auth::user()->unreadNotifications()->count()
                : 0,
            'notifications' => fn() => Auth::check()
                ? Auth::user()->notifications()->limit(5)->get()
                : [],
            'user_data' => fn() => Auth::user(),
            'role' => function () {
                $user = Auth::user();
                return $user && method_exists($user, 'getRoleNames')
                    ? User::findOrFail(Auth::user()->id)->getRoleNames()->first()
                    : null;
            },
        ]);
    }
}
// 'role' => fn() => User::findOrFail(Auth::user()->id)->getRoleNames()->first(),
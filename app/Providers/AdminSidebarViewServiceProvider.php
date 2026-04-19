<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminSidebarViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('restaurant.layout.navbar', function ($view) {
            $user = Auth::guard('admin')->user();
            $imageUrl = null;

            if ($user) {
                $cacheKey = 'admin_photo_' . $user->id;
                $imageUrl = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($user) {
                    return DB::table('admin__photos')->where('admin_id', $user->id)->first();
                });
            }
            // $imageUrl = DB::table('admin__photos')->where('admin_id', $user->id)->first();
            $image = $imageUrl ? asset('storage/' . $imageUrl->photo_path) : null;

            $view->with(['adminImage' => $image,
                'adminUser' => $user,
            ]);
        });
    }
}

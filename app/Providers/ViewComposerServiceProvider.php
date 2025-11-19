<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('admin.app', function ($view) {
            $superAdmin = null;
            if (Auth::guard('super_admin')->check()) {
        
                $id = Auth::guard('super_admin')->id();
                $cacheKey = "super_admin:{$id}";

     
                $ttlMinutes = 30;

     
                $superAdmin = Cache::remember($cacheKey, now()->addMinutes($ttlMinutes), function () use ($id) {
                    return DB::table('super_admins')->where('id', $id)->first();
                });
            }

            $view->with('superAdmin', $superAdmin);
        });
   
    }
}

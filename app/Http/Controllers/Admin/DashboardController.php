<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\RestaurantSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ensure we're using the correct guard for admin authentication
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            abort(403, 'Admin not authenticated.');
        }
       
        $restaurants = $admin->restaurants()->with('staff')->orderByDesc('created_at')->get();

        $currentRestaurant = null;
        if ($restaurants->isNotEmpty()) {
            $currentRestaurant = $restaurants->firstWhere('id', $request->old('restaurant_id'))
                ?: $restaurants->first();
        }

        $totalStaff = $restaurants->sum(function ($restaurant) {
            return $restaurant->staff->count();
        });

        // Preload schedules for current restaurant for the schedule form
        $schedule = collect();
        if ($currentRestaurant) {
            // Use relationship to load schedules if it's defined on the Restaurant model
            $schedule = $currentRestaurant->schedules()->get();
        }

        return view('restaurant.dashboard', [
            'admin' => $admin,
            'restaurants' => $restaurants,
            'currentRestaurant' => $currentRestaurant,
            'totalStaff' => $totalStaff,
            'schedules' => $schedule,
        ]);
    }
}

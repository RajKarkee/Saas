<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $adminId = $request->session()->get('admin_id');

        if (!$adminId) {
            abort(403, 'Admin session missing.');
        }

        $admin = Admin::with(['restaurants.staff'])->findOrFail($adminId);
        $restaurants = $admin->restaurants()->with('staff')->orderByDesc('created_at')->get();

        $currentRestaurant = null;
        if ($restaurants->isNotEmpty()) {
            $currentRestaurant = $restaurants->firstWhere('id', $request->old('restaurant_id'))
                ?: $restaurants->first();
        }

        $totalStaff = $restaurants->sum(function ($restaurant) {
            return $restaurant->staff->count();
        });

        return view('restaurant.dashboard', [
            'admin' => $admin,
            'restaurants' => $restaurants,
            'currentRestaurant' => $currentRestaurant,
            'totalStaff' => $totalStaff,
        ]);
    }
}

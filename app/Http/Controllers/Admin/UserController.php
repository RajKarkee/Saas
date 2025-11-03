<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $adminId = $request->session()->get('admin_id');
        if (!$adminId) {
            abort(403, 'Admin session missing.');
        }

        $restaurant = Restaurant::where('owner_id', $adminId)->first();
        if (!$restaurant) {
            abort(403, 'Restaurant not found.');
        }

        $users = User::where('restaurant_id', $restaurant->id)->get();

        return view('restaurant.user.index', compact('users'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
    
        $restaurant = Restaurant::where('owner_id', Auth::id())->first();
        if (!$restaurant) {
            abort(403, 'Restaurant not found.');
        }

        $users = User::where('restaurant_id', $restaurant->id)->get();

        return view('restaurant.user.index', compact('users'));
    }
}

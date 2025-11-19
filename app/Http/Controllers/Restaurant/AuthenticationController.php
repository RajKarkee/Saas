<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {

        abort(404);
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::guard('staff')->attempt($credentials)) {

      
        $staff = Auth::guard('staff')->user();


        $restaurant = \App\Models\Restaurant::find($staff->restaurant_id);

    
        $routeMatch = match ($staff->role) {
            0 => route('restaurant.staff.dashboard'), 
            1 => route('restaurant.staff.index'),     
            2 => route('restaurant.delivery.index'),  
            default => route('restaurant.login'),
        };

        return redirect($routeMatch)->with([
            'message' => 'Login successful',
            'restaurant' => $restaurant,
            'staff' => $staff,
        ]);
    }


    return redirect()->route('restaurant.login')->with('error', 'Invalid email or password.');
}


    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();

        return redirect()->route('restaurant.login')->with('message', 'Logged out successfully');
    }

    public function logoutall(Request $request)
    {
        Auth::guard('staff')->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out from all devices successfully',
        ], 200);
    }
}

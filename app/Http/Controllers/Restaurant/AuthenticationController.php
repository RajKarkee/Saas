<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;


class AuthenticationController extends Controller
{
  public function register(Request $request){
        // Registration disabled - this endpoint intentionally returns 404
        abort(404);
    }
    public function login(Request $request){
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $staff = Staff::where('email', $request->email)->first();
    if (!$staff || !Hash::check($request->password, $staff->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password'
        ], 401);
    }
    $token = $staff->createToken('staff_token', ['staff'])->plainTextToken;
    $request->session()->put('staff_token', $token);
    $request->session()->put('staff_id', $staff->id);
    $request->session()->regenerate();
    $restaurant = Restaurant::find($staff->restaurant_id);
$routeMatch = match($staff->role){
    0 => route('restaurant.staff.dashboard'), // Manager
    1 => route('restaurant.staff.orders'),  // Chef1
    2 => route('restaurant.delivery.index'), // Waiter
};
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'restaurant' => $restaurant,
                'staff' => $staff,
                'token' => $token,
            ],
            'redirect' => $routeMatch,
        ], 200);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }
    public function logoutall(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logged out from all devices successfully'
        ], 200);
    }
}

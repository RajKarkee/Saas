<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Restaurant\KitchenLoginRequest;
use App\Http\Requests\Restaurant\StaffLoginRequest;
use App\Http\Resources\Api\RestaurantResource;
use App\Http\Resources\Restaurant\StaffAuthResource;
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

    public function login(StaffLoginRequest $request)
{
    $credentials = $request->validated();

    if (Auth::guard('staff')->attempt($credentials)) {


        $staff = Auth::guard('staff')->user();


        $restaurant = \App\Models\Restaurant::find($staff->restaurant_id);


        $routeMatch = match ($staff->role) {
            0 => route('restaurant.kitchen.index'),
            1 => route('restaurant.staff.index'),
            2 => route('restaurant.delivery.index'),
            default => route('restaurant.login'),
        };

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'restaurant' => $restaurant ? new RestaurantResource($restaurant) : null,
                'staff' => new StaffAuthResource($staff),
            ],
            'redirect' => $routeMatch,
        ], 200);
    }


    return response()->json([
        'success' => false,
        'message' => 'Invalid email or password.',
    ], 401);
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
    public function showKitchenLogin()
    {
        return view('kitchen.login');
    }

    public function kitchenLogin(KitchenLoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::guard('staff')->attempt($credentials)) {
            $staff = Auth::guard('staff')->user();
            if ($staff->role != 0) {
                Auth::guard('staff')->logout();
                return redirect()->back()->withErrors(['error' => 'Unauthorized access for kitchen staff only.']);
            }

            return redirect()->route('restaurant.kitchen.index');
        }

        return redirect()->back()->withErrors(['error' => 'Invalid email or password.']);
    }
    public function kitchenLogout(Request $request){
        Auth::guard('staff')->logout();
        return redirect()->route('landingPage.home')->with('message','Logged out successfully');
    }
}

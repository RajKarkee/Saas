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

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'restaurant' => $restaurant,
                'staff' => $staff,
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
    public function kitchenLogin(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'email'=>'required|email',
                'password'=>'required|string',
            ]);
            $credentials = $request->only('email', 'password');
            if(Auth::guard('staff')->attempt($credentials)){
                $staff = Auth::guard('staff')->user();
                if($staff->role !=0){
                    Auth::guard('staff')->logout();
                    return redirect()->back()->withErrors(['error'=>'Unauthorized access for kitchen staff only.']);
                }
                return redirect()->route('restaurant.kitchen.index');
            }
            else{
                return redirect()->back()->withErrors(['error'=>'Invalid email or password.']);
            }
        }
        return view('kitchen.login');
    }
    public function kitchenLogout(Request $request){
        Auth::guard('staff')->logout();
        return redirect()->route('landingPage.home')->with('message','Logged out successfully');
    }
}

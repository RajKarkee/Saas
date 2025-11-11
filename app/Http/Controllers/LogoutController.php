<?php

namespace App\Http\Controllers;


use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\SuperAdmin;
use App\Models\Admin;
use App\Models\Staff;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        // Define all guards + their token/session keys
        $guards = [
            'superadmin' => [
                
                'model' => SuperAdmin::class,
                'redirect' => route('logout.page')
            ],
            'admin' => [
              
                'model' => Admin::class,
                'redirect' => route('logout.page')
            ],
            'staff' => [
                
                'model' => Staff::class,
                'redirect' => route('logout.page')
            ],
        ];

        foreach ($guards as $guard => $config) {
       if(Auth::guard($guard)->check()){
        Auth::guard($guard)->logout();
        return redirect()->route('logout.page')->with('message', 'Logged out successfully .');
       }

        }

        return response()->json([
            'success' => false,
            'message' => 'No active session found.'
        ], 401);
    }
    public function logoutPage(Request $request){
        // Pass message (if present) to the view so the template can render it server-side
        $message = $request->query('message', null);
        return view('logout', ['message' => $message]);

    }

    }

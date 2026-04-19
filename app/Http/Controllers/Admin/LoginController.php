<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VerifyAdminLoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(){
        return view('restaurant.auth');
    }
    public function verify(VerifyAdminLoginRequest $request)
    {
        $credentials = $request->validated();
        if(Auth::guard('admin')->attempt($credentials)){
            return redirect()->route('admin.dashboard');
        }else{
            return redirect()->back()->withErrors(['email' => 'Invalid login details'])->withInput();
        }




    // $admin = Admin::where('email', $credentials['email'])->first();

    // if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid login details'
    //         ], 401);
    //     }

    //     $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;

    //     $request->session()->put('admin_token', $token);
    //     $request->session()->put('admin_id', $admin->id);
    //     $request->session()->regenerate();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Login successful',
    //         'token'=>$token,
    //         'redirect' => route('admin.dashboard'),
    //     ], 200);

    }
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('landingPage.home');
    }
}

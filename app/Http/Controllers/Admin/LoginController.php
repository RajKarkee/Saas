<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(){
        return view('restaurant.auth');
    }
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login details',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $validator->validated();

    $admin = Admin::where('email', $credentials['email'])->first();

    if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login details'
            ], 401);
        }

        $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;

        $request->session()->put('admin_token', $token);
        $request->session()->put('admin_id', $admin->id);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token'=>$token,
            'redirect' => route('admin.dashboard'),
        ], 200);
  
    }
}

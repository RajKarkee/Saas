<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
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
    $credentials = $request->only('email', 'password');
    $remember = $request->boolean('remember');
    if (!auth()->attempt($credentials, $remember)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid login details'
        ], 401);

    }
    $user = auth()->user();
    // If user is admin (role 1) redirect to dashboard
    $dashboardUrl = route('dashboard');

    if ($request->wantsJson() || $request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'redirect' => $dashboardUrl,
            'data' => [ 'user' => $user ],
        ], 200);
    }

    return redirect($dashboardUrl);
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

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
                'token_session' => 'superadmin_token',
                'model' => SuperAdmin::class,
                'redirect' => route('logout.page')
            ],
            'admin' => [
                'token_session' => 'admin_token',
                'model' => Admin::class,
                'redirect' => route('logout.page')
            ],
            'staff' => [
                'token_session' => 'staff_token',
                'model' => Staff::class,
                'redirect' => route('logout.page')
            ],
        ];

        foreach ($guards as $guard => $config) {
            $plainToken = $request->session()->get($config['token_session']);

            if ($plainToken) {
                // Resolve Sanctum token
                $accessToken = PersonalAccessToken::findToken($plainToken);

                // Validate token ownership
                if ($accessToken && $accessToken->tokenable_type === $config['model']) {
                    // Delete the token (logout this device/session)
                    $accessToken->delete();

                    // Clear the session keys
                    Session::forget([
                        $config['token_session'],
                        "{$guard}_id",
                    ]);

                    Session::invalidate();
                    Session::regenerateToken();

                    $message = ucfirst($guard) . ' logged out successfully.';
                    
                    // return response()->json([
                    //     'success' => true,
                    //     'message' => $message,
                    //     'redirect' => route('logout.page', ['message' => $message])
                    // ]);
                    return redirect()->route('logout.page', ['message' => $message]);
                }
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

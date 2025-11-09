<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Staff;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;

class SanctumStaff
{
    protected array $defaultAbilities = ['ability_name'];

    public function handle(Request $request, Closure $next, ...$abilities): Response
    {
        // Must have a session-stored plain text token (id|token)
        $plainToken = $request->session()->get('staff_token');
        if (!$plainToken) {
            abort(404, 'Page not found');
        }

        // Resolve the token model safely (handles id|token parsing and hashing)
        $accessToken = PersonalAccessToken::findToken($plainToken);
        if (!$accessToken || $accessToken->tokenable_type !== Staff::class) {
            abort(404, 'Page not found');
        }

        $staff = $accessToken->tokenable;
        
        // Verify the session staff_id matches the token's staff
        $sessionStaffId = $request->session()->get('staff_id');
        if ($sessionStaffId != $staff->id) {
            // Session mismatch - clear session and deny access
            $request->session()->flush();
            abort(403, 'Session expired. Please login again.');
        }

        // Optional ability check using provided abilities or defaults
        $required = !empty($abilities) ? $abilities : $accessToken->abilities;
        foreach ($required as $ability) {
            if (!$accessToken->can($ability)) {
                abort(403, 'Forbidden - You do not have access to this section.');
            }
        }

        // All good, continue
        return $next($request);
    }
}
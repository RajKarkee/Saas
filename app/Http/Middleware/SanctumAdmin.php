<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Admin;

class SanctumAdmin
{
    /**
     * Default required abilities for admin tokens.
     */
    protected array $defaultAbilities = ['admin'];

    /**
     * Handle an incoming request.
     *
     * Usage on route/group: ->middleware('admin.sanctum:admin')
     */
    public function handle(Request $request, Closure $next, ...$abilities): Response
    {
        // Get the plain-text token stored in session during admin login
        $plainToken = $request->session()->get('admin_token');
        if (!$plainToken) {
            abort(404, 'Page not found');
        }

        // Resolve the token safely and ensure it belongs to an Admin
        $accessToken = PersonalAccessToken::findToken($plainToken);
        if (!$accessToken || $accessToken->tokenable_type !== Admin::class) {
            abort(404, 'Page not found');
        }

        // Determine required abilities (from middleware args or defaults)
        $required = !empty($abilities) ? $abilities : $this->defaultAbilities;
        foreach ($required as $ability) {
            if (!$accessToken->can($ability)) {
                abort(403, 'Forbidden');
            }
        }

        return $next($request);
    }
}

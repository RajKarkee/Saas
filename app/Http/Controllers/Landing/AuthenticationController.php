<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Landing\CheckDomainRequest;
use App\Http\Requests\Landing\CheckEmailRequest;
use App\Http\Requests\Landing\CheckUniqueRequest;
use App\Http\Requests\Landing\RegisterAdminRestaurantRequest;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\RestaurantSetting;

class AuthenticationController extends Controller
{
    public function checkUnique(CheckUniqueRequest $request){
        $validated = $request->validated();
        $type = $validated['type'];
        $value = $validated['value'];

        switch($type){
            case 'admin_email':
                 $exists = DB::table('admins')->where('email', $value)->exists();
                break;
            case 'restaurant_domain':
                $exists = DB::table('restaurants')->where('domain', $value)->exists();
                break;
            case 'restaurant_email':
                $exists = DB::table('restaurant_settings')->where('email', $value)->exists();
                break;

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid type parameter',
                ], 400);
        }
        return response()->json([
            'success' => true,
            'unique' => !$exists,
            'message' => $exists ? 'Value is already taken' : 'Value is available',
        ], 200);
    }


    public function checkEmail(CheckEmailRequest $request): JsonResponse
    {
        $email = $request->validated()['email'];


    $exists = Admin::where('email', $email)->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Email is already taken',
            ], 200);
        }

        return response()->json([
            'success' => true,
            'unique' => !$exists,
            'message' => $exists ? 'Email is already taken' : 'Email is available',
        ], 200);
    }

    /**
     * Final submit: create Admin and Restaurant in one go.
     * Expects admin_* and restaurant_* fields from the two-step form.
     */
    public function register(RegisterAdminRestaurantRequest $request): JsonResponse
    {
        $data = $request->validated()['data'];

        $adminData = $data['admin'];
        $restaurantData = $data['restaurant'];

        [$admin, $restaurant] = DB::transaction(function () use ($adminData, $restaurantData) {
            $admin = new Admin();
            $admin->name = $adminData['username'];
            $admin->email = $adminData['email'];
            $admin->password = Hash::make($adminData['password']);
            $admin->status = 'active';
            $admin->save();

            $restaurant = new Restaurant();
            $restaurant->owner_id = $admin->id;
            $restaurant->domain = $restaurantData['domain'] ?? null;
            $restaurant->subdomain = $restaurantData['subdomain'] ?? null;
            $restaurant->name = $restaurantData['name'];
            $restaurant->status = 'pending';
            $restaurant->save();

            if (!empty($restaurantData['email'])) {
                $restaurantSettings = new RestaurantSetting();
                $restaurantSettings->restaurant_id = $restaurant->id;
                $restaurantSettings->email = $restaurantData['email'];
                $restaurantSettings->save();
            }

            return [$admin, $restaurant];
        });

        return response()->json([
            'success' => true,
            'message' => 'Your restaurant is pending approval but you can login as Admin',
            'admin_id' => $admin->id,
            'restaurant_id' => $restaurant->id,
         'redirect' => route('landingPage.login'),
        ], 201);
    }


    public function checkDomain(CheckDomainRequest $request): JsonResponse
    {
        $domain = strtolower(trim($request->validated()['domain']));

        // Basic normalization: strip protocol and path
        $domain = preg_replace('/^https?:\/\//i', '', $domain);
        $domain = explode('/', $domain)[0];

        $exists = Restaurant::where('domain', $domain)->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'unique' => false,
                'message' => 'This domain is already in use',
            ]);
        }

        return response()->json([
            'success' => true,
            'unique' => true,
            'message' => 'Domain is available',
        ]);
    }
}

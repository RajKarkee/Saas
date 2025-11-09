<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\RestaurantSetting;

class AuthenticationController extends Controller
{
    public function checkUnique(Request $request){
        $type=$request->input('type');
        $value=$request->input('value');
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


    public function checkEmail(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email format',
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = $validator->validated()['email'];

    
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
    public function register(Request $request): JsonResponse
    {
        // Expect nested structure { data: { admin: {...}, restaurant: {...} } }
        $data = $request->input('data');
        if (!$data || !isset($data['admin']) || !isset($data['restaurant'])) {
            return response()->json([
                'success' => false,
                'message' => 'Incomplete data provided',
            ], 400);
        }

        $adminData = $data['admin'];
        $restaurantData = $data['restaurant'];

        // Front-end now sends password_confirmation; enforce confirmed rule
        $adminValidator = Validator::make($adminData, [
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($adminValidator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid admin data',
                'errors' => $adminValidator->errors(),
            ], 422);
        }

        $restaurantValidator = Validator::make($restaurantData, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:restaurant_settings,email',
            'domain' => 'nullable|string|max:255|unique:restaurants,domain', // now optional
            'subdomain' => 'nullable|string|max:255|unique:restaurants,subdomain',
        ]);
        if ($restaurantValidator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid restaurant data',
                'errors' => $restaurantValidator->errors(),
            ], 422);
        }

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
                        $restaurant_settings = new RestaurantSetting();
                        $restaurant_settings->restaurant_id = $restaurant->id;
                        $restaurant_settings->email = $restaurantData['email'];
                        $restaurant_settings->save();
                }

        return response()->json([
            'success' => true,
            'message' => 'Your restaurant is pending approval but you can login as Admin',
            'admin_id' => $admin->id,
            'restaurant_id' => $restaurant->id,
         'redirect' => route('landingPage.login'),
        ], 201);
    }


    public function checkDomain(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid domain',
                'errors' => $validator->errors(),
            ], 422);
        }

        $domain = strtolower(trim($validator->validated()['domain']));

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

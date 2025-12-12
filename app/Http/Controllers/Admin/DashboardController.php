<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\RestaurantSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ensure we're using the correct guard for admin authentication
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            abort(403, 'Admin not authenticated.');
        }
       
        $restaurants = $admin->restaurants()->with('staff')->orderByDesc('created_at')->get();

        $currentRestaurant = null;
        if ($restaurants->isNotEmpty()) {
            $currentRestaurant = $restaurants->firstWhere('id', $request->old('restaurant_id'))
                ?: $restaurants->first();
        }
        $restaurantId=DB::table('restaurants')->where('owner_id',$admin->id)->first();
        $totalStaff = $restaurants->sum(function ($restaurant) {
            return $restaurant->staff->count();
        });


        // Preload schedules for current restaurant for the schedule form
        // $schedule = collect();
        // if ($currentRestaurant) {
        //     // Use relationship to load schedules if it's defined on the Restaurant model
        //     $schedule = $currentRestaurant->schedules()->get();
        // }
        $schedule= DB::table('restaurant_schedules_tables')
        ->where('restaurant_id', $restaurantId->id)->get();

        return view('restaurant.dashboard', [
            'admin' => $admin,
            'restaurants' => $restaurants,
            'currentRestaurant' => $currentRestaurant,
            'totalStaff' => $totalStaff,
            'schedules' => $schedule,
        ]);

    }
    public function profile(Request $request){
        if($request->isMethod('put')){
           $admin = Auth::guard('admin')->user();
           $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:admins,email,'.$admin->id,
            'password'=>'nullable|string|min:8',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
           ]);
           if($request->filled('password')){
            $admin->password = Hash::make($request->password);
           }
           $admin->name = $request->name;
           $admin->email = $request->email;
           $admin->save();
           if($request->hasFile('image')){
            $photoPath = $request->file('image')->store('admin_photos', 'public');
            DB::table('admin__photos')->updateOrInsert(
                ['admin_id' => $admin->id],
                ['photo_path' => $photoPath]
              
            );
            Cache::forget('admin_photo_' . $admin->id);
           }
              return redirect()->route('admin.dashboard')->with('success', 'Profile updated successfully.');
        }
        else{
            $admin = Auth::guard('admin')->user();
            $imageUrl =DB::table('admin__photos')->where('admin_id', $admin->id)->first();
            $adminImage = $imageUrl ? asset('storage/' . $imageUrl->photo_path) : null;
            // view()->share('adminUser', $admin);
            // view()->share('adminImage', $adminImage);
            return view('restaurant.layout.profile',compact('admin','adminImage'));
        }
    }
}

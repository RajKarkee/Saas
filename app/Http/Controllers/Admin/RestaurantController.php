<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\RestaurantSetting;
use Illuminate\Support\Facades\Schema;
use App\Models\Staff;
use App\Models\Admin;
use App\Models\Staff_photo;
use Illuminate\Support\Facades\Hash;

class RestaurantController extends Controller
{
    /**
     * Show the edit form for the current admin's single restaurant.
     */
    public function edit(Request $request)
    {
        $adminId = $request->session()->get('admin_id');
        if (!$adminId) {
            abort(403, 'Admin session missing.');
        }

       
        $restaurant = Restaurant::where('owner_id', $adminId)->first();
        if (!$restaurant) {
            $restaurant = new Restaurant([
                'name' => '',
                'domain' => '',
                'subdomain' => '',
                'status' => 'inactive',
                'logo' => null,
            ]);
        }

        return view('restaurant.restaurant', [
            'restaurant' => $restaurant,
        ]);
    }

    /**
     * Update the restaurant details. Logo is present in UI but intentionally ignored here.
     */
    public function update(Request $request)
    {
        $adminId = $request->session()->get('admin_id');
        if (!$adminId) {
            abort(403, 'Admin session missing.');
        }

        $restaurant = Restaurant::where('owner_id', $adminId)->first();

    
        $domainRule = ['required', 'string', 'max:255'];
        if ($restaurant) {
            $domainRule[] = Rule::unique('restaurants', 'domain')->ignore($restaurant->id);
        } else {
            $domainRule[] = Rule::unique('restaurants', 'domain');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domain' => $domainRule,
            'subdomain' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            // 'logo' field deliberately not processed server-side per requirement
        ]);

        if (!$restaurant) {
            // Create a new restaurant for this admin
            $restaurant = new Restaurant();
            $restaurant->owner_id = $adminId;
        }

    // Upsert fields (excluding logo handling)
        $restaurant->name = $validated['name'];
        $restaurant->domain = $validated['domain'];
        $restaurant->subdomain = $validated['subdomain'] ?? null;
        $restaurant->status = $validated['status'];
        $restaurant->save();

        return redirect()->back()->with('success', 'Restaurant details saved successfully.');
    }
    public function settings(Request $request)
    {
        $adminId = $request->session()->get('admin_id');
        if (!$adminId) {
            abort(403, 'Admin session missing.');
        }

        // Ensure the admin has a restaurant
        $restaurant = Restaurant::where('owner_id', $adminId)->firstOrFail();

        
        if ($request->isMethod('get')) {
            $setting = $restaurant->settings()->first();
            return view('restaurant.restaurantSetting', [
                'restaurant' => $restaurant,
                'setting' => $setting,
            ]);
        }

        
        $validated = $request->validate([
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'map_url' => ['nullable', 'url', 'max:2048'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $setting = $restaurant->settings()->first();
        if (! $setting) {
            $setting = new RestaurantSetting();
            $setting->restaurant_id = $restaurant->id;
        }

        $setting->address = $validated['address'] ?? null;
        $setting->phone = $validated['phone'] ?? null;

        // Only set email if the column exists (migration may be missing the column)
        if (Schema::hasColumn('restaurant_settings', 'email')) {
            $setting->email = $validated['email'] ?? null;
        }

        $setting->map_url = $validated['map_url'] ?? null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('restaurant_logos', 'public');
            $setting->logo = $logoPath;
        }

        $setting->save();

        return redirect()->back()->with('success', 'Restaurant settings saved successfully.');
    }
    public function staffIndex(Request $request)
    {
        $adminId = $request->session()->get('admin_id');
        if (!$adminId) {
            abort(403, 'Admin session missing.');
        }

        $restaurant = Restaurant::where('owner_id', $adminId)->firstOrFail();

        $staff = $restaurant->staff()->get();

        return view('restaurant.staff.staff',  compact('staff')
        );
    }
    public function staffCreate(Request $request)
    {
        return view('restaurant.staff.create');
    }
public function staffStore(Request $request)
    {
        $adminId = $request->session()->get('admin_id');
        if (!$adminId) {
            abort(403, 'Admin session missing.');
        }

        $restaurant = Restaurant::where('owner_id', $adminId)->firstOrFail();

        $roleMap=[
            'Manager'=>0,
            'Staff'=>1,
            'Delivery Person'=>2,
        ];
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('staff', 'email')],
            'password' => ['string', 'min:8', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'role' => ['required', 'string', 'in:Manager,Delivery Person,Staff'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);
     
        $validated['role']=$roleMap[$validated['role']];

        $staff = $restaurant->staff()->create([
            'restaurant_id' => $restaurant->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);
        if ($validated['photo'] ?? false) {
            $imagePath = $validated['photo']->store('staff_photos', 'public');
            Staff_photo::create([
                'staff_id' => $staff->id,
                'photo_url' => $imagePath,
            ]);
        }

        return redirect()->route('admin.restaurant.staff.index')->with('success', 'Staff member created successfully.');
    }
    public function staffEdit(Request $request, $staffId)
    {
        $adminId = $request->session()->get('admin_id');
        if (!$adminId) {
            abort(403, 'Admin session missing.');
        }

        $restaurant = Restaurant::where('owner_id', $adminId)->firstOrFail();

        $staff = $restaurant->staff()->findOrFail($staffId);
        $staff_photo = Staff_photo::where('staff_id', $staff->id)->first();

         if ($staff_photo) {
            $staff->photo_url = $staff_photo->photo_url;
        } else {
            $staff->photo_url = null;
        }
      

        return view('restaurant.staff.edit', compact('staff'));
    }
    public function staffUpdate(Request $request, $staffId)
    {
        $adminId = $request->session()->get('admin_id');
        if (!$adminId) {
            abort(403, 'Admin session missing.');
        }

        $restaurant = Restaurant::where('owner_id', $adminId)->firstOrFail();

        $staff = $restaurant->staff()->findOrFail($staffId);

        $roleMap=[
            'Manager'=>0,
            'Staff'=>1,
            'Delivery Person'=>2,
        ];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('staff', 'email')->ignore($staff->id)],
            'password' => ['nullable', 'string', 'min:8', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'role' => ['required', 'string', 'in:Manager,Delivery Person,Staff'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['role']=$roleMap[$validated['role']];

        $staff->name = $validated['name'];
        $staff->email = $validated['email'];
        if (!empty($validated['password'])) {
            $staff->password = Hash::make($validated['password']);
        }
        $staff->phone = $validated['phone'] ?? null;
        $staff->role = $validated['role'];
        $staff->status = $validated['status'];
        $staff->save();

        if ($validated['photo'] ?? false) {
            $imagePath = $validated['photo']->store('staff_photos', 'public');
            $staffPhoto = Staff_photo::where('staff_id', $staff->id)->first();
            if ($staffPhoto) {
                $staffPhoto->photo_url = $imagePath;
                $staffPhoto->save();
            } else {
                Staff_photo::create([
                    'staff_id' => $staff->id,
                    'photo_url' => $imagePath,
                ]);
            }
        }

        return redirect()->route('admin.restaurant.staff.index')->with('success', 'Staff member updated successfully.');
    }
    public function staffDestroy(Request $request, $staffId)
    {
        $adminId = $request->session()->get('admin_id');
        if (!$adminId) {
            abort(403, 'Admin session missing.');
        }

        $restaurant = Restaurant::where('owner_id', $adminId)->firstOrFail();

        $staff = $restaurant->staff()->findOrFail($staffId);

        // Delete associated photo if exists
        Staff_photo::where('staff_id', $staff->id)->delete();

        $staff->delete();

        return redirect()->route('admin.restaurant.staff.index')->with('success', 'Staff member deleted successfully.');
    }
}

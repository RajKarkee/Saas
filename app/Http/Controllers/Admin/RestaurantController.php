<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        // Load the current admin's restaurant if it exists; otherwise provide a blank model
        $restaurant = Restaurant::where('owner_id', $adminId)->first();
        if (!$restaurant) {
            $restaurant = new Restaurant([
                'name' => '',
                'domain' => '',
                'subdomain' => '',
                'email' => '',
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

        // Build validation rules depending on whether this is a create or update
        $domainRule = ['required', 'string', 'max:255'];
        $emailRule = ['required', 'email', 'max:255'];
        if ($restaurant) {
            $domainRule[] = Rule::unique('restaurants', 'domain')->ignore($restaurant->id);
            $emailRule[] = Rule::unique('restaurants', 'email')->ignore($restaurant->id);
        } else {
            $domainRule[] = Rule::unique('restaurants', 'domain');
            $emailRule[] = Rule::unique('restaurants', 'email');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domain' => $domainRule,
            'subdomain' => ['nullable', 'string', 'max:255'],
            'email' => $emailRule,
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
        $restaurant->email = $validated['email'];
        $restaurant->status = $validated['status'];
        $restaurant->save();

        return redirect()->back()->with('success', 'Restaurant details saved successfully.');
    }
}

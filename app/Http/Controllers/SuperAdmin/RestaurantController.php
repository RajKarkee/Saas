<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\CreateStaffRequest;
use App\Http\Requests\SuperAdmin\RegisterRestaurantRequest;
use App\Http\Requests\SuperAdmin\SearchAdminRequest;
use App\Http\Requests\SuperAdmin\StoreRestaurantRequest;
use App\Http\Requests\SuperAdmin\UpdateRestaurantRequest;
use App\Http\Resources\SuperAdmin\AdminLookupResource;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\AdminRestaurant;
use App\Models\Staff;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class RestaurantController extends Controller
{
    /**
     * Public restaurant registration (signup page)
     */
    public function registerRestaurant(RegisterRestaurantRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Create restaurant
            $restaurant = Restaurant::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'domain' => $validated['domain'],
                'subdomain' => $validated['subdomain'] ?? null,
                'password' => Hash::make($validated['password']),
                'status' => 'pending', // Pending approval by super admin
            ]);

            DB::commit();

            return redirect()->route('landingPage.login')
                ->with('success', 'Restaurant registered successfully! Your account is pending approval. You will be notified via email once approved.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Registration failed. Please try again.')
                ->withInput();
        }
    }

    public function index()
    {
        if(request()->isMethod('get')){
            $restaurants = Restaurant::with('owner')->latest()->paginate(20);
            return view('admin.restaurant.index', compact('restaurants'));
        }



    }
    public function create()
    {
      $missingAdmins = DB::table('admin__restaurants as ar')
        ->leftJoin('restaurants as r', 'ar.admin_id', '=', 'r.owner_id')
        ->select('ar.admin_id', 'ar.restaurant_count', DB::raw('COUNT(r.id) as actual_count'))
        ->groupBy('ar.admin_id', 'ar.restaurant_count')
        ->havingRaw('actual_count < ar.restaurant_count')
        ->pluck('ar.admin_id');
        $admin=DB::table('admins')
        ->whereIn('id', $missingAdmins)
        ->get();

        return view('admin.restaurant.add',compact('admin'));
    }
    public function store(StoreRestaurantRequest $request)
    {
        $validated = $request->validated();

        $restaurant = new Restaurant();
        $restaurant->name = $validated['name'];
        $restaurant->domain = $validated['domain'];
        $restaurant->subdomain = $validated['subdomain'];
        $restaurant->owner_id = $validated['owner_id'];
        $restaurant->status = $validated['status'];
        $restaurant->save();

        return redirect()->route('super_admin.restaurant.index')->with('success', 'Restaurant added successfully.');
    }
    public function search(SearchAdminRequest $request){
        $query = trim((string) ($request->validated()['query'] ?? ''));

        $missingAdmins = DB::table('admin__restaurants as ar')
    ->leftJoin('restaurants as r', 'ar.admin_id', '=', 'r.owner_id')
    ->select('ar.admin_id', 'ar.restaurant_count', DB::raw('COUNT(r.id) as actual_count'))
    ->groupBy('ar.admin_id', 'ar.restaurant_count')
    ->havingRaw('actual_count < ar.restaurant_count')
    ->pluck('ar.admin_id');

        $admins = Admin::whereIn('id', $missingAdmins)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json(AdminLookupResource::collection($admins));
    }
    public function edit($id)
    {
        $restaurant = Restaurant::with('owner')->findOrFail($id);
           $missingAdmins = DB::table('admin__restaurants as ar')
        ->leftJoin('restaurants as r', 'ar.admin_id', '=', 'r.owner_id')
        ->select('ar.admin_id', 'ar.restaurant_count', DB::raw('COUNT(r.id) as actual_count'))
        ->groupBy('ar.admin_id', 'ar.restaurant_count')
        ->havingRaw('actual_count < ar.restaurant_count')
        ->pluck('ar.admin_id'); // only get admin_id list
        $admin=DB::table('admins')
        ->whereIn('id', $missingAdmins)
        ->get();


        return view('admin.restaurant.edit', compact('restaurant', 'admin'));
    }
    public function update(UpdateRestaurantRequest $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $validated = $request->validated();

        $restaurant->name = $validated['name'];
        $restaurant->domain = $validated['domain'];
        $restaurant->subdomain = $validated['subdomain'];
        $restaurant->owner_id = $validated['owner_id'];
        $restaurant->status = $validated['status'];
        $restaurant->save();

        return redirect()->route('super_admin.restaurant.index')->with('success', 'Restaurant updated successfully.');
    }
    public function pending()
    {
        $restaurants = Restaurant::with('owner')->where('status', 'pending')->latest()->paginate(20);
        return view('admin.restaurant.pendingindex', compact('restaurants'));
    }
    public function view($id)
    {
        $restaurant = Restaurant::with('owner','staff')->findOrFail($id);
        return view('admin.restaurant.staff.index', compact('restaurant'));
    }
    public function staffcreate(CreateStaffRequest $request)
    {
        $validated = $request->validated();

        $staff = new Staff();
        $staff->name = $validated['name'];
        $staff->email = $validated['email'];
        $staff->phone = $validated['phone'] ?? null;
        $staff->role = $validated['role'];
        $staff->password = Hash::make($validated['password']);
        $staff->save();

        return redirect()->back()->with('success', 'Staff added successfully.');
    }
}

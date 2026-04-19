<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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
    public function registerRestaurant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:restaurants,email',
            'domain' => 'required|string|max:255|unique:restaurants,domain',
            'subdomain' => 'nullable|string|max:255|unique:restaurants,subdomain',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create restaurant
            $restaurant = Restaurant::create([
                'name' => $request->name,
                'email' => $request->email,
                'domain' => $request->domain,
                'subdomain' => $request->subdomain,
                'password' => Hash::make($request->password),
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
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',

            'domain' => 'required|string|max:255|unique:restaurants,domain',
            'subdomain' => 'required|string|max:255|unique:restaurants,subdomain',
            'owner_id' => 'required|exists:admins,id',
            'status' => 'required|in:active,inactive,pending',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $restaurant = new Restaurant();
        $restaurant->name = $request->input('name');

        $restaurant->domain = $request->input('domain');
        $restaurant->subdomain = $request->input('subdomain');
        $restaurant->owner_id = $request->input('owner_id');
        $restaurant->status = $request->input('status');
        $restaurant->save();

        return redirect()->route('super_admin.restaurant.index')->with('success', 'Restaurant added successfully.');
    }
    public function search(Request $request){
        $query = trim((string) $request->input('query', ''));

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

    return response()->json($admins);
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
    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'domain' => ['required','string','max:255', Rule::unique('restaurants')->ignore($restaurant->id)],
            'subdomain' => ['required','string','max:255', Rule::unique('restaurants')->ignore($restaurant->id)],
            'owner_id' => 'required|exists:admins,id',
            'status' => 'required|in:active,inactive,pending',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $restaurant->name = $request->input('name');
        $restaurant->domain = $request->input('domain');
        $restaurant->subdomain = $request->input('subdomain');
        $restaurant->owner_id = $request->input('owner_id');
        $restaurant->status = $request->input('status');
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
    public function staffcreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required|string|max:225',
            'email'=> 'required|string|email|max:225|unique:staff',
            'phone'=> 'nullable|regex:/^\+977-9\d{9}$/',
            'role'=> 'required|integer',
            'password'=> 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $staff = new Staff();
        $staff->name = $request->input('name');
        $staff->email = $request->input('email');
        $staff->phone = $request->input('phone');
        $staff->role = $request->input('role');
        $staff->password = Hash::make($request->input('password'));
        $staff->save();

        return redirect()->back()->with('success', 'Staff added successfully.');
    }
}

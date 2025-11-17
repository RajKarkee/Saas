<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Adminphoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Restaurant;
use App\Models\AdminRestaurant;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.authentication.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');
        if(Auth::guard('super_admin')->attempt($credentials)){
         
            return redirect()->route('super_admin.index');
        }
        else{   
            return response()->json(['error' => 'Invalid Credentials'], 401);
        }
    //     $superAdmin = \App\Models\SuperAdmin::where('email', $request->email)->first();

    //     if (!$superAdmin || !Hash::check($request->password, $superAdmin->password)) {
    //         return response()->json(['message' => 'Invalid credentials'], 401);
    //     }

    //     // Create Sanctum token with abilities
    //     $token = $superAdmin->createToken('super-admin-token', ['superadmin'])->plainTextToken;

    // // Store the plain token in session for middleware verification
    // $request->session()->put('superadmin_token', $token);
    // $request->session()->put('superadmin_id', $superAdmin->id);
    // // Regenerate session ID to persist and mitigate fixation
    // $request->session()->regenerate();

    //     return response()->json([
    //         'message' => 'Login successful',
    //         'redirect' => route('super_admin.admins.index')
    //     ]);

    }

    public function logout(Request $request)
    {
        Auth::guard('super_admin')->logout();
        return redirect()->route('superadmin.login');
        // $token = $request->session()->get('superadmin_token');
        
        // if ($token) {
        //     $superAdmin = \App\Models\SuperAdmin::whereHas('tokens', function($q) use ($token) {
        //         $q->where('token', hash('sha256', $token));
        //     })->first();
            
        //     if ($superAdmin) {
        //         $superAdmin->tokens()->where('token', hash('sha256', $token))->delete();
        //     }
        // }

        // $request->session()->forget(['superadmin_token', 'superadmin_id']);
        // $request->session()->flush();

        // return redirect()->route('superadmin.login');
    }

    public function index(Request $request)
    {
       if($request->isMethod('post')){
        // Handle POST request logic here (e.g., form submission)
        return response()->json(['message' => 'POST request received']);
       }
       $admins = Admin::with('adminPhoto', 'adminRestaurant')->get();
       return view('admin.res_admin.index', compact('admins'));
      
    }
    public function create()
    {
        return view('admin.res_admin.add');
    }
    public function store(Request $request)
    {
        // Use a validator so we can return JSON errors for AJAX requests
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8',
            'status' => 'required|in:active,inactive,pending',
            'restaurant_count' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        DB::beginTransaction();
        try {
            $admin = new Admin();
            $admin->name = $validatedData['name'];
            $admin->email = $validatedData['email'];
            $admin->password = Hash::make($validatedData['password']);
            $admin->status = $validatedData['status'];
            $admin->save();

            if (!empty($validatedData['image']) && $request->hasFile('image')) {
                $path = $request->file('image')->store('admin/image', 'public');
                $adminPhoto = new Adminphoto();
                $adminPhoto->admin_id = $admin->id;
                $adminPhoto->photo_path = $path;
                $adminPhoto->save();
            }
            else{
                $adminPhoto = new Adminphoto();
                $adminPhoto->admin_id = $admin->id;
            
                $adminPhoto->save();
            }

            if (array_key_exists('restaurant_count', $validatedData)) {
                $adminRestaurant = new AdminRestaurant();
                $adminRestaurant->admin_id = $admin->id;
                $adminRestaurant->restaurant_count = $validatedData['restaurant_count'];
                $adminRestaurant->save();
            }

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'Admin created successfully.', 'redirect' => route('super_admin.admins.index')]);
            }

            return redirect()->route('super_admin.admins.index')->with('success', 'Admin created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // fallback
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Failed to create admin.'], 500);
            }
            return redirect()->back()->with('error', 'Failed to create admin.');
        }
    }
    public function edit($id)
    {
        $admin = Admin::with('adminPhoto', 'adminRestaurant')->findOrFail($id);
        return view('admin.res_admin.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required','string','email','max:255', Rule::unique('admins')->ignore($admin->id)],
            'password' => 'nullable|string|min:8',
            'status' => 'required|in:active,inactive,pending',
            'restaurant_count' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        DB::beginTransaction();
        try {
            $admin->name = $data['name'];
            $admin->email = $data['email'];
            if (!empty($data['password'])) {
                $admin->password = Hash::make($data['password']);
            }
            $admin->status = $data['status'];
            $admin->save();

            // handle image removal or replacement
            $adminPhoto = Adminphoto::firstOrNew(['admin_id' => $admin->id]);
            $oldPath = $adminPhoto->photo_path ?? null;

            if ($request->has('remove_image') && $request->input('remove_image') == '1') {
                // delete old file if exists
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $adminPhoto->photo_path = null;
                $adminPhoto->save();
            }

            if ($request->hasFile('image')) {
                // delete old file if replacing
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file('image')->store('admin/image', 'public');
                $adminPhoto->photo_path = $path;
                $adminPhoto->save();
            } else {
                // ensure there is a record if none exists
                if (!$adminPhoto->exists) {
                    $adminPhoto->save();
                }
            }

            if (array_key_exists('restaurant_count', $data)) {
                $adminRestaurant = AdminRestaurant::firstOrNew(['admin_id' => $admin->id]);
                $adminRestaurant->restaurant_count = $data['restaurant_count'];
                $adminRestaurant->save();
            }

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'Admin updated successfully.', 'redirect' => route('super_admin.admins.index')]);
            }

            return redirect()->route('super_admin.admins.index')->with('success', 'Admin updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Failed to update admin.'], 500);
            }
            return redirect()->back()->with('error', 'Failed to update admin.');
        }
    }
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);

        DB::beginTransaction();
        try {
            // Delete associated photo if exists
            $adminPhoto = Adminphoto::where('admin_id', $admin->id)->first();
            if ($adminPhoto) {
                if ($adminPhoto->photo_path && Storage::disk('public')->exists($adminPhoto->photo_path)) {
                    Storage::disk('public')->delete($adminPhoto->photo_path);
                }
                $adminPhoto->delete();
            }

            // Delete associated admin restaurant record if exists
            $adminRestaurant = AdminRestaurant::where('admin_id', $admin->id)->first();
            if ($adminRestaurant) {
                $adminRestaurant->delete();
            }

            $admin->delete();

            DB::commit();

            return redirect()->route('super_admin.admins.index')->with('success', 'Admin deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete admin.');
        }
    }
}

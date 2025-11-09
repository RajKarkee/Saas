<?php

namespace App\Http\Controllers\Restaurant\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Staff;
use App\Models\Restaurant;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
class DeliveryController extends Controller
{
   public function index(Request $request)
    {
        $staffId = $request->session()->get('staff_id');
        if (!$staffId) {
            abort(403, 'Staff session missing.');
        }
        // Use correlated subqueries to aggregate order_items per order (avoids GROUP BY issues)
        $orders = DB::table('orders')
            ->where('delivery_person_id', $staffId,)
            ->where('delivery_status', 'pending')
            ->leftJoin('users', 'users.id', '=', 'orders.customer_id')
            ->select(
                'orders.*',
                'users.name as customer_name',
                'users.email as customer_email',
                DB::raw('(SELECT COALESCE(SUM(total_price),0) FROM order_items WHERE order_items.order_id = orders.id) as order_total_price'),
            )
            ->get();

        // Return view (the Blade will use customer_name, order_quantity, order_total_price)
        return view('delivery.dashboard', compact('orders'));
    }

   
    public function setting(Request $request)
    {
        $staffId = $request->session()->get('staff_id');
        if (!$staffId) {
            abort(403, 'Staff session missing.');
        }

       $staff= DB::table('staff')->where('id', $staffId)->first();
       $restaurant = DB::table('restaurants')->where('id', $staff->restaurant_id)->first();
       $staffphoto=DB::table('staff_photos')->where('staff_id', $staff->id)->first();

        return view('delivery.profile', compact('staff', 'restaurant','staffphoto'));
      
}
public function startDelivery(Request $request, $id)
    {
        $staffId = $request->session()->get('staff_id');
        if (!$staffId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $order = Order::where('id', $id)
            ->where('delivery_person_id', $staffId)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found or not assigned to you'], 404);
        }

        if ($order->delivery_status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Order cannot be started'], 400);
        }

        $order->delivery_status = 'in_transit';
        $order->save();

        return response()->json(['success' => true, 'message' => 'Delivery started successfully'], 200);
    }
    public function ongoingDeliveries(Request $request)
    {
        $staffId = $request->session()->get('staff_id');
        if (!$staffId) {
            abort(403, 'Staff session missing.');
        }

        $orders = DB::table('orders')
            ->where('delivery_person_id', $staffId)
            ->whereIn('delivery_status', ['in_transit'])
            ->leftJoin('users', 'users.id', '=', 'orders.customer_id')
            ->select(
                'orders.*',
                'users.name as customer_name',
                'users.email as customer_email',
                DB::raw('(SELECT COALESCE(SUM(total_price),0) FROM order_items WHERE order_items.order_id = orders.id) as order_total_price'),
            )
            ->get();

        return view('delivery.deliverySection', compact('orders'));
    }
    public function profile(Request $request)
    {
        $staffId = $request->session()->get('staff_id');
        if (!$staffId) {
            abort(403, 'Staff session missing.');
        }

        $staff = DB::table('staff')->where('id', $staffId)->first();
        if (!$staff) {
            abort(404, 'Staff not found.');
        }

        $restaurant = DB::table('restaurants')->where('id', $staff->restaurant_id)->first();
        if (!$restaurant) {
            abort(404, 'Restaurant not found.');
        }

        // Accept POST or PUT for updates (form uses POST)
        if ($request->isMethod('post') || $request->isMethod('put')) {
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:staff,email,' . $staffId,
                'phone' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:8',
                'photo' => 'nullable|image|max:2048',
                'remove_photo' => 'nullable|in:0,1',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'updated_at' => now(),
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->input('password'));
            }

            // Handle photo removal or replacement
            $removePhoto = $request->input('remove_photo') === '1' || $request->input('remove_photo') === 1;

            DB::beginTransaction();
            try {
                // If user uploaded a new photo, store it and update staff_photos
                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $path = $file->store('staff_photos', 'public');

                    // delete old photo if exists
                    $existing = DB::table('staff_photos')->where('staff_id', $staffId)->first();
                    if ($existing && !empty($existing->path)) {
                        Storage::disk('public')->delete($existing->path);
                    }

                    DB::table('staff_photos')->updateOrInsert(
                        ['staff_id' => $staffId],
                        ['path' => $path, 'updated_at' => now()]
                    );
                } elseif ($removePhoto) {
                    // remove existing photo record and delete file
                    $existing = DB::table('staff_photos')->where('staff_id', $staffId)->first();
                    if ($existing && !empty($existing->path)) {
                        Storage::disk('public')->delete($existing->path);
                    }
                    DB::table('staff_photos')->where('staff_id', $staffId)->delete();
                }

                // Update staff table
                DB::table('staff')->where('id', $staffId)->update($data);

                DB::commit();
                return redirect()->back()->with('success', 'Profile updated successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                // Log the error if you have logging setup (omitted here for brevity)
                return redirect()->back()->with('error', 'Failed to update profile. Please try again.');
            }
        }

        // Render profile page
        $staffphoto = DB::table('staff_photos')->where('staff_id', $staff->id)->first();
        return view('delivery.profile', compact('staff', 'restaurant', 'staffphoto'));
    }
}

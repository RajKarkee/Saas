<?php

namespace App\Http\Controllers\Restaurant\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Staff;

class StaffController extends Controller
{
    public function index(Request $request){
       
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
        
      
        $delivery = DB::table('staff')
            ->where('restaurant_id', $staff->restaurant_id)
            ->where('role', 2)
            ->get();
        
       
        $orders = DB::table('orders')
            ->leftJoin('users', 'orders.customer_id', '=', 'users.id')
            ->leftJoin('staff as delivery_staff', 'orders.delivery_person_id', '=', 'delivery_staff.id')
            ->where('orders.restaurant_id', $staff->restaurant_id)
            ->select('orders.*', 'users.name as customer_name', 'users.email as customer_email', 
                     'delivery_staff.name as delivery_person_name')
            ->orderBy('orders.created_at', 'desc')
            ->get();

        return view('staff.dashboard', compact('staff', 'restaurant', 'orders', 'delivery'));
    }
    
    public function assignDelivery(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'delivery_person_id' => 'required|integer|exists:staff,id'
        ]);
        
        // Get the staff session
        $staffId = $request->session()->get('staff_id');
        if (!$staffId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $staff = DB::table('staff')->where('id', $staffId)->first();
        
    
        $order = DB::table('orders')
            ->where('id', $validated['order_id'])
            ->where('restaurant_id', $staff->restaurant_id)
            ->first();
            
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }
        
       
        $deliveryPerson = DB::table('staff')
            ->where('id', $validated['delivery_person_id'])
            ->where('restaurant_id', $staff->restaurant_id)
            ->where('role', 2)
            ->first();
            
        if (!$deliveryPerson) {
            return response()->json(['success' => false, 'message' => 'Invalid delivery person'], 404);
        }
        
     
        DB::table('orders')
            ->where('id', $validated['order_id'])
            ->update([
                'delivery_person_id' => $validated['delivery_person_id'],
                'status' => 'confirmed',
                'updated_at' => now()
            ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Delivery person assigned successfully'
        ]);
    }
    
    public function updateOrderStatus(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'status' => 'required|string|in:pending,confirmed,preparing,ready,out_for_delivery,delivered,completed,cancelled'
        ]);
        
     
        $staffId = $request->session()->get('staff_id');
        if (!$staffId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $staff = DB::table('staff')->where('id', $staffId)->first();
        
       
        $order = DB::table('orders')
            ->where('id', $validated['order_id'])
            ->where('restaurant_id', $staff->restaurant_id)
            ->first();
            
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }
        
     
        DB::table('orders')
            ->where('id', $validated['order_id'])
            ->update([
                'status' => $validated['status'],
                'updated_at' => now()
            ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully'
        ]);
    }
    
    public function setting(Request $request){
        $staffId = $request->session()->get('staff_id');
        if (!$staffId) {
            abort(403, 'Staff session missing.');
        }
        
        $staff = DB::table('staff')->where('id', $staffId)->first();
        $photo =DB::table('staff_photos')->where('staff_id', $staffId)->first();
        if (!$staff) {
            abort(404, 'Staff not found.');
        }
        
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name' => 'required|string|max:225',
                'email' => 'required|string|email|max:225|unique:staff,email,' . $staffId,
                'phone' => 'nullable|string|max:15',
                'password' => 'nullable|string|min:8',
            ]);
            
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'updated_at' => now()
            ];
            
            if (!empty($validated['password'])) {
                $updateData['password'] = bcrypt($validated['password']);
            }
            
            DB::table('staff')->where('id', $staffId)->update($updateData);
            
            return redirect()->back()->with('success', 'Profile updated successfully.');
        }

        return view('staff.setting', compact('staff', 'photo'));
    }

    public function create(){
        return view('restaurant.staff.create');
    }
    public function store(Request $request){
        $validated=$request->validate([
            'name'=> 'required|string|max:225',
            'email'=> 'required|string|email|max:225|unique:staff',
            'phone'=> 'nullable|string|max:15',
            'role'=> 'required|integer',
            'password'=> 'required|string|min:8',
        ]);
        
    }
}

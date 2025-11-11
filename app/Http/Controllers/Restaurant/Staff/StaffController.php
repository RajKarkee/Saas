<?php

namespace App\Http\Controllers\Restaurant\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index(Request $request){
       
        
        
        $staff = DB::table('staff')->where('id', Auth::guard('staff')->id())->first();
        if (!$staff) {
            abort(404, 'Staff not found.');
        }
        
        $restaurant = DB::table('restaurants')->where('id', $staff->restaurant_id)->first();
        if (!$restaurant) {
            abort(404, 'Restaurant not found.');
        }
        
      
        $delivery = DB::table('staff')
            ->leftJoin('staff_photos', 'staff.id', '=', 'staff_photos.staff_id')
            ->where('restaurant_id', $staff->restaurant_id)
            ->where('role', 2)
            ->select('staff.*','staff_photos.photo_url as photo_url')
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
        
        
        $staff = DB::table('staff')->where('id', Auth::guard('staff')->id())->first();
        
    
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
                'status' => 1,
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
        
 
        
        $staff = DB::table('staff')->where('id', Auth::guard('staff')->id())->first();
        
       
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

        $staff = DB::table('staff')->where('id', Auth::guard('staff')->id())->first();
        $photo =DB::table('staff_photos')->where('staff_id', Auth::guard('staff')->id())->first();
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

    public function orderView(Request $request, $id){


        $staff = DB::table('staff')->where('id', Auth::guard('staff')->id())->first();
        if (!$staff) {
            abort(404, 'Staff not found.');
        }

        $restaurant = DB::table('restaurants')->where('id', $staff->restaurant_id)->first();
        if (!$restaurant) {
            abort(404, 'Restaurant not found.');
        }

        // $order = DB::table('orders')
        //     ->leftJoin('users', 'orders.customer_id', '=', 'users.id')
        //     ->leftJoin('staff as delivery_staff', 'orders.delivery_person_id', '=', 'delivery_staff.id')
        //     ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
        //     ->leftJoin('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
        //     ->leftJoin('menu_categories', 'menu_items.category_id', '=', 'menu_categories.id')
        //     ->where('orders.id', $id)
        //     ->where('orders.restaurant_id', $staff->restaurant_id)
        //     ->select('orders.*', 'users.name as customer_name', 'users.email as customer_email',
        //              'delivery_staff.name as delivery_person_name', 'order_items.*', 'menu_items.name as menu_item_name', 'menu_categories.name as menu_category_name')
        //     ->first();

        $order =DB::table('orders')
        ->leftjoin('users', 'orders.customer_id', '=', 'users.id')
        ->leftjoin('staff', 'orders.delivery_person_id', '=', 'staff.id')
        ->select('orders.*', 'users.name as customer_name', 'users.email as customer_email', 'staff.name as delivery_person_name','staff.id as delivery_person_id','staff.phone as delivery_person_phone')
        ->where('orders.id', $id)
        ->where('orders.restaurant_id', $staff->restaurant_id)->first();

        $items = DB::table('order_items')
        ->leftjoin('menu_items','order_items.menu_item_id','=','menu_items.id')
        ->leftjoin('menu_item_images','menu_items.id','=','menu_item_images.menu_item_id')
        ->leftjoin('menu_item_addons','menu_items.id','=','menu_item_addons.menu_item_id')
        ->leftjoin('menu_categories','menu_items.menu_category_id','=','menu_categories.id')
        ->select('order_items.*','menu_items.name as item_name','menu_items.description as item_description','menu_items.price as item_price','menu_items.stock_quantity as item_stock_quantity',
        'menu_item_images.image_url as item_image','menu_item_images.image_alt as item_image_alt',
        'menu_item_addons.name as addon_name','menu_item_addons.additional_price as addon_price','menu_item_addons.max_select as addon_max_select',
        'menu_categories.name as item_catagory','menu_categories.description as category_description')
        ->where('order_items.order_id', $id)
        ->get();
        if (!$order) {
            abort(404, 'Order not found.');
        }

        return view('staff.orderview', compact('staff', 'restaurant','items','order'));
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

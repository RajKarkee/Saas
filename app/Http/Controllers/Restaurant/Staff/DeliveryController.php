<?php

namespace App\Http\Controllers\Restaurant\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Staff;
use App\Models\Restaurant;
use App\Models\Order;

class DeliveryController extends Controller
{
   public function index(Request $request)
    {
        $staffId = $request->session()->get('staff_id');
        if (!$staffId) {
            abort(403, 'Staff session missing.');
        }

       $staff= DB::table('staff')->where('id', $staffId)->first();
       $restaurant = DB::table('restaurants')->where('id', $staff->restaurant_id)->first();
       $orders = DB::table('orders')->where('restaurant_id', $staff->restaurant_id)
       ->where('delivery_person_id', $staff->id)
       ->where('status', 'pending')->get();
       $staffPhotos = DB::table('staff_photos')->where('staff_id', $staff->id)->first();

        return view('delivery.layout.app', compact('staff', 'restaurant', 'orders', 'staffPhotos'));
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
}

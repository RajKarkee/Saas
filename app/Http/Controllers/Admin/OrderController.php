<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Staff;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\OrderItem;


class OrderController extends Controller
{
    public function orderIndex(Request $request)
    {
        $adminId = $request->session()->get('admin_id');
        if (!$adminId) {
            abort(403, 'Admin session missing.');
        }
        // Use owner_id like other admin controllers to locate the restaurant
        $restaurant = Restaurant::where('owner_id', $adminId)->first();
        if (!$restaurant) {
            abort(403, 'Restaurant not found.');
        }
        $orders = Order::where('restaurant_id', $restaurant->id)->get();
        $staff = Staff::where('restaurant_id', $restaurant->id)->get();
        $menu_category = MenuCategory::where('restaurant_id', $restaurant->id)->get();
        $order_items = OrderItem::whereIn('order_id', $orders->pluck('id'))->get();

        return view('restaurant.order.index', compact('orders', 'staff', 'menu_category', 'order_items'));
    }
}

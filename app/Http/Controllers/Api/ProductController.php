<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\DeliveryStaffResource;
use App\Http\Resources\Api\MenuCategoryResource;
use App\Http\Resources\Api\MenuItemAddonResource;
use App\Http\Resources\Api\MenuItemResource;
use App\Http\Resources\Api\OrderStatusResource;
use App\Http\Resources\Api\RestaurantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function getRestaurant(Request $request, $restaurantId)
    {
        $restaurant = DB::table('restaurants')->where('id', $restaurantId)->first();

        if (!$restaurant) {
            return response()->json(['error' => 'Restaurant not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['restaurant' => new RestaurantResource($restaurant)], Response::HTTP_OK);
    }
    public function getMenuCategories(Request $request, $restaurantId){
        $categories = DB::table('menu_categories')
            ->where('restaurant_id', $restaurantId)
            ->get();

        return response()->json(['categories' => MenuCategoryResource::collection($categories)], Response::HTTP_OK);

    }
    public function getItems(Request $request, $restaurantId, $categoryId){
        // table is `menu_items` and category column is `menu_category_id`
        $items = DB::table('menu_items')
            ->where('restaurant_id', $restaurantId)
            ->where('menu_category_id', $categoryId)
            ->get();

        return response()->json(['items' => MenuItemResource::collection($items)], Response::HTTP_OK);
    }
    public function getItemAddons(Request $request, $itemId){
        // addons table is `menu_item_addons` and FK column is `menu_item_id`
        $addons = DB::table('menu_item_addons')
            ->where('menu_item_id', $itemId)
            ->get();

        return response()->json(['addons' => MenuItemAddonResource::collection($addons)], Response::HTTP_OK);
    }
    public function getDeliveryStaff(Request $request, $restaurantId){
        // Return staff for the restaurant. Previously we joined orders which could duplicate entries.
        $deliveryStaff = DB::table('staff')
            ->where('restaurant_id', $restaurantId)
            ->select('id', 'name', 'email', 'phone')
            ->get();

        return response()->json(['delivery_staff' => DeliveryStaffResource::collection($deliveryStaff)], Response::HTTP_OK);
    }
    public function getOrderStatus(Request $request, $restaurantId){
        $orderStatuses = DB::table('orders')
            ->where('restaurant_id', $restaurantId)
            ->select('id', 'status')
            ->get();
        return response()->json(['order_statuses' => OrderStatusResource::collection($orderStatuses)], Response::HTTP_OK);
    }
}

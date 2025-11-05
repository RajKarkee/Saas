<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController as ProductController;

// Note: routes defined in routes/api.php are automatically prefixed with /api by Laravel.
// Do not add an extra /api prefix here — that caused endpoints to be available at /api/api/... previously.

Route::get('restaurant/{restaurantId}', [ProductController::class, 'getRestaurant']);
Route::get('restaurant/{restaurantId}/categories', [ProductController::class, 'getMenuCategories']);
Route::get('restaurant/{restaurantId}/categories/{categoryId}/items', [ProductController::class, 'getItems']);
Route::get('item/{itemId}/addons', [ProductController::class, 'getItemAddons']);
Route::get('restaurant/{restaurantId}/delivery-staff', [ProductController::class, 'getDeliveryStaff']);
Route::get('restaurant/{restaurantId}/order-statuses', [ProductController::class, 'getOrderStatus']);
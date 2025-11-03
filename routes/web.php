<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Super_Admin\RestaurantController as Super_AdminRestaurantController;
use App\Http\Controllers\Restaurant\AuthenticationController as RestaurantAuthController;
use App\Http\Controllers\Restaurant\Staff\StaffController as RestaurantStaffController;
use App\Http\Controllers\Super_Admin\AdminController as SuperAdminadminController;
use App\Http\Controllers\Admin\LoginController as AdminLogin;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RestaurantController as AdminRestaurantController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

Route::get('/', function () {
    return view('restaurant.layout.app');
});
Route::get('/dashboard', function () {
    return view('admin.layout.dashboard');
})->name('dashboard');
Route::get('/superadmin/login', [SuperAdminadminController::class, 'showLoginForm'])->name('superadmin.login');
Route::post('/superadmin/login', [SuperAdminadminController::class, 'login']);
Route::post('/superadmin/logout', [SuperAdminadminController::class, 'logout'])->name('superadmin.logout');
  Route::get('admin/login',[AdminLogin::class,'login'])->name('login');
  Route::post('admin/verify',[AdminLogin::class,'verify'])->name('admin.verify');

Route::prefix('super_admin')->middleware(['superadmin.sanctum:superadmin'])->name('super_admin.')->group(function(){
    Route::prefix('admins')->name('admins.')->group(function(){
    Route::match(['get','post'],'/index',[SuperAdminadminController::class,'index']
    )->name('index');
    Route::get('/create',[SuperAdminadminController::class,'create']
    )->name('create');
    Route::post('/store', [SuperAdminadminController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [SuperAdminadminController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [SuperAdminadminController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [SuperAdminadminController::class, 'destroy'])->name('destroy');
    });
Route::prefix('restaurant')->name('restaurant.')->group(function(){
    Route::match(['get','post'],'/index',[Super_AdminRestaurantController::class,'index']
    )->name('index');
    Route::get('/create',[Super_AdminRestaurantController::class,'create']
    )->name('create');
    Route::post('/store', [Super_AdminRestaurantController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [Super_AdminRestaurantController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [Super_AdminRestaurantController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [Super_AdminRestaurantController::class, 'destroy'])->name('destroy');
    Route::get('/search', [Super_AdminRestaurantController::class, 'search'])->name('admin.search');
    Route::get('/pending', [Super_AdminRestaurantController::class, 'pending'])->name('pending');
    Route::get('/restaurant-view/{id}', [Super_AdminRestaurantController::class, 'view'])->name('view');
    
});
});

Route::prefix('admin')->middleware(['admin.sanctum:admin'])->name('admin.')->group(function(){
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::prefix('/restaurants')->name('restaurant.')->group(function(){
    Route::post('/store', [AdminDashboardController::class, 'store'])->name('store');
    Route::get('/edit', [AdminRestaurantController::class, 'edit'])->name('edit');
    Route::put('/update', [AdminRestaurantController::class, 'update'])->name('update');
    Route::match(['get','post','put'],'/settings',[AdminRestaurantController::class,'settings']
    )->name('settings');
    Route::prefix('/staff')->name('staff.')->group(function(){
    Route::get('/index', [AdminRestaurantController::class, 'staffIndex'])->name('index');
    Route::get('/create', [AdminRestaurantController::class, 'staffCreate'])->name('create');
    Route::post('/store', [AdminRestaurantController::class, 'staffStore'])->name('store');
    Route::get('/edit/{id}', [AdminRestaurantController::class, 'staffEdit'])->name('edit');
    Route::put('/update/{id}', [AdminRestaurantController::class, 'staffUpdate'])->name('update');
    Route::delete('/destroy/{id}', [AdminRestaurantController::class, 'staffDestroy'])->name('destroy');
    Route::get('/deliverymen', [AdminRestaurantController::class, 'deliveryMen'])->name('deliverymen');
    Route::get('/manager', [AdminRestaurantController::class, 'manager'])->name('manager');
    });
    Route::prefix('/menu')->name('menu.')->group(function(){
    Route::prefix('/categories')->name('categories.')->group(function(){
    Route::get('/index', [AdminRestaurantController::class,'categoryIndex'])->name('index');
    Route::get('/create', [AdminRestaurantController::class,'categoryCreate'])->name('create');
    Route::post('/store', [AdminRestaurantController::class,'categoryStore'])->name('store');
    Route::get('/edit/{id}', [AdminRestaurantController::class,'categoryEdit'])->name('edit');
    Route::put('/update/{id}', [AdminRestaurantController::class,'categoryUpdate'])->name('update');
    Route::delete('/destroy/{id}', [AdminRestaurantController::class,'categoryDestroy'])->name('destroy');
    });
    Route::prefix('/items')->name('items.')->group(function(){
    Route::get('/index/{id}', [AdminRestaurantController::class, 'itemIndex'])->name('index');
    Route::get('/create/{id}', [AdminRestaurantController::class, 'itemCreate'])->name('create');
    Route::post('/store/{id}', [AdminRestaurantController::class, 'itemStore'])->name('store');
    Route::get('/edit/{id}', [AdminRestaurantController::class, 'itemEdit'])->name('edit');
    Route::put('/update/{id}', [AdminRestaurantController::class, 'itemUpdate'])->name('update');
    Route::delete('/destroy/{id}', [AdminRestaurantController::class, 'itemDestroy'])->name('destroy');
    Route::prefix('/addons')->name('addons.')->group(function(){
        Route::get('/index/{id}', [AdminRestaurantController::class, 'addonIndex'])->name('index');
        Route::get('/create/{id}', [AdminRestaurantController::class, 'addonCreate'])->name('create');
        Route::post('/store/{id}', [AdminRestaurantController::class, 'addonStore'])->name('store');
        Route::get('/edit/{id}', [AdminRestaurantController::class, 'addonEdit'])->name('edit');
        Route::put('/update/{id}', [AdminRestaurantController::class, 'addonUpdate'])->name('update');
        Route:: delete('/destroy/{id}', [AdminRestaurantController::class, 'addonDestroy'])->name('destroy');
    });
});
    });
    Route::prefix('/orders')->name('orders.')->group(function(){
    Route::get('/index', [AdminOrderController::class, 'orderIndex'])->name('index');
    });
});
Route::prefix('/users')->name('users.')->group(function(){
    Route::get('/index', [AdminUserController::class, 'userIndex'])->name('index');
});
});
Route::prefix('restaurant')->name('restaurant.')->group(function(){
    // Registration disabled for restaurants (login-only)
    // Route::post('/register',[RestaurantAuthController::class,'register'])->name('register');
    Route::post('/login',[RestaurantAuthController::class,'login'])->name('login');
    Route::prefix('staff')->name('staff.')->group(function(){
   Route::get('/index',[RestaurantStaffController::class,'index'])->name('index');
    Route::get('/create',[RestaurantStaffController::class,'create'])->name('create');
    Route::post('/store', [RestaurantStaffController::class, 'store'])->name('store');
    });

});
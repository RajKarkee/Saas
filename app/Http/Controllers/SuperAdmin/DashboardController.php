<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        $superAdmin = auth()->guard('super_admin')->user();
        $restaurantCount = DB::table('restaurants')->count();
        $restaurant=DB::table('restaurants')->
        leftjoin('admins','restaurants.owner_id','=','admins.id')->
        select('restaurants.*','admins.name as owner_name','admins.status as owner_status')->
        get();
        
        return view('admin.layout.dashboard',compact('superAdmin','restaurantCount','restaurant'));
    }
}

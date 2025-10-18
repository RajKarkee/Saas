<?php

namespace App\Http\Controllers\Restaurant\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(){
        return view('restaurant.staff.staff');
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

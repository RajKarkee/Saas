<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\RestaurantSetting;
use Illuminate\Support\Facades\Schema;
use App\Models\Staff;
use App\Models\Admin;
use App\Models\StaffPhoto;
use Illuminate\Support\Facades\Hash;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuItemAddon;
use App\Models\MenuItemImage;
use App\Models\RestaurantSchedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class RestaurantController extends Controller
{
    /**
     * Show the edit form for the current admin's single restaurant.
     */
    public function edit(Request $request)
    {
 

       
        $restaurant = Restaurant::where('owner_id', Auth::id())->first();
        if (!$restaurant) {
            $restaurant = new Restaurant([
                'name' => '',
                'domain' => '',
                'subdomain' => '',
                'status' => 'inactive',
                'logo' => null,
            ]);
        }

        return view('restaurant.restaurant', [
            'restaurant' => $restaurant,
        ]);
    }

    /**
     * Update the restaurant details. Logo is present in UI but intentionally ignored here.
     */
    public function update(Request $request)
    {
    

        $restaurant = Restaurant::where('owner_id', Auth::id())->first();

    
        $domainRule = ['required', 'string', 'max:255'];
        if ($restaurant) {
            $domainRule[] = Rule::unique('restaurants', 'domain')->ignore($restaurant->id);
        } else {
            $domainRule[] = Rule::unique('restaurants', 'domain');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domain' => $domainRule,
            'subdomain' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            // 'logo' field deliberately not processed server-side per requirement
        ]);

        if (!$restaurant) {
            // Create a new restaurant for this admin
            $restaurant = new Restaurant();
            $restaurant->owner_id = $adminId;
        }

    // Upsert fields (excluding logo handling)
        $restaurant->name = $validated['name'];
        $restaurant->domain = $validated['domain'];
        $restaurant->subdomain = $validated['subdomain'] ?? null;
        $restaurant->status = $validated['status'];
        $restaurant->save();

        return redirect()->back()->with('success', 'Restaurant details saved successfully.');
    }
    /**
     * Update weekly schedules for the restaurant.
     * Expects arrays: day_of_week[], opening_time[], closing_time[], is_open[]
     */
public function updateSchedules(Request $request)
{

    $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

    $days        = $request->input('day_of_week', []);
    $opening     = $request->input('opening_time', []);
    $closing     = $request->input('closing_time', []);
    $openFlags   = $request->input('is_open', []); // checkbox values (only those checked appear)

    $validDays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    $messages  = [];

    foreach ($validDays as $index => $day) {

      
        $o = $opening[$index] ?? null;
        $c = $closing[$index] ?? null;
        $isOpen = array_key_exists($index, $openFlags); 

    
        if ($isOpen) {

           
            if (!$o || !$c) {
                $messages[] = "$day requires both opening and closing time.";
                continue;
            }

            if ($o >= $c) {
                $messages[] = "$day closing time must be after opening time.";
                continue;
            }
        }

      
        $record = $restaurant->schedules()->firstOrNew(['day_of_week' => $day]);

    
        $record->is_open      = $openFlags[$index];
        $record->opening_time = $isOpen ? $o : null;
        $record->closing_time = $isOpen ? $c : null;
        $record->save();
    }

  
    if (count($messages) > 0) {
        return back()->with('error', 'Some schedule errors: ' . implode(', ', $messages));
    }

    return back()->with('success', 'Schedules updated successfully.');
}

    public function settings(Request $request)
    {
                    // $adminId = $request->session()->get('admin_id');
                    // if (!$adminId) {
                    //     abort(403, 'Admin session missing.');
                    // }

        // Ensure the admin has a restaurant
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        
        if ($request->isMethod('get')) {
            $setting = $restaurant->settings()->first();
            return view('restaurant.restaurantSetting', [
                'restaurant' => $restaurant,
                'setting' => $setting,
            ]);
        }

        
        $validated = $request->validate([
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'map_url' => ['nullable', 'url', 'max:2048'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $setting = $restaurant->settings()->first();
        if (! $setting) {
            $setting = new RestaurantSetting();
            $setting->restaurant_id = $restaurant->id;
        }

        $setting->address = $validated['address'] ?? null;
        $setting->phone = $validated['phone'] ?? null;

        // Only set email if the column exists (migration may be missing the column)
        if (Schema::hasColumn('restaurant_settings', 'email')) {
            $setting->email = $validated['email'] ?? null;
        }

        $setting->map_url = $validated['map_url'] ?? null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('restaurant_logos', 'public');
            $setting->logo = $logoPath;
        }

        $setting->save();

        return redirect()->back()->with('success', 'Restaurant settings saved successfully.');
    }
    public function staffIndex(Request $request)
    {
      

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $staff = $restaurant->staff()->get();
        $staff_photos = StaffPhoto::whereIn('staff_id', $staff->pluck('id'))->get()->keyBy('staff_id');
        
        return view('restaurant.staff.staff',  compact('staff', 'staff_photos')
        );
    }
    public function staffCreate(Request $request)
    {
        return view('restaurant.staff.create');
    }
public function staffStore(Request $request)
    {


        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $roleMap=[
            'Manager'=>0,
            'Staff'=>1,
            'Delivery Person'=>2,
        ];
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('staff', 'email')],
            'password' => ['string', 'min:8', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'role' => ['required', 'string', 'in:Manager,Delivery Person,Staff'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);
     
        $validated['role']=$roleMap[$validated['role']];

        $staff = $restaurant->staff()->create([
            'restaurant_id' => $restaurant->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);
        if ($validated['photo'] ?? false) {
            $imagePath = $validated['photo']->store('staff_photos', 'public');
            StaffPhoto::create([
                'staff_id' => $staff->id,
                'photo_url' => $imagePath,
            ]);
        }

        return redirect()->route('admin.restaurant.staff.index')->with('success', 'Staff member created successfully.');
    }
    public function staffEdit(Request $request, $staffId)
    {
  

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $staff = $restaurant->staff()->findOrFail($staffId);
        $staff_photo = StaffPhoto::where('staff_id', $staff->id)->first();

         if ($staff_photo) {
            $staff->photo_url = $staff_photo->photo_url;
        } else {
            $staff->photo_url = null;
        }
      

        return view('restaurant.staff.edit', compact('staff'));
    }
    public function staffUpdate(Request $request, $staffId)
    {
  
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $staff = $restaurant->staff()->findOrFail($staffId);

        $roleMap=[
            'Manager'=>0,
            'Staff'=>1,
            'Delivery Person'=>2,
        ];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('staff', 'email')->ignore($staff->id)],
            'password' => ['nullable', 'string', 'min:8', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'role' => ['required', 'string', 'in:Manager,Delivery Person,Staff'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['role']=$roleMap[$validated['role']];

        $staff->name = $validated['name'];
        $staff->email = $validated['email'];
        if (!empty($validated['password'])) {
            $staff->password = Hash::make($validated['password']);
        }
        $staff->phone = $validated['phone'] ?? null;
        $staff->role = $validated['role'];
        $staff->status = $validated['status'];
        $staff->save();

        if ($validated['photo'] ?? false) {
            $imagePath = $validated['photo']->store('staff_photos', 'public');
            $staffPhoto = StaffPhoto::where('staff_id', $staff->id)->first();
            if ($staffPhoto) {
                $staffPhoto->photo_url = $imagePath;
                $staffPhoto->save();
            } else {
                StaffPhoto::create([
                    'staff_id' => $staff->id,
                    'photo_url' => $imagePath,
                ]);
            }
        }

        return redirect()->route('admin.restaurant.staff.index')->with('success', 'Staff member updated successfully.');
    }
    public function staffDestroy(Request $request, $staffId)
    {


        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $staff = $restaurant->staff()->findOrFail($staffId);

        // Delete associated photo if exists
        StaffPhoto::where('staff_id', $staff->id)->delete();

        $staff->delete();

        return redirect()->route('admin.restaurant.staff.index')->with('success', 'Staff member deleted successfully.');
    }
    public function deliveryMen(Request $request)
    {
   

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $deliveryMen = $restaurant->staff()->where('role', 2)->get();
        $staff_photos = StaffPhoto::whereIn('staff_id', $deliveryMen->pluck('id'))->get()->keyBy('staff_id');

        return view('restaurant.staff.deliveryMen',  compact('deliveryMen', 'staff_photos')
        );
    }
    public function manager(Request $request)
    {
 

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $managers = $restaurant->staff()->where('role', 0)->get();
        $staff_photos = StaffPhoto::whereIn('staff_id', $managers->pluck('id'))->get()->keyBy('staff_id');
        return view('restaurant.staff.manager',  compact('managers', 'staff_photos')
        );
    }
    public function categoryIndex(Request $request){
   
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
        $categories = $restaurant->menuCategories()->get();
        return view('restaurant.menu.categories.index', compact('categories'));
    }
    public function categoryCreate(){
        return view('restaurant.menu.categories.form');
    }
    public function categoryStore(Request $request){
 

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'position' => ['nullable', 'integer', 'min:1',
            Rule::unique('menu_categories', 'position')->where(function($query) use ($request, $restaurant) {
                return $query->where('restaurant_id', $restaurant->id);
            }   )],
            'is_active' => ['nullable', 'boolean'],
        ]);

      
   $category = new MenuCategory();
        $category->restaurant_id = $restaurant->id;
        $category->name = $validated['name'];
        $category->position = $validated['position'] ?? null;
        $category->is_active = $validated['is_active'] ?? true;
        $category->description = $validated['description'] ?? null;
        $category->save();

        return redirect()->route('admin.restaurant.menu.categories.index')->with('success', 'Category created successfully.');
    }
    public function categoryEdit(Request $request, $categoryId){
  

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $category = $restaurant->menuCategories()->findOrFail($categoryId);

        return view('restaurant.menu.categories.form', compact('category'));
    }
    public function categoryUpdate(Request $request, $categoryId){
 

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $category = $restaurant->menuCategories()->findOrFail($categoryId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'position' => ['nullable', 'integer', 'min:1',
            Rule::unique('menu_categories','position')->where(function($query) use ($category, $restaurant) {
                return $query->where('restaurant_id', $restaurant->id)
                             ->where('id', '!=', $category->id);
            }   )], 
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->name = $validated['name'];
        $category->position = $validated['position'] ?? null;
        $category->is_active = $validated['is_active'] ?? true;
        $category->description = $validated['description'] ?? null;
        $category->save();

        return redirect()->route('admin.restaurant.menu.categories.index')->with('success', 'Category updated successfully.');
    }
    public function categoryDestroy(Request $request, $id){
 
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $category = $restaurant->menuCategories()->findOrFail($id);

        $category->delete();

        return redirect()->route('admin.restaurant.menu.categories.index')->with('success', 'Category deleted successfully.');
    }
    public function itemIndex(Request $request, $id){
      
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
       $category = MenuCategory::findOrFail($id);
      

        //
    
        $itemsQuery = $restaurant->menuItems();

        if ($category) {
            $itemsQuery->where('menu_category_id', $category->id);
        }

        $items = $itemsQuery->get();
        $item_images = MenuItemImage::whereIn('menu_item_id', $items->pluck('id'))->get()->groupBy('menu_item_id');

        return view('restaurant.menu.items.index', compact('items', 'category', 'item_images'));
    }
    public function itemCreate(Request $request, $categoryId){


        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
       $category = MenuCategory::findOrFail($categoryId);
        return view('restaurant.menu.items.form', compact('category'));
    }
    public function itemStore(Request $request, $categoryId){
  

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
       $category = MenuCategory::findOrFail($categoryId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
            'stock_quantity' => ['nullable', 'integer',],
            'image' => ['nullable', 'image', 'max:2048'],
            'image_alt' => ['nullable', 'image', 'max:2048'],
        ]);

        $item = new MenuItem();
        $item->restaurant_id = $restaurant->id;
        $item->menu_category_id = $category->id;
        $item->name = $validated['name'];
        $item->description = $validated['description'] ?? null;
        $item->price = $validated['price'];
        $item->is_available = $validated['is_available'] ?? true;
        $item->stock_quantity = $validated['stock_quantity'] ?? null;
   
        $item->save();
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu_item_images', 'public');
            $imageAltPath = null;
            if ($request->hasFile('image_alt')) {
                $imageAltPath = $request->file('image_alt')->store('menu_item_images', 'public');
            }
            MenuItemImage::create([
                'menu_item_id' => $item->id,
                'image_alt' => $imageAltPath,
                'image_url' => $imagePath,
            ]);
        }

        return redirect()->route('admin.restaurant.menu.items.index', $category->id)->with('success', 'Menu item added successfully.');
    }
    public function itemEdit(Request $request, $itemId){


        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $item = $restaurant->menuItems()->findOrFail($itemId);
        $category = MenuCategory::findOrFail($item->menu_category_id);
        $item_images = MenuItemImage::where('menu_item_id', $item->id)->get();

        return view('restaurant.menu.items.form', compact('item', 'category','item_images'));
    }
    public function itemUpdate(Request $request, $itemId){
        $adminId = $request->session()->get('admin_id');


        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $item = $restaurant->menuItems()->findOrFail($itemId);
        $category = MenuCategory::findOrFail($item->menu_category_id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
            'stock_quantity' => ['nullable', 'integer',],
        ]);

        $item->name = $validated['name'];
        $item->description = $validated['description'] ?? null;
        $item->price = $validated['price'];
        $item->is_available = $validated['is_available'] ?? true;
        $item->stock_quantity = $validated['stock_quantity'] ?? null;
   
        $item->save();
        if(request()->hasFile('image')){
            $imagePath = $request->file('image')->store('menu_item_images', 'public');
            if(request()->hasFile('image_alt')){
                $image_alt = $request->file('image_alt')->store('menu_item_images', 'public');
            }else{
                $image_alt = null;
            }
           
   $menu_item_image= MenuItemImage::where('menu_item_id', $item->id)->first();
            if($menu_item_image){
                $menu_item_image->image_url = $imagePath;
                $menu_item_image->image_alt = $image_alt;
                $menu_item_image->save();
            }else{
                Menu_item_image::create([
                    'menu_item_id' => $item->id,
                    'image_alt' => $image_alt ,
                    'image_url' => $imagePath,
                ]);
            }
        }

        return redirect()->route('admin.restaurant.menu.items.index', $category->id)->with('success', 'Menu item updated successfully.');
    }
    public function itemDestroy(Request $request, $itemId){


        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $item = $restaurant->menuItems()->findOrFail($itemId);
        $category = MenuCategory::findOrFail($item->menu_category_id);

        // Delete associated images if exist
        MenuItemImage::where('menu_item_id', $item->id)->delete();

        $item->delete();

        return redirect()->route('admin.restaurant.menu.items.index', $category->id)->with('success', 'Menu item deleted successfully.');
    }
    public function addonIndex(Request $request, $itemId){
        // $adminId = $request->session()->get('admin_id');
        // if (!$adminId) {
        //     abort(403, 'Admin session missing.');
        // }
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
       $item = MenuItem::findOrFail($itemId);
       $category = MenuCategory::findOrFail($item->menu_category_id);
        //
    
        $addons = $restaurant->menuItemAddons()->where('menu_item_id', $item->id)->get();

        return view('restaurant.menu.addons.index', compact('addons', 'item', 'category'));
    }
    public function addonCreate(Request $request, $itemId){
    
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
       $item = MenuItem::findOrFail($itemId);
        return view('restaurant.menu.addons.form', compact('item','restaurant'));
    }
    public function addonStore(Request $request, $itemId){
  

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
       $item = MenuItem::findOrFail($itemId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'additional_price' => ['required', 'numeric', 'min:0'],
            'is_active' => [ 'boolean'],
            'is_available' => [ 'boolean'],
        ]);

        $addon = new MenuItemAddon();
        $addon->restaurant_id = $restaurant->id;
        $addon->menu_item_id = $item->id;
        $addon->name = $validated['name'];
        $addon->additional_price = $validated['additional_price'];
     
        $addon->is_available = $validated['is_available'] ?? true;
        $addon->save();

        return redirect()->route('admin.restaurant.menu.items.addons.index', $item->id)->with('success', 'Addon added successfully.');
    }
    public function addonEdit(Request $request, $id){
    
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

       $addon = MenuItemAddon::findOrFail($id);
         $item = MenuItem::findOrFail($addon->menu_item_id);

        return view('restaurant.menu.addons.form', compact('addon', 'item'));
    }
    public function addonUpdate(Request $request, $id){
     

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $addon = MenuItemAddon::findOrFail($id);
        $item = MenuItem::findOrFail($addon->menu_item_id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'additional_price' => ['required', 'numeric', 'min:0'],
            'is_active' => [ 'boolean'],
            'is_available' => [ 'boolean'],
        ]);

        $addon->name = $validated['name'];
        $addon->additional_price = $validated['additional_price'];
     
        $addon->is_available = $validated['is_available'] ?? true;
        $addon->save();
        
        return redirect()->route('admin.restaurant.menu.items.addons.index', $item->id)->with('success', 'Addon updated successfully.');
    }
    public function addonDestroy(Request $request, $id){
  

        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $addon = MenuItemAddon::findOrFail($id);
        $item = MenuItem::findOrFail($addon->menu_item_id);

        $addon->delete();

        return redirect()->route('admin.restaurant.menu.items.addons.index', $item->id)->with('success', 'Addon deleted successfully.');
    }
    public function allItems(Request $request){
        if($request->isMethod('get')){
            $restaurant =Restaurant::where('owner_id', Auth::id())->firstOrFail();
            $items = DB::table('menu_items')->where('menu_items.restaurant_id', $restaurant->id)
            ->leftjoin('menu_categories', 'menu_items.menu_category_id', '=', 'menu_categories.id')
            ->leftjoin('menu_item_images', 'menu_items.id', '=', 'menu_item_images.menu_item_id')
            ->select('menu_items.*', 'menu_categories.name as category_name', 'menu_item_images.image_url as image')
            ->get();

            return view('restaurant.itemsAll.index', compact('items'));
        }
    }
}
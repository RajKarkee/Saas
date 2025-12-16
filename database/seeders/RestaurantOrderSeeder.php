<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Restaurant;
use App\Models\Menu_item;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\User;
use Faker\Factory as Faker;

class RestaurantOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Use an existing restaurant id (5) instead of creating a new restaurant
        $restaurantId = 5;

        // Ensure restaurant exists; if not, stop to avoid seeding into wrong place
        $restaurantExists = DB::table('restaurants')->where('id', $restaurantId)->exists();
        if (! $restaurantExists) {
            throw new \Exception("Restaurant with id {$restaurantId} not found. Please create it before running this seeder or change the restaurant id in the seeder.");
        }

        // Ensure there are menu categories for this restaurant; create defaults if none exist
        $categoryIds = DB::table('menu_categories')->where('restaurant_id', $restaurantId)->pluck('id')->toArray();
        if (empty($categoryIds)) {
            for ($c = 1; $c <= 2; $c++) {
                $categoryIds[] = DB::table('menu_categories')->insertGetId([
                    'restaurant_id' => $restaurantId,
                    'name' => 'Category ' . $c,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Ensure there are menu items for this restaurant; create defaults if none exist
        $menuItemIds = DB::table('menu_items')->where('restaurant_id', $restaurantId)->pluck('id')->toArray();
        if (empty($menuItemIds)) {
            for ($i = 1; $i <= 6; $i++) {
                $menuItemIds[] = DB::table('menu_items')->insertGetId([
                    'restaurant_id' => $restaurantId,
                    'menu_category_id' => $categoryIds[array_rand($categoryIds)],
                    'name' => 'Menu Item ' . $i,
                    'description' => $faker->sentence(),
                    'price' => $faker->randomFloat(2, 5, 30),
                    'is_available' => true,
                    'stock_quantity' => $faker->numberBetween(5, 30),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Fetch customers (users) - ensure there are users
        $customers = User::limit(10)->get();
        if ($customers->count() === 0) {
            // create a default user if none exists
            $customers = collect([User::factory()->create()]);
        }

        // Create orders for some customers
        foreach ($customers as $customer) {
            $ordersToCreate = rand(1, 3);
            for ($o = 0; $o < $ordersToCreate; $o++) {
                $itemsCount = rand(1, 4);
                $orderTotal = 0;

                $orderId = DB::table('orders')->insertGetId([
                    'restaurant_id' => $restaurantId,
                    'customer_id' => $customer->id,
                    'total_amount' => 0, // update after items
                    'status' => $faker->randomElement(['pending','completed']),
                    'order_type' => 'delivery',
                    'delivery_time' => now()->addMinutes($faker->numberBetween(20, 90)),
                    'payment_method' => $faker->randomElement(['cash', 'card']),
                    'notes' => $faker->boolean(30) ? $faker->sentence() : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Add order items
                for ($it = 0; $it < $itemsCount; $it++) {
                    $menuItemId = $menuItemIds[array_rand($menuItemIds)];
                    $menuRow = DB::table('menu_items')->where('id', $menuItemId)->first();
                    $qty = rand(1, 3);
                    $unit = $menuRow->price;
                    $totalPrice = $unit * $qty;
                    $orderTotal += $totalPrice;

                    DB::table('order_items')->insert([
                        'order_id' => $orderId,
                        'menu_item_id' => $menuItemId,
                        'quantity' => $qty,
                        'unit_price' => $unit,
                        'total_price' => $totalPrice,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Update order total
                DB::table('orders')->where('id', $orderId)->update([
                    'total_amount' => $orderTotal,
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

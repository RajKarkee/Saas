# Step-by-Step Implementation Guide

## 🚀 Day 1: Project Setup & Database Foundation

### Morning Session (2-3 hours)
#### 1. Install Required Packages
```bash
# Install Sanctum for API authentication
composer require laravel/sanctum

# Install additional packages for development
composer require --dev laravel/telescope
composer require spatie/laravel-permission
composer require spatie/laravel-sluggable
composer require spatie/laravel-query-builder

# Install frontend dependencies if needed
npm install
```

#### 2. Configure Sanctum
```bash
# Publish Sanctum configuration
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Run Sanctum migrations
php artisan migrate
```

#### 3. Create Base Migration Files
```bash
# Core tables
php artisan make:migration update_restaurants_table --table=restaurants
php artisan make:migration create_admins_table
php artisan make:migration create_customers_table

# Menu tables
php artisan make:migration create_menu_categories_table
php artisan make:migration create_menu_items_table
php artisan make:migration create_menu_item_modifiers_table
php artisan make:migration create_menu_item_modifier_options_table

# Order tables
php artisan make:migration create_carts_table
php artisan make:migration create_cart_items_table
php artisan make:migration create_orders_table
php artisan make:migration create_order_items_table

# Supporting tables
php artisan make:migration create_addresses_table
php artisan make:migration create_coupons_table
php artisan make:migration create_reviews_table
```

### Afternoon Session (3-4 hours)
#### 4. Implement Restaurant Migration
Edit `database/migrations/[timestamp]_update_restaurants_table.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
            $table->string('phone')->after('email');
            $table->string('logo')->nullable()->after('password');
            $table->string('cover_image')->nullable()->after('logo');
            $table->text('description')->nullable()->after('cover_image');
            $table->text('address')->after('description');
            $table->string('city')->after('address');
            $table->string('state')->after('city');
            $table->string('country')->default('USA')->after('state');
            $table->string('postal_code')->after('country');
            $table->decimal('latitude', 10, 8)->nullable()->after('postal_code');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('currency', 3)->default('USD')->after('longitude');
            $table->string('timezone')->default('UTC')->after('currency');
            $table->json('opening_hours')->nullable()->after('timezone');
            $table->integer('delivery_radius')->default(10)->after('opening_hours');
            $table->decimal('minimum_order', 10, 2)->default(0)->after('delivery_radius');
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('minimum_order');
            $table->decimal('tax_rate', 5, 2)->default(0)->after('delivery_fee');
            $table->boolean('is_active')->default(true)->after('tax_rate');
            $table->boolean('is_accepting_orders')->default(true)->after('is_active');
            $table->boolean('setup_completed')->default(false)->after('is_accepting_orders');
            $table->enum('subscription_plan', ['free', 'basic', 'premium', 'enterprise'])->default('free')->after('setup_completed');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_plan');
            $table->json('settings')->nullable()->after('subscription_ends_at');
            $table->softDeletes();
            
            // Add indexes
            $table->index('slug');
            $table->index('subdomain');
            $table->index('is_active');
            $table->index(['is_active', 'is_accepting_orders']);
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn([
                'slug', 'phone', 'logo', 'cover_image', 'description',
                'address', 'city', 'state', 'country', 'postal_code',
                'latitude', 'longitude', 'currency', 'timezone',
                'opening_hours', 'delivery_radius', 'minimum_order',
                'delivery_fee', 'tax_rate', 'is_active', 'is_accepting_orders',
                'setup_completed', 'subscription_plan', 'subscription_ends_at',
                'settings', 'deleted_at'
            ]);
        });
    }
};
```

#### 5. Implement Admins Migration
Edit `database/migrations/[timestamp]_create_admins_table.php`:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('phone')->nullable();
            $table->enum('role', ['owner', 'manager', 'staff'])->default('staff');
            $table->json('permissions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            // Indexes
            $table->unique(['restaurant_id', 'email']);
            $table->index('restaurant_id');
            $table->index('email');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
```

## 🚀 Day 2: Models & Authentication Setup

### Morning Session (3-4 hours)
#### 1. Create All Model Files
```bash
# Create models
php artisan make:model Admin
php artisan make:model Customer
php artisan make:model Menu/Category
php artisan make:model Menu/MenuItem
php artisan make:model Menu/Modifier
php artisan make:model Menu/ModifierOption
php artisan make:model Order/Cart
php artisan make:model Order/CartItem
php artisan make:model Order/Order
php artisan make:model Order/OrderItem
php artisan make:model Address
php artisan make:model Coupon
php artisan make:model Review
```

#### 2. Update Auth Configuration
Edit `config/auth.php`:
```php
<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
        
        'customer' => [
            'driver' => 'session',
            'provider' => 'customers',
        ],
        
        'api' => [
            'driver' => 'sanctum',
            'provider' => 'users',
        ],
        
        'admin-api' => [
            'driver' => 'sanctum',
            'provider' => 'admins',
        ],
        
        'customer-api' => [
            'driver' => 'sanctum',
            'provider' => 'customers',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
        
        'customers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        
        'admins' => [
            'provider' => 'admins',
            'table' => 'admin_password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        
        'customers' => [
            'provider' => 'customers',
            'table' => 'customer_password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
];
```

#### 3. Create BelongsToRestaurant Trait
Create `app/Traits/BelongsToRestaurant.php`:
```php
<?php

namespace App\Traits;

use App\Models\Restaurant;
use App\Scopes\RestaurantScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToRestaurant
{
    protected static function bootBelongsToRestaurant()
    {
        // Add global scope to filter by restaurant
        static::addGlobalScope(new RestaurantScope);

        // Auto-set restaurant_id when creating
        static::creating(function ($model) {
            if (empty($model->restaurant_id)) {
                $model->restaurant_id = app('tenant')->id ?? null;
            }
        });

        // Validate restaurant_id before saving
        static::saving(function ($model) {
            if (empty($model->restaurant_id)) {
                throw new \Exception('Restaurant ID is required');
            }
        });
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
```

#### 4. Create RestaurantScope
Create `app/Scopes/RestaurantScope.php`:
```php
<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class RestaurantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (app()->has('tenant')) {
            $builder->where($model->getTable() . '.restaurant_id', app('tenant')->id);
        }
    }
}
```

### Afternoon Session (3-4 hours)
#### 5. Implement Restaurant Model
Edit `app/Models/Restaurants.php` (rename to Restaurant.php):
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'email', 'phone', 'password',
        'subdomain', 'domain', 'logo', 'cover_image',
        'description', 'address', 'city', 'state', 
        'country', 'postal_code', 'latitude', 'longitude',
        'currency', 'timezone', 'opening_hours',
        'delivery_radius', 'minimum_order', 'delivery_fee',
        'tax_rate', 'is_active', 'is_accepting_orders',
        'setup_completed', 'subscription_plan', 
        'subscription_ends_at', 'settings', 'status'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'settings' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'minimum_order' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'is_accepting_orders' => 'boolean',
        'setup_completed' => 'boolean',
        'subscription_ends_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Menu\Category::class);
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(Menu\MenuItem::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order\Order::class);
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getFullDomainAttribute(): string
    {
        if ($this->domain) {
            return $this->domain;
        }

        if ($this->subdomain) {
            return $this->subdomain . '.' . config('app.domain');
        }

        return '';
    }

    public function isOpen(): bool
    {
        if (!$this->opening_hours) {
            return true;
        }

        // Implement logic to check if restaurant is open
        $day = strtolower(now($this->timezone)->format('l'));
        $time = now($this->timezone)->format('H:i');
        
        if (isset($this->opening_hours[$day])) {
            $hours = $this->opening_hours[$day];
            return $time >= $hours['open'] && $time <= $hours['close'];
        }

        return false;
    }
}
```

## 🚀 Day 3: Multi-Tenancy Middleware

### Morning Session (3-4 hours)
#### 1. Create Tenant Identification Middleware
```bash
php artisan make:middleware IdentifyTenant
php artisan make:middleware EnsureTenantSession
php artisan make:middleware ValidateRestaurantStatus
```

#### 2. Implement IdentifyTenant Middleware
Edit `app/Http/Middleware/IdentifyTenant.php`:
```php
<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $restaurant = null;

        // Check if it's a subdomain
        $appDomain = config('app.domain', 'localhost');
        if (str_ends_with($host, '.' . $appDomain)) {
            $subdomain = str_replace('.' . $appDomain, '', $host);
            if ($subdomain && $subdomain !== 'www') {
                $restaurant = Restaurant::where('subdomain', $subdomain)
                    ->where('is_active', true)
                    ->first();
            }
        } else {
            // Check for custom domain
            $restaurant = Restaurant::where('domain', $host)
                ->where('is_active', true)
                ->first();
        }

        if ($restaurant) {
            // Bind the restaurant to the container
            app()->singleton('tenant', function () use ($restaurant) {
                return $restaurant;
            });

            // Add restaurant info to request
            $request->merge(['restaurant_id' => $restaurant->id]);
        }

        return $next($request);
    }
}
```

#### 3. Register Middleware
Edit `app/Http/Kernel.php` (or `bootstrap/app.php` for Laravel 11):
```php
// Add to middleware groups
protected $middlewareGroups = [
    'web' => [
        // ... existing middleware
        \App\Http\Middleware\IdentifyTenant::class,
    ],

    'api' => [
        // ... existing middleware
        \App\Http\Middleware\IdentifyTenant::class,
    ],
];

// Add to route middleware
protected $routeMiddleware = [
    // ... existing middleware
    'tenant' => \App\Http\Middleware\IdentifyTenant::class,
    'tenant.session' => \App\Http\Middleware\EnsureTenantSession::class,
    'restaurant.active' => \App\Http\Middleware\ValidateRestaurantStatus::class,
];
```

### Afternoon Session (3-4 hours)
#### 4. Create API Controllers Structure
```bash
# Admin Controllers
php artisan make:controller Api/V1/Admin/AuthController
php artisan make:controller Api/V1/Admin/DashboardController
php artisan make:controller Api/V1/Admin/MenuCategoryController --api
php artisan make:controller Api/V1/Admin/MenuItemController --api
php artisan make:controller Api/V1/Admin/OrderController --api

# Customer Controllers
php artisan make:controller Api/V1/Customer/AuthController
php artisan make:controller Api/V1/Customer/ProfileController
php artisan make:controller Api/V1/Customer/CartController --api
php artisan make:controller Api/V1/Customer/OrderController --api
php artisan make:controller Api/V1/Customer/AddressController --api

# Public Controllers
php artisan make:controller Api/V1/Public/RestaurantController
php artisan make:controller Api/V1/Public/MenuController
```

#### 5. Set Up API Routes
Create `routes/api/v1/admin.php`:
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin;

// Authentication routes
Route::post('login', [Admin\AuthController::class, 'login']);
Route::post('register', [Admin\AuthController::class, 'register']);

Route::middleware(['auth:admin-api'])->group(function () {
    // Auth
    Route::post('logout', [Admin\AuthController::class, 'logout']);
    Route::get('profile', [Admin\AuthController::class, 'profile']);
    
    // Dashboard
    Route::get('dashboard', [Admin\DashboardController::class, 'index']);
    
    // Menu Management
    Route::apiResource('categories', Admin\MenuCategoryController::class);
    Route::apiResource('menu-items', Admin\MenuItemController::class);
    
    // Order Management
    Route::apiResource('orders', Admin\OrderController::class);
    Route::post('orders/{order}/status', [Admin\OrderController::class, 'updateStatus']);
});
```

Create `routes/api/v1/customer.php`:
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Customer;

// Authentication routes
Route::post('login', [Customer\AuthController::class, 'login']);
Route::post('register', [Customer\AuthController::class, 'register']);

Route::middleware(['auth:customer-api'])->group(function () {
    // Auth
    Route::post('logout', [Customer\AuthController::class, 'logout']);
    Route::get('profile', [Customer\ProfileController::class, 'show']);
    Route::put('profile', [Customer\ProfileController::class, 'update']);
    
    // Addresses
    Route::apiResource('addresses', Customer\AddressController::class);
    
    // Cart
    Route::get('cart', [Customer\CartController::class, 'index']);
    Route::post('cart/items', [Customer\CartController::class, 'addItem']);
    Route::put('cart/items/{item}', [Customer\CartController::class, 'updateItem']);
    Route::delete('cart/items/{item}', [Customer\CartController::class, 'removeItem']);
    Route::delete('cart/clear', [Customer\CartController::class, 'clear']);
    
    // Orders
    Route::get('orders', [Customer\OrderController::class, 'index']);
    Route::post('orders', [Customer\OrderController::class, 'store']);
    Route::get('orders/{order}', [Customer\OrderController::class, 'show']);
});
```

## 🚀 Day 4: Implement Core Features

### Morning Session (3-4 hours)
#### 1. Implement Admin Authentication Controller
Edit `app/Http/Controllers/Api/V1/Admin/AuthController.php`:
```php
<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            // Restaurant details
            'restaurant_name' => 'required|string|max:255',
            'subdomain' => 'required|string|unique:restaurants|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            'restaurant_email' => 'required|email',
            'restaurant_phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            
            // Admin details
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins',
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => 'nullable|string',
        ]);

        // Create restaurant
        $restaurant = Restaurant::create([
            'name' => $validated['restaurant_name'],
            'slug' => str()->slug($validated['restaurant_name']),
            'subdomain' => $validated['subdomain'],
            'email' => $validated['restaurant_email'],
            'phone' => $validated['restaurant_phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'postal_code' => $validated['postal_code'],
            'password' => Hash::make($validated['password']),
        ]);

        // Create admin
        $admin = Admin::create([
            'restaurant_id' => $restaurant->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'role' => 'owner',
        ]);

        // Create token
        $token = $admin->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Restaurant registered successfully',
            'data' => [
                'restaurant' => $restaurant,
                'admin' => $admin,
                'token' => $token,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $validated['email'])->first();

        if (!$admin || !Hash::check($validated['password'], $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$admin->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Your account has been suspended.'],
            ]);
        }

        // Update last login
        $admin->update(['last_login_at' => now()]);

        // Create token
        $token = $admin->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'admin' => $admin,
                'restaurant' => $admin->restaurant,
                'token' => $token,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'admin' => $request->user(),
                'restaurant' => $request->user()->restaurant,
            ],
        ]);
    }
}
```

### Afternoon Session (3-4 hours)
#### 2. Create Base API Controller with Response Trait
Create `app/Traits/HasApiResponse.php`:
```php
<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

trait HasApiResponse
{
    protected function success($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function error(string $message = 'Error', int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    protected function paginated(LengthAwarePaginator $paginator, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ]);
    }

    protected function resource($resource, string $message = 'Success'): JsonResponse
    {
        if ($resource instanceof JsonResource) {
            return $this->success($resource->resolve(), $message);
        }

        return $this->success($resource, $message);
    }
}
```

## 🎯 Quick Command Reference

### Database Commands
```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration with seed
php artisan migrate:fresh --seed

# Create seeder
php artisan make:seeder RestaurantSeeder
```

### Testing Commands
```bash
# Create test
php artisan make:test Api/RestaurantRegistrationTest
php artisan make:test Api/MenuManagementTest

# Run tests
php artisan test
php artisan test --filter RestaurantRegistrationTest
```

### Cache Commands
```bash
# Clear all cache
php artisan optimize:clear

# Cache config and routes
php artisan optimize
```

## 📝 Environment Configuration

Add to `.env`:
```env
# App Domain (for subdomain routing)
APP_DOMAIN=yourdomain.com

# Sanctum Configuration
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1,yourdomain.com,*.yourdomain.com

# Session Configuration for Multi-tenancy
SESSION_DOMAIN=.yourdomain.com

# Queue Configuration (for order processing)
QUEUE_CONNECTION=database

# Mail Configuration (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Payment Gateway (prepare for future)
STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=

# SMS Gateway (prepare for future)
TWILIO_SID=
TWILIO_TOKEN=
TWILIO_FROM=

# Storage
FILESYSTEM_DISK=public
```

## 🔄 Next Steps Priority

### Week 1 Completion Checklist:
- [ ] All migrations created and tested
- [ ] Models with relationships set up
- [ ] Multi-tenancy middleware working
- [ ] Admin authentication complete
- [ ] Customer authentication complete
- [ ] Basic API structure in place

### Week 2 Focus:
- [ ] Menu management CRUD
- [ ] Cart functionality
- [ ] Order placement
- [ ] Restaurant dashboard

### Week 3 Focus:
- [ ] Order tracking
- [ ] Payment integration
- [ ] Notification system
- [ ] Review system

### Week 4 Focus:
- [ ] Performance optimization
- [ ] Security audit
- [ ] API documentation
- [ ] Deployment preparation

## 🚨 Important Notes

1. **Always test multi-tenancy isolation** - Create multiple restaurants and verify data separation
2. **Use database transactions** for critical operations (orders, payments)
3. **Implement rate limiting** early to prevent abuse
4. **Log all critical actions** (orders, payments, refunds)
5. **Set up monitoring** (Laravel Telescope in development)
6. **Document API endpoints** as you build them
7. **Write tests** for critical paths (auth, orders, payments)

## 💡 Development Tips

1. **Use Postman/Insomnia** to test APIs as you build
2. **Set up Git hooks** for code formatting
3. **Use Laravel Telescope** to debug in development
4. **Implement soft deletes** on critical models
5. **Use UUIDs** for public-facing IDs (order numbers)
6. **Cache menu items** aggressively (they don't change often)
7. **Use queues** for heavy operations (sending emails, processing images)

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Sanctum Documentation](https://laravel.com/docs/sanctum)
- [Spatie Packages](https://spatie.be/open-source/packages)
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [RESTful API Design](https://restfulapi.net/)

## 🆘 Common Issues & Solutions

### Issue: Subdomain not working locally
**Solution**: Edit your hosts file:
```
127.0.0.1 restaurant1.localhost
127.0.0.1 restaurant2.localhost
```

### Issue: CORS errors with Sanctum
**Solution**: Configure `config/cors.php`:
```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['http://localhost:3000'],
'supports_credentials' => true,
```

### Issue: Restaurant context not set
**Solution**: Ensure middleware order is correct and check if restaurant exists

### Issue: Token authentication not working
**Solution**: Ensure you're sending token in Authorization header:
```
Authorization: Bearer YOUR_TOKEN_HERE
```

## 🎉 Success Indicators

You know you're on track when:
1. ✅ You can register a restaurant and get a subdomain
2. ✅ Admin can log in and get authenticated
3. ✅ Customer can register and log in
4. ✅ Data is properly isolated between restaurants
5. ✅ API responses are consistent
6. ✅ Basic CRUD operations work for menu items
7. ✅ Cart functionality works
8. ✅ Orders can be placed

Remember: **Build incrementally, test frequently, and maintain clean code!**
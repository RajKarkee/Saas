# Multi-Tenant Food Delivery SaaS - Complete Implementation Plan

## 🎯 Project Overview
Building a Shopify-like platform for restaurants with food ordering capabilities, where each restaurant gets their own subdomain/custom domain.

## 📋 System Requirements

### Core Features
1. **Multi-Tenant Architecture** - Complete isolation between restaurants
2. **Dual Authentication System** - Separate auth for restaurant admins and customers
3. **Domain Management** - Subdomain and custom domain support
4. **Menu Management** - Categories, items, modifiers, pricing
5. **Order Management** - Cart, checkout, order tracking
6. **Dashboard Systems** - Admin analytics and customer order history
7. **API-First Architecture** - RESTful API with Sanctum authentication

## 🏗️ Architecture Design

### Database Design Pattern
```
Tenant Identification: restaurant_id (indexed on all tables)
Isolation: Row-level security with global scopes
Performance: Composite indexes on (restaurant_id, primary_key)
```

### Authentication Architecture
```
Guards:
- admin (Restaurant owners/staff)
- customer (End users)
- superadmin (Platform administrators)

Providers:
- admins → Admin model
- customers → Customer model
- users → User model (superadmin)
```

## 📊 Complete Database Schema

### Phase 1: Core Tables

#### 1. restaurants (Updated)
```sql
- id (bigint, primary)
- name (string)
- slug (string, unique, indexed)
- email (string)
- phone (string)
- subdomain (string, unique, nullable, indexed)
- custom_domain (string, unique, nullable, indexed)
- logo (string, nullable)
- cover_image (string, nullable)
- description (text, nullable)
- address (text)
- city (string)
- state (string)
- country (string)
- postal_code (string)
- latitude (decimal, nullable)
- longitude (decimal, nullable)
- currency (string, default: 'USD')
- timezone (string, default: 'UTC')
- opening_hours (json, nullable)
- delivery_radius (integer, default: 10)
- minimum_order (decimal, default: 0)
- delivery_fee (decimal, default: 0)
- tax_rate (decimal, default: 0)
- is_active (boolean, default: true)
- is_accepting_orders (boolean, default: true)
- setup_completed (boolean, default: false)
- subscription_plan (enum: ['free', 'basic', 'premium', 'enterprise'])
- subscription_ends_at (timestamp, nullable)
- settings (json, nullable)
- created_at
- updated_at
- deleted_at (soft delete)

Indexes:
- subdomain
- custom_domain
- slug
- is_active
```

#### 2. admins (Restaurant Staff)
```sql
- id (bigint, primary)
- restaurant_id (bigint, foreign, indexed)
- name (string)
- email (string, unique)
- password (string)
- phone (string, nullable)
- role (enum: ['owner', 'manager', 'staff'], default: 'staff')
- permissions (json, nullable)
- is_active (boolean, default: true)
- last_login_at (timestamp, nullable)
- email_verified_at (timestamp, nullable)
- remember_token (string, nullable)
- created_at
- updated_at

Indexes:
- (restaurant_id, email) composite unique
- restaurant_id
- email
```

#### 3. customers
```sql
- id (bigint, primary)
- name (string)
- email (string, unique, indexed)
- phone (string, nullable, indexed)
- password (string)
- email_verified_at (timestamp, nullable)
- phone_verified_at (timestamp, nullable)
- date_of_birth (date, nullable)
- gender (enum: ['male', 'female', 'other'], nullable)
- profile_picture (string, nullable)
- referral_code (string, unique, nullable)
- referred_by (bigint, nullable, foreign)
- loyalty_points (integer, default: 0)
- is_active (boolean, default: true)
- last_login_at (timestamp, nullable)
- preferences (json, nullable)
- remember_token (string, nullable)
- created_at
- updated_at

Indexes:
- email
- phone
- referral_code
```

### Phase 2: Menu Management Tables

#### 4. menu_categories
```sql
- id (bigint, primary)
- restaurant_id (bigint, foreign, indexed)
- name (string)
- slug (string)
- description (text, nullable)
- image (string, nullable)
- parent_id (bigint, nullable, self-referencing)
- display_order (integer, default: 0)
- is_active (boolean, default: true)
- available_from (time, nullable)
- available_until (time, nullable)
- available_days (json, nullable) // ['monday', 'tuesday', ...]
- created_at
- updated_at

Indexes:
- (restaurant_id, slug) composite unique
- restaurant_id
- parent_id
- display_order
```

#### 5. menu_items
```sql
- id (bigint, primary)
- restaurant_id (bigint, foreign, indexed)
- category_id (bigint, foreign, indexed)
- name (string)
- slug (string)
- description (text, nullable)
- image (string, nullable)
- images (json, nullable) // Additional images
- price (decimal(10,2))
- discounted_price (decimal(10,2), nullable)
- tax_rate (decimal(5,2), default: 0)
- preparation_time (integer) // in minutes
- calories (integer, nullable)
- serving_size (string, nullable)
- ingredients (text, nullable)
- allergens (json, nullable)
- tags (json, nullable) // ['spicy', 'vegan', 'gluten-free']
- is_vegetarian (boolean, default: false)
- is_vegan (boolean, default: false)
- is_gluten_free (boolean, default: false)
- is_featured (boolean, default: false)
- is_available (boolean, default: true)
- stock_quantity (integer, nullable) // null = unlimited
- max_quantity_per_order (integer, default: 10)
- display_order (integer, default: 0)
- available_from (time, nullable)
- available_until (time, nullable)
- available_days (json, nullable)
- created_at
- updated_at

Indexes:
- (restaurant_id, slug) composite unique
- restaurant_id
- category_id
- is_available
- is_featured
```

#### 6. menu_item_modifiers
```sql
- id (bigint, primary)
- restaurant_id (bigint, foreign, indexed)
- menu_item_id (bigint, foreign, indexed)
- name (string)
- type (enum: ['required', 'optional'])
- min_selections (integer, default: 0)
- max_selections (integer, default: 1)
- display_order (integer, default: 0)
- created_at
- updated_at

Indexes:
- restaurant_id
- menu_item_id
```

#### 7. menu_item_modifier_options
```sql
- id (bigint, primary)
- restaurant_id (bigint, foreign, indexed)
- modifier_id (bigint, foreign, indexed)
- name (string)
- price (decimal(10,2), default: 0)
- is_default (boolean, default: false)
- is_available (boolean, default: true)
- display_order (integer, default: 0)
- created_at
- updated_at

Indexes:
- restaurant_id
- modifier_id
```

### Phase 3: Order Management Tables

#### 8. carts
```sql
- id (bigint, primary)
- restaurant_id (bigint, foreign, indexed)
- customer_id (bigint, foreign, indexed, nullable)
- session_id (string, indexed, nullable) // For guest users
- subtotal (decimal(10,2), default: 0)
- tax (decimal(10,2), default: 0)
- delivery_fee (decimal(10,2), default: 0)
- discount (decimal(10,2), default: 0)
- total (decimal(10,2), default: 0)
- coupon_code (string, nullable)
- notes (text, nullable)
- expires_at (timestamp)
- created_at
- updated_at

Indexes:
- restaurant_id
- customer_id
- session_id
- expires_at
```

#### 9. cart_items
```sql
- id (bigint, primary)
- restaurant_id (bigint, foreign, indexed)
- cart_id (bigint, foreign, indexed)
- menu_item_id (bigint, foreign, indexed)
- quantity (integer)
- unit_price (decimal(10,2))
- modifiers (json, nullable) // Selected modifiers with prices
- special_instructions (text, nullable)
- subtotal (decimal(10,2))
- created_at
- updated_at

Indexes:
- restaurant_id
- cart_id
- menu_item_id
```

#### 10. orders
```sql
- id (bigint, primary)
- restaurant_id (bigint, foreign, indexed)
- customer_id (bigint, foreign, indexed, nullable)
- order_number (string, unique, indexed)
- type (enum: ['delivery', 'pickup', 'dine_in'])
- status (enum: ['pending', 'confirmed', 'preparing', 'ready', 'out_for_delivery', 'delivered', 'completed', 'cancelled', 'refunded'])
- payment_status (enum: ['pending', 'paid', 'failed', 'refunded'])
- payment_method (enum: ['cash', 'card', 'wallet', 'online'])
- subtotal (decimal(10,2))
- tax (decimal(10,2))
- delivery_fee (decimal(10,2))
- service_fee (decimal(10,2), default: 0)
- discount (decimal(10,2), default: 0)
- tip (decimal(10,2), default: 0)
- total (decimal(10,2))
- coupon_code (string, nullable)
- customer_name (string)
- customer_email (string)
- customer_phone (string)
- delivery_address (json, nullable)
- delivery_latitude (decimal, nullable)
- delivery_longitude (decimal, nullable)
- delivery_distance (decimal, nullable)
- estimated_delivery_time (timestamp, nullable)
- actual_delivery_time (timestamp, nullable)
- preparation_time (integer) // minutes
- driver_id (bigint, nullable)
- driver_name (string, nullable)
- driver_phone (string, nullable)
- notes (text, nullable)
- kitchen_notes (text, nullable)
- rejection_reason (text, nullable)
- rating (integer, nullable) // 1-5
- feedback (text, nullable)
- is_asap (boolean, default: true)
- scheduled_at (timestamp, nullable)
- confirmed_at (timestamp, nullable)
- prepared_at (timestamp, nullable)
- delivered_at (timestamp, nullable)
- cancelled_at (timestamp, nullable)
- created_at
- updated_at

Indexes:
- restaurant_id
- customer_id
- order_number
- status
- payment_status
- type
- created_at
- scheduled_at
```

#### 11. order_items
```sql
- id (bigint, primary)
- restaurant_id (bigint, foreign, indexed)
- order_id (bigint, foreign, indexed)
- menu_item_id (bigint, foreign, indexed)
- name (string) // Store name at time of order
- quantity (integer)
- unit_price (decimal(10,2))
- modifiers (json, nullable)
- special_instructions (text, nullable)
- subtotal (decimal(10,2))
- created_at
- updated_at

Indexes:
- restaurant_id
- order_id
- menu_item_id
```

### Phase 4: Supporting Tables

#### 12. addresses
```sql
- id (bigint, primary)
- customer_id (bigint, foreign, indexed)
- type (enum: ['home', 'work', 'other'])
- is_default (boolean, default: false)
- address_line_1 (string)
- address_line_2 (string, nullable)
- city (string)
- state (string)
- country (string)
- postal_code (string)
- latitude (decimal, nullable)
- longitude (decimal, nullable)
- delivery_instructions (text, nullable)
- created_at
- updated_at

Indexes:
- customer_id
- is_default
```

#### 13. coupons
```sql
- id (bigint, primary)
- restaurant_id (bigint, foreign, indexed)
- code (string, unique, indexed)
- description (string)
- type (enum: ['percentage', 'fixed'])
- value (decimal(10,2))
- minimum_order (decimal(10,2), default: 0)
- maximum_discount (decimal(10,2), nullable)
- usage_limit (integer, nullable)
- usage_count (integer, default: 0)
- customer_usage_limit (integer, default: 1)
- applicable_items (json, nullable) // Specific menu items
- applicable_categories (json, nullable)
- is_active (boolean, default: true)
- starts_at (timestamp, nullable)
- expires_at (timestamp, nullable)
- created_at
- updated_at

Indexes:
- restaurant_id
- code
- is_active
- starts_at
- expires_at
```

#### 14. reviews
```sql
- id (bigint, primary)
- restaurant_id (bigint, foreign, indexed)
- order_id (bigint, foreign, indexed)
- customer_id (bigint, foreign, indexed)
- rating (integer) // 1-5
- food_rating (integer, nullable)
- delivery_rating (integer, nullable)
- comment (text, nullable)
- reply (text, nullable)
- replied_at (timestamp, nullable)
- is_featured (boolean, default: false)
- created_at
- updated_at

Indexes:
- restaurant_id
- order_id
- customer_id
- rating
```

## 🚀 Implementation Phases

### Phase 1: Foundation (Week 1)
1. **Day 1-2: Database & Models**
   - Create all migrations
   - Set up models with relationships
   - Implement global scopes for multi-tenancy

2. **Day 3-4: Authentication System**
   - Configure multi-guard authentication
   - Set up Sanctum for API
   - Create auth controllers

3. **Day 5-7: Multi-Tenancy Core**
   - Implement tenant identification middleware
   - Set up subdomain routing
   - Create tenant context management

### Phase 2: Restaurant Management (Week 2)
1. **Day 8-9: Restaurant Onboarding**
   - Registration API
   - Domain setup
   - Initial configuration wizard

2. **Day 10-11: Admin Dashboard APIs**
   - Restaurant settings
   - Staff management
   - Business hours configuration

3. **Day 12-14: Menu Management**
   - Categories CRUD
   - Menu items CRUD
   - Modifiers system

### Phase 3: Customer Experience (Week 3)
1. **Day 15-16: Customer Auth & Profile**
   - Registration/Login
   - Profile management
   - Address management

2. **Day 17-18: Menu Browsing**
   - Public menu API
   - Search and filters
   - Item details with modifiers

3. **Day 19-21: Cart System**
   - Add to cart with modifiers
   - Update quantities
   - Apply coupons

### Phase 4: Order Processing (Week 4)
1. **Day 22-23: Checkout Process**
   - Order validation
   - Payment integration prep
   - Order placement

2. **Day 24-25: Order Management**
   - Status updates
   - Real-time tracking
   - Kitchen management

3. **Day 26-28: Notifications & Reviews**
   - Order notifications
   - Review system
   - Email/SMS integration

## 🛠️ Technical Implementation Details

### 1. Tenant Identification Middleware
```php
// app/Http/Middleware/IdentifyTenant.php
class IdentifyTenant
{
    public function handle($request, Closure $next)
    {
        // 1. Check subdomain
        // 2. Check custom domain
        // 3. Set restaurant context
        // 4. Apply global scope
    }
}
```

### 2. Global Scope for Multi-Tenancy
```php
// app/Traits/BelongsToRestaurant.php
trait BelongsToRestaurant
{
    protected static function bootBelongsToRestaurant()
    {
        // Add global scope
        // Auto-set restaurant_id on create
        // Validate restaurant context
    }
}
```

### 3. API Versioning Structure
```
routes/api/
├── v1/
│   ├── admin.php
│   ├── customer.php
│   └── public.php
```

### 4. Response Format Standardization
```json
{
    "success": true,
    "data": {},
    "message": "Success",
    "errors": null,
    "meta": {
        "pagination": {}
    }
}
```

## 📁 Directory Structure

```
app/
├── Models/
│   ├── Restaurant.php
│   ├── Admin.php
│   ├── Customer.php
│   ├── Menu/
│   │   ├── Category.php
│   │   ├── MenuItem.php
│   │   ├── Modifier.php
│   │   └── ModifierOption.php
│   ├── Order/
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Cart.php
│   │   └── CartItem.php
│   └── Support/
│       ├── Address.php
│       ├── Coupon.php
│       └── Review.php
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── V1/
│   │   │   │   ├── Admin/
│   │   │   │   │   ├── AuthController.php
│   │   │   │   │   ├── MenuController.php
│   │   │   │   │   ├── OrderController.php
│   │   │   │   │   └── DashboardController.php
│   │   │   │   ├── Customer/
│   │   │   │   │   ├── AuthController.php
│   │   │   │   │   ├── CartController.php
│   │   │   │   │   ├── OrderController.php
│   │   │   │   │   └── ProfileController.php
│   │   │   │   └── Public/
│   │   │   │       ├── RestaurantController.php
│   │   │   │       └── MenuController.php
│   ├── Middleware/
│   │   ├── IdentifyTenant.php
│   │   ├── EnsureTenantSession.php
│   │   └── ValidateRestaurantStatus.php
│   └── Resources/
│       ├── Admin/
│       ├── Customer/
│       └── Public/
├── Services/
│   ├── TenantService.php
│   ├── OrderService.php
│   ├── CartService.php
│   └── PaymentService.php
├── Repositories/
│   ├── RestaurantRepository.php
│   ├── MenuRepository.php
│   └── OrderRepository.php
└── Traits/
    ├── BelongsToRestaurant.php
    └── HasApiResponse.php
```

## 🔐 Security Best Practices

1. **Tenant Isolation**
   - Always verify restaurant_id in queries
   - Use composite unique constraints
   - Implement row-level security

2. **API Security**
   - Rate limiting per tenant
   - API versioning
   - Request validation
   - CORS configuration for custom domains

3. **Authentication**
   - Separate token tables for each guard
   - Token expiration policies
   - Password complexity requirements
   - 2FA for restaurant admins

4. **Data Protection**
   - Encrypt sensitive data
   - PCI compliance for payments
   - GDPR compliance for customer data
   - Regular security audits

## 📊 Performance Optimization

1. **Database**
   - Composite indexes on (restaurant_id, frequently_queried_column)
   - Partitioning for large tables
   - Query optimization with eager loading

2. **Caching Strategy**
   - Cache menu items per restaurant
   - Redis for session management
   - CDN for static assets

3. **API Optimization**
   - Pagination for list endpoints
   - Selective field returns
   - Response compression

## 🧪 Testing Strategy

1. **Unit Tests**
   - Model relationships
   - Service layer logic
   - Trait functionality

2. **Feature Tests**
   - API endpoints
   - Authentication flows
   - Multi-tenancy isolation

3. **Integration Tests**
   - Payment processing
   - Order workflow
   - Notification delivery

## 📈 Monitoring & Analytics

1. **Application Monitoring**
   - Laravel Telescope for debugging
   - Error tracking with Sentry
   - Performance monitoring with New Relic

2. **Business Metrics**
   - Orders per restaurant
   - Revenue tracking
   - Customer acquisition cost
   - Menu item performance

## 🚦 Deployment Checklist

1. **Environment Setup**
   - Production database
   - Redis server
   - Queue workers
   - SSL certificates

2. **Configuration**
   - Environment variables
   - Cache configuration
   - Queue configuration
   - Mail configuration

3. **Domain Management**
   - Wildcard SSL for subdomains
   - DNS configuration for custom domains
   - CDN setup

## 📝 API Documentation

Use Laravel Scribe or Swagger for auto-generated API documentation covering:
- Authentication endpoints
- Restaurant management
- Menu operations
- Order processing
- Customer operations

## 🎯 Success Metrics

1. **Technical KPIs**
   - API response time < 200ms
   - 99.9% uptime
   - Zero tenant data leaks

2. **Business KPIs**
   - Restaurant onboarding time < 30 minutes
   - Order processing time < 2 minutes
   - Customer retention rate > 60%

## 🔄 Next Steps After Implementation

1. **Advanced Features**
   - Loyalty programs
   - Subscription plans
   - Analytics dashboard
   - Mobile applications

2. **Integrations**
   - Payment gateways (Stripe, PayPal)
   - Delivery partners (DoorDash, Uber Eats)
   - POS systems
   - Accounting software

3. **Scaling Considerations**
   - Database sharding
   - Microservices architecture
   - Multi-region deployment
   - Real-time features with WebSockets
# Laravel SaaS Codebase Index

## Project Overview
- **Framework**: Laravel 12.0
- **PHP Version**: ^8.2
- **Frontend**: Tailwind CSS 4.0 + Vite
- **Database**: SQLite (development)
- **Type**: Multi-tenant SaaS for Restaurant Management

## Directory Structure

### Root Files
- `composer.json` - PHP dependencies and autoloading
- `package.json` - Node.js dependencies (Vite, Tailwind)
- `.env` - Environment configuration
- `artisan` - Laravel CLI
- `vite.config.js` - Vite configuration

### `/app` - Application Logic
```
app/
├── Http/
│   └── Controllers/
│       └── Controller.php - Base controller
├── Models/
│   ├── Restaurants.php - Restaurant model (empty)
│   └── User.php - User authentication model
└── Providers/
    └── AppServiceProvider.php - Service provider
```

### `/bootstrap` - Framework Bootstrap
```
bootstrap/
├── app.php - Application bootstrap
├── providers.php - Service providers registration
└── cache/ - Cached framework files
```

### `/config` - Configuration Files
- `app.php` - Application settings
- `auth.php` - Authentication guards/providers
- `cache.php` - Cache configuration
- `database.php` - Database connections
- `filesystems.php` - Storage disks
- `logging.php` - Log channels
- `mail.php` - Mail drivers
- `queue.php` - Queue connections
- `services.php` - Third-party services
- `session.php` - Session configuration

### `/database` - Database Files
```
database/
├── factories/
│   └── UserFactory.php - User model factory
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 0001_01_01_000001_create_cache_table.php
│   ├── 0001_01_01_000002_create_jobs_table.php
│   └── 2025_10_12_172640_create_restaurants_table.php
└── seeders/
    └── DatabaseSeeder.php
```

### `/public` - Public Assets
```
public/
├── css/
│   └── index.css - Custom CSS
├── .htaccess - Apache configuration
├── favicon.ico
├── index.php - Application entry point
└── robots.txt
```

### `/resources` - Source Assets & Views
```
resources/
├── css/
│   └── app.css - Main application CSS
├── js/
│   ├── app.js - Main JavaScript
│   └── bootstrap.js - JS bootstrap
└── views/
    └── restaurant/
        ├── layout/
        │   ├── navbar.blade.php
        │   └── sidebar.blade.php
        └── welcome.blade.php
```

### `/routes` - Application Routes
- `web.php` - Web routes (currently only home route)
- `console.php` - Artisan commands

### `/storage` - Application Storage
```
storage/
├── app/ - Application files
├── framework/ - Framework cache/sessions
└── logs/ - Application logs
```

### `/tests` - Test Suite
```
tests/
├── Feature/
│   └── ExampleTest.php
├── Unit/
│   └── ExampleTest.php
└── TestCase.php
```

## Database Schema

### Users Table
- `id` (primary key)
- `name`
- `email` (unique)
- `email_verified_at`
- `password`
- `remember_token`
- `created_at`
- `updated_at`

### Restaurants Table
- `id` (primary key)
- `name`
- `domain`
- `subdomain` (nullable)
- `email` (unique)
- `password`
- `status` (enum: 'active', 'inactive', default: 'active')
- `created_at`
- `updated_at`
- **Indexes**: compound index on (domain, subdomain)

## Key Dependencies

### PHP Packages
- **laravel/framework**: ^12.0 - Core framework
- **laravel/tinker**: ^2.10.1 - REPL for Laravel
- **mews/purifier**: ^3.4 - HTML purifier
- **laravel/breeze**: ^2.3 (dev) - Authentication scaffolding

### JavaScript Packages
- **vite**: ^7.0.4 - Build tool
- **tailwindcss**: ^4.0.0 - CSS framework
- **laravel-vite-plugin**: ^2.0.0 - Laravel Vite integration
- **axios**: ^1.8.2 - HTTP client

## Application Routes

### Web Routes (`routes/web.php`)
- `GET /` - Returns `restaurant.welcome` view

## Models

### User Model
- **Traits**: HasFactory, Notifiable
- **Fillable**: name, email, password
- **Hidden**: password, remember_token
- **Casts**: email_verified_at (datetime), password (hashed)

### Restaurants Model
- Currently empty (no relationships or attributes defined)

## Views Structure
- **Layout Components**:
  - `navbar.blade.php` - Navigation bar
  - `sidebar.blade.php` - Sidebar navigation
- **Pages**:
  - `welcome.blade.php` - Home page

## Development Commands

### Composer Scripts
- `composer dev` - Runs all development servers concurrently
- `composer test` - Runs the test suite

### NPM Scripts
- `npm run dev` - Start Vite development server
- `npm run build` - Build production assets

## Configuration Notes
- Using SQLite for development database
- Session driver configured for file storage
- Cache driver set to file system
- Queue connection set to sync (no queue worker needed)

## Project Status
- Basic Laravel 12 installation with multi-tenant restaurant structure
- Authentication scaffolding available (Laravel Breeze)
- Restaurant multi-tenancy model established but not fully implemented
- Basic view structure with layout components

## Next Steps Recommendations
1. Complete Restaurants model with relationships and attributes
2. Implement multi-tenant middleware for subdomain routing
3. Create authentication controllers for restaurants
4. Build restaurant dashboard views
5. Implement tenant isolation in database queries
6. Add restaurant-specific configurations
7. Create API endpoints if needed
8. Set up proper testing for multi-tenancy

## File Count Summary
- PHP Files: ~30 (excluding vendor)
- Blade Templates: 3
- JavaScript Files: 2
- CSS Files: 2
- Migration Files: 4
- Configuration Files: 10
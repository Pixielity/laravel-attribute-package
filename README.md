# Laravel Attribute Collector

A Laravel package that leverages the power of `olvlvl/composer-attribute-collector` to provide attribute-based functionality for Laravel applications. This package allows you to use PHP 8+ attributes for defining routes, event listeners, scheduled jobs, middleware, caching, validation, authorization, and more with zero runtime reflection cost.

## 🚀 Features

- **Zero Runtime Cost** - Uses composer-attribute-collector for compile-time attribute discovery
- **Attribute-based Routing** - Define routes using PHP 8 attributes
- **Event Listeners** - Register event listeners with attributes
- **Scheduled Jobs** - Define scheduled tasks using attributes
- **Method Caching** - Cache method results with attributes
- **Validation** - Apply validation rules using attributes
- **Authorization** - Define authorization policies with attributes
- **Rate Limiting** - Apply rate limits using attributes
- **Logging** - Add method logging with attributes
- **Middleware** - Apply middleware using attributes
- **Extensible Architecture** - Easy to add custom attribute handlers following ISP principles
- **Laravel Integration** - Seamless integration with Laravel's service container

## 📋 Requirements

- PHP 8.1+
- Laravel 9.0+ | 10.0+ | 11.0+
- Composer 2.0+

## 📦 Installation

Install the package via Composer:

```bash
composer require pixielity/laravel-attribute-collector
```

The package will automatically register its service provider.

Publish the configuration file (optional):

```bash
php artisan vendor:publish --provider="Pixielity\LaravelAttributeCollector\LaravelAttributeCollectorServiceProvider" --tag="config"
```

## ⚙️ Configuration

The package automatically configures `composer-attribute-collector` to scan your `app` and `src` directories. You can customize this in your `composer.json`:

```json
{
    "extra": {
        "composer-attribute-collector": {
            "include": [
                "app",
                "src",
                "packages"
            ]
        }
    }
}
```

Configuration options in `config/attribute-collector.php`:

```php
return [
    'enabled' => env('ATTRIBUTE_COLLECTOR_ENABLED', true),
    'cache_enabled' => env('ATTRIBUTE_CACHE_ENABLED', true),
    'handlers' => [
        // Custom handler configurations
    ],
];
```

## 🎯 Usage

### Route Attributes

Define routes using attributes instead of traditional route files:

```php
<?php

namespace App\Http\Controllers;

use Pixielity\LaravelAttributeCollector\Attributes\Route;
use Pixielity\LaravelAttributeCollector\Attributes\Middleware;
use Pixielity\LaravelAttributeCollector\Attributes\RateLimit;

#[Middleware('auth')]
class UserController
{
    #[Route::get('/users', name: 'users.index')]
    #[Middleware('can:view-users')]
    #[RateLimit(60, 1)] // 60 requests per minute
    public function index()
    {
        return User::all();
    }

    #[Route::post('/users', name: 'users.store')]
    #[Middleware(['throttle:60,1', 'can:create-users'])]
    #[Validate(['name' => 'required|string', 'email' => 'required|email'])]
    public function store(Request $request)
    {
        return User::create($request->validated());
    }

    #[Route::get('/users/{id}', name: 'users.show', where: ['id' => '[0-9]+'])]
    #[Cache(ttl: 3600, tags: ['users'])]
    public function show(User $user)
    {
        return $user;
    }
}
```

### Event Listener Attributes

Register event listeners using attributes:

```php
<?php

namespace App\Listeners;

use Pixielity\LaravelAttributeCollector\Attributes\Listen;
use Pixielity\LaravelAttributeCollector\Attributes\Log;

class UserEventListener
{
    #[Listen(UserRegistered::class, queue: 'emails')]
    #[Log(level: 'info', message: 'Processing user registration')]
    public function handleUserRegistered(UserRegistered $event)
    {
        // Send welcome email
    }

    #[Listen(['user.login', 'user.logout'], queue: 'analytics')]
    public function handleUserActivity($event)
    {
        // Log user activity
    }
}
```

### Scheduled Job Attributes

Define scheduled tasks using attributes:

```php
<?php

namespace App\Jobs;

use Pixielity\LaravelAttributeCollector\Attributes\Schedule;
use Pixielity\LaravelAttributeCollector\Attributes\Log;

class MaintenanceJobs
{
    #[Schedule::daily(timezone: 'UTC', description: 'Clean up old logs')]
    #[Log(level: 'info', message: 'Running daily log cleanup')]
    public function cleanupLogs()
    {
        // Cleanup logic
    }

    #[Schedule('0 */6 * * *', withoutOverlapping: true)]
    public function processOrders()
    {
        // Process orders every 6 hours
    }
}
```

### Caching Attributes

Cache method results automatically:

```php
<?php

namespace App\Services;

use Pixielity\LaravelAttributeCollector\Attributes\Cache;

class DataService
{
    #[Cache(ttl: 3600, key: 'expensive_calculation_{id}')]
    public function expensiveCalculation(int $id): array
    {
        // Expensive operation that will be cached
        return $this->performCalculation($id);
    }

    #[Cache(ttl: 1800, tags: ['reports', 'analytics'])]
    public function generateReport(): array
    {
        // Report generation cached with tags
        return $this->buildReport();
    }
}
```

### Authorization Attributes

Define authorization policies:

```php
<?php

namespace App\Http\Controllers;

use Pixielity\LaravelAttributeCollector\Attributes\Authorize;
use Pixielity\LaravelAttributeCollector\Attributes\Route;

class AdminController
{
    #[Route::get('/admin/users')]
    #[Authorize('admin')]
    public function users()
    {
        return User::all();
    }

    #[Route::delete('/admin/users/{user}')]
    #[Authorize('delete', 'user')]
    public function deleteUser(User $user)
    {
        $user->delete();
        return response()->json(['success' => true]);
    }
}
```

## 📚 Available Attributes

### Route Attribute
- `Route::get(string $uri, ?string $name = null, array $middleware = [], ?string $domain = null, array $where = [])`
- `Route::post(string $uri, ...)` - POST routes
- `Route::put(string $uri, ...)` - PUT routes
- `Route::patch(string $uri, ...)` - PATCH routes
- `Route::delete(string $uri, ...)` - DELETE routes
- `Route(string $method, string $uri, ...)` - Generic route attribute

### Listen Attribute
- `Listen(string|array $events, ?string $queue = null, int $tries = 1, int $timeout = 60)`

### Schedule Attribute
- `Schedule(string $expression, ?string $timezone = null, bool $withoutOverlapping = false, bool $runInBackground = false, ?string $description = null)`
- `Schedule::daily(?string $timezone = null)` - Daily execution
- `Schedule::hourly(?string $timezone = null)` - Hourly execution
- `Schedule::everyMinute(?string $timezone = null)` - Every minute
- `Schedule::weekly(?string $timezone = null)` - Weekly execution
- `Schedule::monthly(?string $timezone = null)` - Monthly execution

### Cache Attribute
- `Cache(int $ttl = 3600, ?string $key = null, array $tags = [], ?string $store = null)`

### Validate Attribute
- `Validate(array $rules, array $messages = [], array $attributes = [])`

### Authorize Attribute
- `Authorize(string $ability, ?string $model = null, array $parameters = [])`

### RateLimit Attribute
- `RateLimit(int $maxAttempts, int $decayMinutes, ?string $key = null)`

### Log Attribute
- `Log(string $level = 'info', ?string $message = null, array $context = [])`

### Middleware Attribute
- `Middleware(string|array $middleware, array $parameters = [])`

## 🔧 Commands

### Discover Attributes

View all discovered attributes in your application:

```bash
# Discover all attributes
php artisan attributes:discover

# Discover specific type
php artisan attributes:discover --type=routes
php artisan attributes:discover --type=listeners
php artisan attributes:discover --type=schedules
php artisan attributes:discover --type=cache
php artisan attributes:discover --type=validation

# Show detailed information
php artisan attributes:discover --detailed
```

## 🔌 Extending the Package

You can create custom attribute handlers by implementing the appropriate interface:

```php
<?php

namespace App\AttributeHandlers;

use Pixielity\LaravelAttributeCollector\Interfaces\AttributeHandlerInterface;
use Pixielity\LaravelAttributeCollector\Services\AttributeRegistry;

class CustomAttributeHandler implements AttributeHandlerInterface
{
    public function __construct(private AttributeRegistry $registry)
    {
    }

    public function handle(): void
    {
        $methods = $this->registry->findMethodsWithAttribute(YourCustomAttribute::class);
        
        foreach ($methods as $methodData) {
            // Handle your custom attribute
            $this->processCustomAttribute($methodData);
        }
    }

    private function processCustomAttribute(array $methodData): void
    {
        // Custom processing logic
    }
}
```

Register your handler in a service provider:

```php
public function boot()
{
    $this->app->make(AttributeRegistry::class)
        ->registerHandler($this->app->make(CustomAttributeHandler::class));
}
```

### Creating Custom Attributes

```php
<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class CustomAttribute
{
    public function __construct(
        public string $value,
        public array $options = []
    ) {
    }
}
```

## ⚡ Performance

This package uses `olvlvl/composer-attribute-collector` which generates a static file during `composer dump-autoload`. This means:

- ✅ Zero runtime reflection overhead
- ✅ No performance impact on HTTP requests
- ✅ Attributes are discovered at build time
- ✅ Cached attribute data for fast access
- ⚠️ Requires `composer dump-autoload` after attribute changes in development

## 🧪 Testing

Run the test suite:

```bash
# Run all tests
composer test

# Run tests with coverage
composer test-coverage

# Run static analysis
composer analyse

# Check code style
composer check-style

# Fix code style
composer fix-style
```

## 📖 Examples

Check the `examples/` directory for comprehensive usage examples of each attribute type.

## 🤝 Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on how to contribute to this project.

## 🔒 Security

If you discover any security-related issues, please email your.email@example.com instead of using the issue tracker.

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## 🙏 Credits

- Built on top of [olvlvl/composer-attribute-collector](https://github.com/olvlvl/composer-attribute-collector)
- Inspired by modern PHP attribute patterns
- Laravel community feedback and contributions

## 📈 Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for more information on what has changed recently.

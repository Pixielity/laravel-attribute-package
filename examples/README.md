# Laravel Attribute Collector - Usage Examples

This directory contains comprehensive examples demonstrating how to use each attribute provided by the Laravel Attribute Collector package.

## Available Examples

### 1. RouteExamples.php

Demonstrates various ways to use the `#[Route]` attribute:

- Basic GET/POST/PUT/PATCH/DELETE routes
- Named routes with middleware
- Parameter constraints and domain routing
- Multiple middleware application

### 2. ListenExamples.php

Shows different event listener patterns using `#[Listen]`:

- Synchronous and asynchronous event handling
- Multiple event listeners
- Queue configuration with retry logic
- Class-level and method-level listeners

### 3. ScheduleExamples.php

Illustrates scheduled task definitions with `#[Schedule]`:

- Daily, hourly, weekly, and monthly schedules
- Custom cron expressions with timezone support
- Background execution and overlap prevention
- Business-specific scheduling patterns

### 4. CacheExamples.php

Demonstrates method-level caching with `#[Cache]`:

- Basic caching with TTL configuration
- Custom cache keys with parameter placeholders
- Cache tags for grouped invalidation
- Store-specific caching (Redis, file, etc.)

### 5. ValidationExamples.php

Shows automatic request validation using `#[Validate]`:

- Basic validation rules
- Custom error messages and attributes
- File upload validation
- Conditional and nested array validation

### 6. AuthorizeExamples.php

Illustrates authorization patterns with `#[Authorize]`:

- Policy-based authorization
- Gate-based authorization
- Role and permission checking
- Multi-guard authorization

## Additional Attributes

The package also includes these attributes with handlers:

- `#[Middleware]` - Apply middleware to methods/classes
- `#[RateLimit]` - API throttling and rate limiting
- `#[Log]` - Automatic method logging

## Usage Instructions

1. **Installation**: Add the package to your Laravel application
2. **Configuration**: Publish and configure the attribute collector settings
3. **Implementation**: Use the attributes on your controller methods or classes
4. **Discovery**: Run `php artisan attribute:discover` to see all discovered attributes

## Best Practices

- Use attributes sparingly and only where they add clear value
- Combine attributes thoughtfully (e.g., Route + Middleware + Validate)
- Consider performance implications of caching and logging attributes
- Test authorization logic thoroughly when using Authorize attributes
- Monitor scheduled tasks when using Schedule attributes

## Integration with Composer Attribute Collector

This package leverages the `olvlvl/composer-attribute-collector` for zero-cost attribute discovery at build time, ensuring optimal performance in production environments.

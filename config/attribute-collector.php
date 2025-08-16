<?php

/**
 * Laravel Attribute Collector Configuration
 *
 * This configuration file controls the behavior of the Laravel Attribute Collector package,
 * which enables the use of PHP 8+ attributes for defining Laravel application components.
 *
 * The package leverages the composer-attribute-collector plugin to discover attributes
 * at build time, providing zero runtime cost for attribute discovery and processing.
 *
 * Configuration Options:
 * - Discovery paths: Directories to scan for PHP attributes
 * - Auto-registration flags: Enable/disable automatic registration of different component types
 * - Handler settings: Customize behavior of specific attribute handlers
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 * @since Laravel 9.0
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Attribute Discovery Paths
    |--------------------------------------------------------------------------
    |
    | These paths will be scanned for PHP attributes during the composer
    | dump-autoload process. The composer-attribute-collector plugin will
    | inspect these directories and compile a static map of all discovered
    | attributes for zero-cost runtime access.
    |
    | Default paths include:
    | - 'app': Standard Laravel application directory
    | - 'src': Common package source directory
    |
    | You can add additional paths as needed for your application structure.
    | Paths should be relative to your project root directory.
    |
    */
    'discovery_paths' => [
        'app',      // Laravel application directory (controllers, models, etc.)
        'src',      // Package source directory or custom application structure
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto-register Routes
    |--------------------------------------------------------------------------
    |
    | When enabled, routes defined with the Route attribute will be
    | automatically registered with Laravel's router during application boot.
    | This eliminates the need to manually define routes in route files.
    |
    | Route attributes support all standard Laravel routing features:
    | - HTTP method specification (GET, POST, PUT, PATCH, DELETE)
    | - Named routes for URL generation
    | - Middleware application
    | - Domain-specific routing
    | - Route parameter constraints
    |
    | Set to false if you prefer to handle route registration manually
    | or want to disable this feature entirely.
    |
    */
    'auto_register_routes' => true,

    /*
    |--------------------------------------------------------------------------
    | Auto-register Event Listeners
    |--------------------------------------------------------------------------
    |
    | When enabled, event listeners defined with the Listen attribute will be
    | automatically registered with Laravel's event dispatcher during
    | application boot. This provides a clean alternative to manually
    | registering listeners in the EventServiceProvider.
    |
    | Listen attributes support:
    | - Multiple event types per listener
    | - Queue configuration for asynchronous processing
    | - Priority ordering for listener execution
    | - Conditional listener registration
    |
    | Set to false if you prefer to handle event listener registration
    | manually or want to disable this feature.
    |
    */
    'auto_register_listeners' => true,

    /*
    |--------------------------------------------------------------------------
    | Auto-register Scheduled Jobs
    |--------------------------------------------------------------------------
    |
    | When enabled, jobs defined with the Schedule attribute will be
    | automatically registered with Laravel's task scheduler during
    | application boot. This allows you to define scheduled tasks directly
    | on job classes rather than in the console kernel.
    |
    | Schedule attributes support:
    | - Cron expressions for flexible scheduling
    | - Frequency methods (daily, hourly, weekly, etc.)
    | - Timezone configuration
    | - Conditional execution based on environment or other factors
    | - Overlap prevention and mutex handling
    |
    | Set to false if you prefer to handle scheduled job registration
    | manually in the console kernel or want to disable this feature.
    |
    */
    'auto_register_scheduled_jobs' => true,

    /*
    |--------------------------------------------------------------------------
    | Handler Configuration
    |--------------------------------------------------------------------------
    |
    | Additional configuration options for specific attribute handlers.
    | Each handler may define its own configuration options to customize
    | behavior beyond the basic auto-registration flags above.
    |
    | This section can be extended as new handlers are added or existing
    | handlers require additional configuration options.
    |
    */
    'handlers' => [
        // Handler-specific configuration can be added here
        // Example:
        // 'route' => [
        //     'prefix' => 'api',
        //     'middleware' => ['api'],
        // ],
    ],
];

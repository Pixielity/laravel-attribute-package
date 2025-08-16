<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector;

use Illuminate\Support\ServiceProvider;
use Pixielity\LaravelAttributeCollector\Commands\AttributeDiscoveryCommand;
use Pixielity\LaravelAttributeCollector\Services\AttributeRegistry;
use Pixielity\LaravelAttributeCollector\Services\AuthorizeAttributeHandler;
use Pixielity\LaravelAttributeCollector\Services\CacheAttributeHandler;
use Pixielity\LaravelAttributeCollector\Services\EventAttributeHandler;
use Pixielity\LaravelAttributeCollector\Services\JobAttributeHandler;
use Pixielity\LaravelAttributeCollector\Services\LogAttributeHandler;
use Pixielity\LaravelAttributeCollector\Services\MiddlewareAttributeHandler;
use Pixielity\LaravelAttributeCollector\Services\RateLimitAttributeHandler;
use Pixielity\LaravelAttributeCollector\Services\RouteAttributeHandler;
use Pixielity\LaravelAttributeCollector\Services\ValidateAttributeHandler;

/**
 * Laravel Attribute Collector Service Provider
 *
 * This service provider bootstraps the Laravel Attribute Collector package,
 * which enables the use of PHP 8+ attributes for defining Laravel application
 * components such as routes, event listeners, scheduled jobs, and middleware.
 *
 * The package leverages the composer-attribute-collector plugin to discover
 * attributes at build time, providing zero runtime cost for attribute discovery.
 *
 * Key Features:
 * - Automatic route registration via Route attributes
 * - Event listener registration via Listen attributes
 * - Scheduled job registration via Schedule attributes
 * - Middleware application via Middleware attributes
 * - Extensible handler system for custom attributes
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 * @since Laravel 9.0
 */
class LaravelAttributeCollectorServiceProvider extends ServiceProvider
{
    /**
     * Register services into the Laravel service container.
     *
     * This method is called during the application's registration phase
     * and is responsible for binding services as singletons to ensure
     * consistent state across the application lifecycle.
     *
     * Services registered:
     * - AttributeRegistry: Central registry for attribute discovery and processing
     * - RouteAttributeHandler: Processes Route attributes and registers routes
     * - EventAttributeHandler: Processes Listen attributes and registers event listeners
     * - JobAttributeHandler: Processes Schedule attributes and registers scheduled jobs
     */
    public function register(): void
    {
        // Merge package configuration with application configuration first
        // This allows users to override default settings via config/attribute-collector.php
        $this->mergeConfigFrom(
            __DIR__.'/../config/attribute-collector.php',
            'attribute-collector'
        );
        
        // Register core services as singletons to maintain state consistency
        $this->app->singleton(AttributeRegistry::class);
        $this->app->singleton(RouteAttributeHandler::class);
        $this->app->singleton(EventAttributeHandler::class);
        $this->app->singleton(JobAttributeHandler::class);
        $this->app->singleton(CacheAttributeHandler::class);
        $this->app->singleton(ValidateAttributeHandler::class);
        $this->app->singleton(RateLimitAttributeHandler::class);
        $this->app->singleton(LogAttributeHandler::class);
        $this->app->singleton(MiddlewareAttributeHandler::class);
        
        // Only register AuthorizeAttributeHandler if authorization is enabled
        if (config('attribute-collector.auto_register_authorization', true)) {
            $this->app->singleton(AuthorizeAttributeHandler::class);
        }
    }

    /**
     * Bootstrap services after all providers have been registered.
     *
     * This method is called during the application's boot phase and handles:
     * - Command registration for console applications
     * - Configuration publishing for package customization
     * - Attribute handler registration and processing
     *
     * The boot process ensures all attributes are discovered and processed
     * before the application begins handling requests.
     */
    public function boot(): void
    {
        // Register console commands and publish configuration only in console environment
        if ($this->app->runningInConsole()) {
            // Register Artisan commands for attribute discovery and debugging
            $this->commands([
                AttributeDiscoveryCommand::class,
            ]);

            // Allow users to publish and customize the package configuration
            $this->publishes([
                __DIR__.'/../config/attribute-collector.php' => config_path('attribute-collector.php'),
            ], 'config');
        }

        // Initialize the attribute processing system
        $this->registerAttributeHandlers();
    }

    /**
     * Register and initialize all attribute handlers.
     *
     * This method sets up the attribute processing pipeline by:
     * 1. Retrieving the central AttributeRegistry instance
     * 2. Registering all built-in attribute handlers
     * 3. Triggering the attribute discovery and processing workflow
     *
     * The processing occurs during application boot to ensure all
     * attribute-defined components are available when needed.
     */
    private function registerAttributeHandlers(): void
    {
        /** @var AttributeRegistry $registry Central registry for attribute management */
        $registry = $this->app->make(AttributeRegistry::class);

        // Register built-in handlers for core Laravel functionality
        // Each handler is responsible for processing specific attribute types
        $registry->registerHandler($this->app->make(RouteAttributeHandler::class));
        $registry->registerHandler($this->app->make(EventAttributeHandler::class));
        $registry->registerHandler($this->app->make(JobAttributeHandler::class));
        $registry->registerHandler($this->app->make(CacheAttributeHandler::class));
        $registry->registerHandler($this->app->make(ValidateAttributeHandler::class));
        $registry->registerHandler($this->app->make(RateLimitAttributeHandler::class));
        
        // Only register AuthorizeAttributeHandler if authorization is enabled
        if (config('attribute-collector.auto_register_authorization', true)) {
            $registry->registerHandler($this->app->make(AuthorizeAttributeHandler::class));
        }
        
        $registry->registerHandler($this->app->make(LogAttributeHandler::class));
        $registry->registerHandler($this->app->make(MiddlewareAttributeHandler::class));

        // Process all registered attributes and apply them to Laravel systems
        // This step converts attribute definitions into actual Laravel registrations
        $registry->processAttributes();
    }
}

<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Pixielity\LaravelAttributeCollector\LaravelAttributeCollectorServiceProvider;

/**
 * Base test case for Laravel Attribute Collector package tests.
 *
 * This class provides the foundation for all package tests by setting up
 * the testing environment with Orchestra Testbench and registering the
 * package service provider.
 *
 * @author Your Name <your.email@example.com>
 */
abstract class TestCase extends Orchestra
{
    /**
     * Set up the test environment before each test.
     *
     * This method is called before each test method is executed.
     * It performs any necessary setup for the testing environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Additional setup can be added here
    }

    /**
     * Get the package providers for the test environment.
     *
     * This method registers the package service provider with the
     * Laravel application during testing.
     *
     * @param \Illuminate\Foundation\Application $app The Laravel application instance
     *
     * @return array<int, class-string> Array of service provider class names
     */
    protected function getPackageProviders($app): array
    {
        return [
            LaravelAttributeCollectorServiceProvider::class,
        ];
    }

    /**
     * Define environment setup for the test.
     *
     * This method allows you to configure the Laravel application
     * environment specifically for testing purposes.
     *
     * @param \Illuminate\Foundation\Application $app The Laravel application instance
     */
    protected function defineEnvironment($app): void
    {
        // Configure testing environment
        $app['config']->set('attribute-collector.enabled', true);
        $app['config']->set('cache.default', 'array');
        $app['config']->set('database.default', 'testing');

        // Disable authorization handler during testing to avoid Gate dependency issues
        $app['config']->set('attribute-collector.auto_register_authorization', false);

        // Set up basic auth guard configuration for testing
        $app['config']->set('auth.defaults.guard', 'web');
        $app['config']->set('auth.guards.web', [
            'driver' => 'session',
            'provider' => 'users',
        ]);
        $app['config']->set('auth.providers.users', [
            'driver' => 'eloquent',
            'model' => '\Illuminate\Foundation\Auth\User',
        ]);
    }
}

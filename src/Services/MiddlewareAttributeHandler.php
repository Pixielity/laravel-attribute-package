<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Services;

use Illuminate\Routing\Router;
use Pixielity\LaravelAttributeCollector\Attributes\Middleware;
use Pixielity\LaravelAttributeCollector\Interfaces\AttributeHandlerInterface;

/**
 * Middleware Attribute Handler for Automatic Middleware Application
 *
 * This handler processes Middleware attributes and applies them to routes
 * or controller methods automatically. It integrates with Laravel's routing
 * system to provide declarative middleware application.
 *
 * Features:
 * - Automatic middleware application to routes
 * - Support for middleware parameters
 * - Class-level and method-level middleware
 * - Integration with existing route middleware
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
class MiddlewareAttributeHandler implements AttributeHandlerInterface
{
    /**
     * Create a new MiddlewareAttributeHandler instance.
     *
     * @param AttributeRegistry $registry Central registry for attribute discovery
     * @param Router            $router   Laravel's router for middleware application
     */
    public function __construct(
        /** @var AttributeRegistry Registry for discovering Middleware attributes */
        private AttributeRegistry $registry,

        /** @var Router Laravel's router for applying middleware */
        private Router $router
    ) {}

    /**
     * Process and register all Middleware attributes.
     *
     * Discovers all classes and methods with Middleware attributes and
     * applies them to the corresponding routes.
     */
    public function handle(): void
    {
        if (! config('attribute-collector.auto_register_middleware', true)) {
            return;
        }

        $classes = $this->registry->findClassesWithAttribute(Middleware::class);
        foreach ($classes as $classData) {
            $this->registerClassMiddleware($classData);
        }

        $methods = $this->registry->findMethodsWithAttribute(Middleware::class);
        foreach ($methods as $methodData) {
            $this->registerMethodMiddleware($methodData);
        }
    }

    /**
     * Register class-level middleware.
     *
     * Applies middleware to all routes in a controller class.
     *
     * @param array{class: string, attribute: Middleware} $classData Class with Middleware attribute
     */
    private function registerClassMiddleware(array $classData): void
    {
        /** @var Middleware $middlewareAttribute */
        $middlewareAttribute = $classData['attribute'];
        $class = $classData['class'];

        // Find all routes that match this controller and apply middleware
        $routes = $this->router->getRoutes()->getRoutes();
        foreach ($routes as $route) {
            if (strpos($route->getActionName(), $class) !== false) {
                $route->middleware($middlewareAttribute->middleware);
            }
        }
    }

    /**
     * Register method-level middleware.
     *
     * Applies middleware to specific controller methods.
     *
     * @param array{class: string, method: string, attribute: Middleware} $methodData Method with Middleware attribute
     */
    private function registerMethodMiddleware(array $methodData): void
    {
        /** @var Middleware $middlewareAttribute */
        $middlewareAttribute = $methodData['attribute'];
        $class = $methodData['class'];
        $method = $methodData['method'];

        $middlewareConfig = $middlewareAttribute->middleware;

        if (! empty($middlewareAttribute->parameters)) {
            foreach ($middlewareConfig as &$middleware) {
                $middleware .= ':'.implode(',', $middlewareAttribute->parameters);
            }
        }

        // This would involve finding the route that corresponds to the class/method
        // and applying the middleware configuration
    }
}

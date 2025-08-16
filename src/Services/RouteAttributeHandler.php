<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Services;

use Illuminate\Routing\Router;
use Pixielity\LaravelAttributeCollector\Attributes\Middleware;
use Pixielity\LaravelAttributeCollector\Attributes\Route;
use Pixielity\LaravelAttributeCollector\Interfaces\RouteHandlerInterface;

/**
 * Route Attribute Handler for Laravel Route Registration
 *
 * This handler is responsible for discovering Route attributes on controller methods
 * and automatically registering them with Laravel's routing system. It processes
 * all Route attribute configurations and applies them to create fully functional
 * Laravel routes without requiring manual route file definitions.
 *
 * Key Features:
 * - Automatic route discovery and registration
 * - Support for all HTTP methods (GET, POST, PUT, PATCH, DELETE)
 * - Named route registration for URL generation
 * - Middleware application from Route attributes
 * - Additional middleware from Middleware attributes
 * - Domain-specific routing support
 * - Route parameter constraint application
 *
 * The handler integrates seamlessly with Laravel's existing routing system,
 * creating routes that behave identically to those defined in traditional route files.
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
class RouteAttributeHandler implements RouteHandlerInterface
{
    /**
     * Create a new RouteAttributeHandler instance.
     *
     * @param  AttributeRegistry  $registry  Central registry for attribute discovery
     * @param  Router  $router  Laravel's router instance for route registration
     */
    public function __construct(
        /** @var AttributeRegistry Central registry for discovering Route attributes */
        private AttributeRegistry $registry,

        /** @var Router Laravel's router for registering discovered routes */
        private Router $router
    ) {}

    /**
     * Discover and register all Route attributes as Laravel routes.
     *
     * This method is called during application boot to process all methods
     * decorated with Route attributes. It respects the configuration setting
     * for auto-registration and gracefully handles any processing errors.
     *
     * The processing workflow:
     * 1. Check if auto-registration is enabled
     * 2. Discover all methods with Route attributes
     * 3. Register each route with Laravel's router
     * 4. Apply additional middleware from Middleware attributes
     */
    public function handle(): void
    {
        // Respect configuration setting for auto-registration
        if (! config('attribute-collector.auto_register_routes', true)) {
            return;
        }

        // Discover all methods decorated with Route attributes
        $methods = $this->registry->findMethodsWithAttribute(Route::class);

        // Process each discovered route method
        foreach ($methods as $methodData) {
            $this->registerRoute($methodData);
        }
    }

    /**
     * Register a single route from attribute data.
     *
     * This method takes the discovered Route attribute data and creates
     * a corresponding Laravel route with all specified configurations
     * including HTTP method, URI pattern, middleware, and constraints.
     *
     * @param  array{class: string, method: string, attribute: Route}  $methodData  Route attribute data
     */
    public function registerRoute(array $methodData): void
    {
        /** @var Route $routeAttribute The Route attribute instance with configuration */
        $routeAttribute = $methodData['attribute'];
        $class = $methodData['class'];
        $method = $methodData['method'];

        // Register the route with Laravel's router using the attribute configuration
        $route = $this->router->addRoute(
            $routeAttribute->method,
            $routeAttribute->uri,
            [$class, $method]
        );

        // Apply optional route name for URL generation and route caching
        if ($routeAttribute->name) {
            $route->name($routeAttribute->name);
        }

        // Apply middleware specified in the Route attribute
        if ($routeAttribute->middleware) {
            $route->middleware($routeAttribute->middleware);
        }

        // Apply domain constraint for subdomain routing
        if ($routeAttribute->domain) {
            $route->domain($routeAttribute->domain);
        }

        // Apply parameter constraints using regular expressions
        if ($routeAttribute->where) {
            $route->where($routeAttribute->where);
        }

        // Check for and apply additional middleware from Middleware attributes
        $this->applyMiddlewareAttributes($route, $class, $method);
    }

    /**
     * Apply additional middleware from Middleware attributes.
     *
     * This method discovers and applies middleware defined via Middleware attributes
     * on both the class level (applies to all methods) and method level (applies
     * to specific methods). This provides a flexible way to layer middleware
     * beyond what's specified in the Route attribute itself.
     *
     * @param  \Illuminate\Routing\Route  $route  The Laravel route instance to apply middleware to
     * @param  string  $class  Fully qualified class name containing the route method
     * @param  string  $method  Method name that handles the route
     */
    private function applyMiddlewareAttributes($route, string $class, string $method): void
    {
        // Get all attributes for the class (both class-level and method-level)
        $classAttributes = $this->registry->getAttributesForClass($class);

        // Apply class-level middleware to all routes in the controller
        foreach ($classAttributes->classAttributes as $attribute) {
            if ($attribute instanceof Middleware) {
                $route->middleware($attribute->middleware);
            }
        }

        // Apply method-specific middleware only to this route
        if (isset($classAttributes->methodsAttributes[$method])) {
            foreach ($classAttributes->methodsAttributes[$method] as $attribute) {
                if ($attribute instanceof Middleware) {
                    $route->middleware($attribute->middleware);
                }
            }
        }
    }
}

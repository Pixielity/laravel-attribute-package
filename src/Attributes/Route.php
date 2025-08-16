<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Attributes;

use Attribute;

/**
 * Route Attribute for Laravel Route Definition
 *
 * This attribute enables developers to define Laravel routes directly on controller methods
 * using PHP 8+ attributes, eliminating the need for traditional route file definitions.
 *
 * The Route attribute supports all standard Laravel routing features including:
 * - HTTP method specification (GET, POST, PUT, PATCH, DELETE)
 * - Named routes for URL generation
 * - Middleware application
 * - Domain-specific routing
 * - Route parameter constraints
 *
 * Usage Examples:
 * ```php
 * #[Route::get('/users', name: 'users.index')]
 * public function index() { }
 *
 * #[Route::post('/users', name: 'users.store', middleware: ['auth', 'throttle:60,1'])]
 * public function store(Request $request) { }
 *
 * #[Route::get('/users/{id}', name: 'users.show', where: ['id' => '[0-9]+'])]
 * public function show(int $id) { }
 * ```
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    /**
     * Create a new Route attribute instance.
     *
     * @param string                $method     The HTTP method for this route (GET, POST, PUT, PATCH, DELETE)
     * @param string                $uri        The URI pattern for this route (e.g., '/users/{id}')
     * @param string|null           $name       Optional route name for URL generation and route caching
     * @param array<string>         $middleware Array of middleware names to apply to this route
     * @param string|null           $domain     Optional domain constraint for this route
     * @param array<string, string> $where      Array of parameter constraints (parameter => regex pattern)
     */
    public function __construct(
        /** @var string The HTTP method (GET, POST, PUT, PATCH, DELETE) */
        public string $method,

        /** @var string The URI pattern with optional parameters */
        public string $uri,

        /** @var string|null Optional route name for reverse URL generation */
        public ?string $name = null,

        /** @var array<string> Middleware stack to apply to this route */
        public array $middleware = [],

        /** @var string|null Domain constraint for subdomain routing */
        public ?string $domain = null,

        /** @var array<string, string> Parameter constraints as regex patterns */
        public array $where = []
    ) {}

    /**
     * Create a GET route attribute.
     *
     * Convenience method for creating GET routes with a fluent interface.
     * GET routes are typically used for retrieving resources.
     *
     * @param string                $uri        The URI pattern for the route
     * @param string|null           $name       Optional route name
     * @param array<string>         $middleware Middleware to apply
     * @param string|null           $domain     Domain constraint
     * @param array<string, string> $where      Parameter constraints
     *
     * @return self New Route instance configured for GET method
     */
    public static function get(
        string $uri,
        ?string $name = null,
        array $middleware = [],
        ?string $domain = null,
        array $where = []
    ): self {
        return new self('GET', $uri, $name, $middleware, $domain, $where);
    }

    /**
     * Create a POST route attribute.
     *
     * Convenience method for creating POST routes with a fluent interface.
     * POST routes are typically used for creating new resources.
     *
     * @param string                $uri        The URI pattern for the route
     * @param string|null           $name       Optional route name
     * @param array<string>         $middleware Middleware to apply
     * @param string|null           $domain     Domain constraint
     * @param array<string, string> $where      Parameter constraints
     *
     * @return self New Route instance configured for POST method
     */
    public static function post(
        string $uri,
        ?string $name = null,
        array $middleware = [],
        ?string $domain = null,
        array $where = []
    ): self {
        return new self('POST', $uri, $name, $middleware, $domain, $where);
    }

    /**
     * Create a PUT route attribute.
     *
     * Convenience method for creating PUT routes with a fluent interface.
     * PUT routes are typically used for updating entire resources.
     *
     * @param string                $uri        The URI pattern for the route
     * @param string|null           $name       Optional route name
     * @param array<string>         $middleware Middleware to apply
     * @param string|null           $domain     Domain constraint
     * @param array<string, string> $where      Parameter constraints
     *
     * @return self New Route instance configured for PUT method
     */
    public static function put(
        string $uri,
        ?string $name = null,
        array $middleware = [],
        ?string $domain = null,
        array $where = []
    ): self {
        return new self('PUT', $uri, $name, $middleware, $domain, $where);
    }

    /**
     * Create a PATCH route attribute.
     *
     * Convenience method for creating PATCH routes with a fluent interface.
     * PATCH routes are typically used for partial resource updates.
     *
     * @param string                $uri        The URI pattern for the route
     * @param string|null           $name       Optional route name
     * @param array<string>         $middleware Middleware to apply
     * @param string|null           $domain     Domain constraint
     * @param array<string, string> $where      Parameter constraints
     *
     * @return self New Route instance configured for PATCH method
     */
    public static function patch(
        string $uri,
        ?string $name = null,
        array $middleware = [],
        ?string $domain = null,
        array $where = []
    ): self {
        return new self('PATCH', $uri, $name, $middleware, $domain, $where);
    }

    /**
     * Create a DELETE route attribute.
     *
     * Convenience method for creating DELETE routes with a fluent interface.
     * DELETE routes are typically used for removing resources.
     *
     * @param string                $uri        The URI pattern for the route
     * @param string|null           $name       Optional route name
     * @param array<string>         $middleware Middleware to apply
     * @param string|null           $domain     Domain constraint
     * @param array<string, string> $where      Parameter constraints
     *
     * @return self New Route instance configured for DELETE method
     */
    public static function delete(
        string $uri,
        ?string $name = null,
        array $middleware = [],
        ?string $domain = null,
        array $where = []
    ): self {
        return new self('DELETE', $uri, $name, $middleware, $domain, $where);
    }
}

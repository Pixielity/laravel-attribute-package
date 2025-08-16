<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Attributes;

use Attribute;

/**
 * Middleware Attribute for Laravel Middleware Application
 *
 * This attribute enables developers to apply middleware directly to classes or methods
 * using PHP 8+ attributes, providing a clean alternative to route-level middleware
 * definitions or controller constructor middleware registration.
 *
 * The Middleware attribute supports all standard Laravel middleware features including:
 * - Single or multiple middleware application
 * - Middleware parameters for configuration
 * - Class-level middleware (applies to all methods)
 * - Method-level middleware (applies to specific methods)
 * - Repeatable attribute for multiple middleware declarations
 *
 * Usage Examples:
 * ```php
 * #[Middleware('auth')]
 * class UserController { }
 *
 * #[Middleware(['auth', 'verified'])]
 * public function dashboard() { }
 *
 * #[Middleware('throttle', parameters: ['60', '1'])]
 * public function apiEndpoint() { }
 *
 * #[Middleware('auth')]
 * #[Middleware('can:edit-posts')]
 * public function editPost() { }
 * ```
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Middleware
{
    /**
     * Create a new Middleware attribute instance.
     *
     * @param  string|array<string>  $middleware  Single middleware name or array of middleware names to apply
     * @param  array<string>  $parameters  Optional parameters to pass to the middleware
     */
    public function __construct(
        /** @var array<string> Array of middleware names to apply */
        public string|array $middleware,

        /** @var array<string> Parameters to pass to the middleware (e.g., rate limiting values) */
        public array $parameters = []
    ) {
        if (is_string($this->middleware)) {
            $this->middleware = [$this->middleware];
        }
    }
}

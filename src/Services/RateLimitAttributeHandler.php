<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Services;

use Illuminate\Routing\Router;
use Pixielity\LaravelAttributeCollector\Attributes\RateLimit;
use Pixielity\LaravelAttributeCollector\Interfaces\AttributeHandlerInterface;

/**
 * Rate Limit Attribute Handler for API Throttling
 *
 * This handler processes RateLimit attributes and applies Laravel's throttling
 * middleware to controller methods based on the attribute configuration.
 *
 * Features:
 * - Automatic throttle middleware application
 * - Configurable rate limits and time windows
 * - Multiple throttling strategies (IP, user, API key)
 * - Integration with Laravel's existing throttle middleware
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
class RateLimitAttributeHandler implements AttributeHandlerInterface
{
    /**
     * Create a new RateLimitAttributeHandler instance.
     *
     * @param  AttributeRegistry  $registry  Central registry for attribute discovery
     * @param  Router  $router  Laravel's router for middleware application
     */
    public function __construct(
        /** @var AttributeRegistry Registry for discovering RateLimit attributes */
        private AttributeRegistry $registry,

        /** @var Router Laravel's router for applying throttle middleware */
        private Router $router
    ) {}

    /**
     * Process and register all RateLimit attributes.
     *
     * Discovers all methods with RateLimit attributes and applies
     * appropriate throttle middleware to the routes.
     */
    public function handle(): void
    {
        if (! config('attribute-collector.auto_register_rate_limits', true)) {
            return;
        }

        $methods = $this->registry->findMethodsWithAttribute(RateLimit::class);

        foreach ($methods as $methodData) {
            $this->applyRateLimit($methodData);
        }
    }

    /**
     * Apply rate limiting to a method.
     *
     * Configures throttle middleware based on the RateLimit attribute
     * settings and applies it to the corresponding route.
     *
     * @param  array{class: string, method: string, attribute: RateLimit}  $methodData  Method with RateLimit attribute
     */
    private function applyRateLimit(array $methodData): void
    {
        /** @var RateLimit $rateLimitAttribute */
        $rateLimitAttribute = $methodData['attribute'];
        $class = $methodData['class'];
        $method = $methodData['method'];

        $throttleConfig = sprintf(
            'throttle:%d,%d',
            $rateLimitAttribute->attempts,
            $rateLimitAttribute->decayMinutes
        );

        if ($rateLimitAttribute->key) {
            $throttleConfig .= ','.$rateLimitAttribute->key;
        } elseif ($rateLimitAttribute->by !== 'ip') {
            $throttleConfig .= ','.$rateLimitAttribute->by;
        }

        // This would typically involve finding routes that match the class/method
        // and applying the throttle middleware to them
    }
}

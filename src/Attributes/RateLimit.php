<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Attributes;

use Attribute;

/**
 * Rate Limiting Attribute for API Throttling
 *
 * This attribute provides declarative rate limiting for controller methods
 * using Laravel's built-in throttling capabilities. It automatically applies
 * rate limiting middleware with configurable limits and time windows.
 *
 * Features:
 * - Configurable request limits and time windows
 * - Per-user, per-IP, or global rate limiting
 * - Custom rate limit keys for complex scenarios
 * - Integration with Laravel's throttle middleware
 * - Customizable response for rate limit exceeded
 *
 * Usage Examples:
 *
 * Basic rate limiting (60 requests per minute):
 * #[RateLimit(60)]
 * public function apiEndpoint() { ... }
 *
 * Custom time window and per-user limiting:
 * #[RateLimit(attempts: 100, decayMinutes: 60, by: 'user')]
 * public function userSpecificEndpoint() { ... }
 *
 * API key based limiting:
 * #[RateLimit(attempts: 1000, decayMinutes: 60, by: 'api_key')]
 * public function apiKeyEndpoint() { ... }
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class RateLimit
{
    /**
     * Create a new RateLimit attribute instance.
     *
     * @param  int  $attempts  Maximum number of attempts allowed
     * @param  int  $decayMinutes  Time window in minutes for rate limit reset
     * @param  string  $by  Rate limiting strategy ('ip', 'user', 'api_key', or custom)
     * @param  string|null  $key  Custom rate limit key for complex scenarios
     * @param  string|null  $response  Custom response when rate limit is exceeded
     */
    public function __construct(
        /** @var int Maximum number of requests allowed in the time window */
        public readonly int $attempts = 60,

        /** @var int Time window in minutes before rate limit resets */
        public readonly int $decayMinutes = 1,

        /** @var string Rate limiting strategy (ip, user, api_key, custom) */
        public readonly string $by = 'ip',

        /** @var string|null Custom key for rate limiting (overrides 'by' parameter) */
        public readonly ?string $key = null,

        /** @var string|null Custom response message when rate limit exceeded */
        public readonly ?string $response = null
    ) {}
}

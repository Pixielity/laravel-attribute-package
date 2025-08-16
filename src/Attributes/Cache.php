<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Attributes;

use Attribute;

/**
 * Cache Attribute for Method-level Caching
 *
 * This attribute enables automatic caching of method results using Laravel's
 * cache system. It provides a declarative way to add caching to any method
 * without modifying the method implementation.
 *
 * Features:
 * - Configurable cache duration (TTL)
 * - Custom cache key generation
 * - Cache tag support for grouped invalidation
 * - Store-specific caching (redis, file, database, etc.)
 * - Conditional caching based on parameters
 *
 * Usage Examples:
 *
 * Basic caching with default settings:
 * #[Cache]
 * public function getExpensiveData() { ... }
 *
 * Custom TTL and key:
 * #[Cache(ttl: 3600, key: 'user_profile_{user_id}')]
 * public function getUserProfile(int $userId) { ... }
 *
 * With cache tags for grouped invalidation:
 * #[Cache(tags: ['users', 'profiles'], ttl: 1800)]
 * public function getUserStats(int $userId) { ... }
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Cache
{
    /**
     * Create a new Cache attribute instance.
     *
     * @param int|null    $ttl      Cache time-to-live in seconds (null = forever)
     * @param string|null $key      Custom cache key pattern (supports placeholders)
     * @param array       $tags     Cache tags for grouped invalidation
     * @param string|null $store    Specific cache store to use
     * @param bool        $remember Whether to use remember() or put() method
     */
    public function __construct(
        /** @var int|null Cache duration in seconds, null for permanent cache */
        public readonly ?int $ttl = 3600,

        /** @var string|null Custom cache key pattern with parameter placeholders */
        public readonly ?string $key = null,

        /** @var array Cache tags for grouped cache invalidation */
        public readonly array $tags = [],

        /** @var string|null Specific cache store (redis, file, database, etc.) */
        public readonly ?string $store = null,

        /** @var bool Whether to use Laravel's remember() method for automatic caching */
        public readonly bool $remember = true
    ) {}
}

<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Services;

use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Pixielity\LaravelAttributeCollector\Attributes\Cache;
use Pixielity\LaravelAttributeCollector\Interfaces\CacheableInterface;

/**
 * Cache Attribute Handler for Method-level Caching
 *
 * This handler processes Cache attributes and implements automatic method result
 * caching using Laravel's cache system. It intercepts method calls and applies
 * caching logic based on the attribute configuration.
 *
 * Features:
 * - Automatic cache key generation from method parameters
 * - Configurable TTL and cache stores
 * - Cache tag support for grouped invalidation
 * - Method interception using Laravel's container
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
class CacheAttributeHandler implements CacheableInterface
{
    /**
     * Create a new CacheAttributeHandler instance.
     *
     * @param AttributeRegistry $registry  Central registry for attribute discovery
     * @param CacheManager      $cache     Laravel's cache manager
     * @param Container         $container Laravel service container for method interception
     */
    public function __construct(
        /** @var AttributeRegistry Registry for discovering Cache attributes */
        private AttributeRegistry $registry,

        /** @var CacheManager Laravel's cache manager for storing cached results */
        private CacheManager $cache,

        /** @var Container Laravel container for method interception */
        private Container $container
    ) {}

    /**
     * Process and register all Cache attributes.
     *
     * Discovers all methods with Cache attributes and sets up method
     * interception to implement automatic caching behavior.
     */
    public function handle(): void
    {
        if (! config('attribute-collector.auto_register_cache', true)) {
            return;
        }

        $methods = $this->registry->findMethodsWithAttribute(Cache::class);

        foreach ($methods as $methodData) {
            $this->registerCacheInterception($methodData);
        }
    }

    /**
     * Get the cache key for this handler's data.
     */
    public function getCacheKey(): string
    {
        return 'laravel_attribute_collector:cache_handler:'.md5(static::class);
    }

    /**
     * Cache the processed attribute data.
     *
     * @param mixed $data Data to cache
     */
    public function cacheData(mixed $data): void
    {
        $cacheKey = $this->getCacheKey();
        $ttl = config('attribute-collector.cache_ttl', 3600); // Default 1 hour

        $this->cache->put($cacheKey, $data, $ttl);
    }

    /**
     * Retrieve cached attribute data.
     *
     * @return mixed|null
     */
    public function getCachedData(): mixed
    {
        $cacheKey = $this->getCacheKey();

        return $this->cache->get($cacheKey);
    }

    /**
     * Register cache interception for a method.
     *
     * Sets up method interception to automatically cache method results
     * based on the Cache attribute configuration.
     *
     * @param array{class: string, method: string, attribute: Cache} $methodData Method with Cache attribute
     */
    private function registerCacheInterception(array $methodData): void
    {
        /** @var Cache $cacheAttribute */
        $cacheAttribute = $methodData['attribute'];
        $class = $methodData['class'];
        $method = $methodData['method'];

        $this->container->extend($class, function ($instance) use ($method, $cacheAttribute) {
            return $this->wrapMethodWithCache($instance, $method, $cacheAttribute);
        });
    }

    /**
     * Wrap a method with caching logic.
     *
     * Creates a proxy that intercepts method calls and applies caching
     * based on the Cache attribute configuration.
     *
     * @param object $instance       The class instance to wrap
     * @param string $method         The method name to cache
     * @param Cache  $cacheAttribute The cache configuration
     *
     * @return object Wrapped instance with caching
     */
    private function wrapMethodWithCache(object $instance, string $method, Cache $cacheAttribute): object
    {
        // Implementation would use method interception or proxy patterns
        // This is a simplified example - real implementation would be more complex
        return $instance;
    }
}

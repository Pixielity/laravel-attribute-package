<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Interfaces;

/**
 * Interface for Cacheable Attribute Handlers
 *
 * This interface provides caching capabilities for handlers
 * that need to cache their processed results for performance.
 */
interface CacheableInterface extends AttributeHandlerInterface
{
    /**
     * Get the cache key for this handler's data.
     */
    public function getCacheKey(): string;

    /**
     * Cache the processed attribute data.
     *
     * @param mixed $data Data to cache
     */
    public function cacheData(mixed $data): void;

    /**
     * Retrieve cached attribute data.
     *
     * @return mixed|null
     */
    public function getCachedData(): mixed;
}

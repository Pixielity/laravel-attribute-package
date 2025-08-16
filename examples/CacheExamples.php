<?php

declare(strict_types=1);

namespace Examples;

use Illuminate\Http\JsonResponse;
use Pixielity\LaravelAttributeCollector\Attributes\Cache;

/**
 * Cache Attribute Usage Examples
 *
 * This class demonstrates various ways to use the Cache attribute
 * for automatic method-level caching.
 */
class CacheExamples
{
    /**
     * Basic caching example
     *
     * Simple method caching with default 1-hour TTL.
     * Cache key is automatically generated from method name and parameters.
     */
    #[Cache]
    public function getExpensiveData(): array
    {
        // Simulate expensive operation
        sleep(2);

        return [
            'data' => 'This is expensive to compute',
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Custom TTL and cache key example
     *
     * Caches user profile data for 30 minutes with custom key pattern.
     * Uses parameter placeholder in cache key.
     */
    #[Cache(ttl: 1800, key: 'user_profile_{user_id}')]
    public function getUserProfile(int $userId): array
    {
        // Simulate database query
        return [
            'id' => $userId,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'profile_data' => 'Complex profile information',
        ];
    }

    /**
     * Cache with tags example
     *
     * Uses cache tags for grouped invalidation.
     * Useful for invalidating related cached data.
     */
    #[Cache(tags: ['users', 'profiles'], ttl: 3600)]
    public function getUserStats(int $userId): array
    {
        return [
            'user_id' => $userId,
            'posts_count' => 42,
            'followers_count' => 1337,
            'following_count' => 256,
        ];
    }

    /**
     * Store-specific caching example
     *
     * Uses Redis cache store specifically for this method.
     * Useful for high-performance caching requirements.
     */
    #[Cache(store: 'redis', ttl: 7200, key: 'api_data_{endpoint}')]
    public function getApiData(string $endpoint): array
    {
        // Simulate external API call
        return [
            'endpoint' => $endpoint,
            'data' => 'External API response data',
            'cached_at' => now()->toISOString(),
        ];
    }

    /**
     * Permanent caching example
     *
     * Caches data permanently (until manually invalidated).
     * Useful for configuration or rarely-changing data.
     */
    #[Cache(ttl: null, key: 'app_config')]
    public function getApplicationConfig(): array
    {
        return [
            'app_name' => config('app.name'),
            'version' => '1.0.0',
            'features' => ['feature1', 'feature2', 'feature3'],
        ];
    }

    /**
     * Complex cache key example
     *
     * Uses multiple parameters in cache key pattern.
     * Demonstrates flexible cache key generation.
     */
    #[Cache(ttl: 900, key: 'search_results_{query}_{page}_{per_page}')]
    public function searchResults(string $query, int $page = 1, int $perPage = 10): array
    {
        // Simulate search operation
        return [
            'query' => $query,
            'page' => $page,
            'per_page' => $perPage,
            'results' => ['result1', 'result2', 'result3'],
            'total' => 150,
        ];
    }

    /**
     * Tagged cache for product data example
     *
     * Uses multiple tags for fine-grained cache invalidation.
     * Allows invalidating by product, category, or all products.
     */
    #[Cache(tags: ['products', 'category_{category_id}', 'product_{product_id}'], ttl: 2400)]
    public function getProductDetails(int $productId, int $categoryId): array
    {
        return [
            'id' => $productId,
            'category_id' => $categoryId,
            'name' => 'Sample Product',
            'price' => 99.99,
            'description' => 'Detailed product information',
        ];
    }

    /**
     * JSON response caching example
     *
     * Caches API responses for better performance.
     * Useful for expensive API endpoints.
     */
    #[Cache(ttl: 600, key: 'api_response_{user_id}')]
    public function getUserApiResponse(int $userId): JsonResponse
    {
        $userData = [
            'user' => $this->getUserProfile($userId),
            'stats' => $this->getUserStats($userId),
            'preferences' => $this->getUserPreferences($userId),
        ];

        return response()->json($userData);
    }

    // Helper method (would be implemented in real application)
    private function getUserPreferences(int $userId): array
    {
        return ['theme' => 'dark', 'language' => 'en'];
    }
}

<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Tests\Unit;

use Pixielity\LaravelAttributeCollector\Attributes\Route;
use Pixielity\LaravelAttributeCollector\Services\AttributeRegistry;
use Pixielity\LaravelAttributeCollector\Tests\TestCase;

/**
 * Unit tests for the AttributeRegistry service.
 *
 * This test class verifies the functionality of the AttributeRegistry
 * service, which is responsible for managing and retrieving collected
 * attributes from the composer-attribute-collector system.
 *
 * @author Your Name <your.email@example.com>
 */
class AttributeRegistryTest extends TestCase
{
    /**
     * The AttributeRegistry instance under test.
     */
    private AttributeRegistry $registry;

    /**
     * Set up the test environment before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->registry = $this->app->make(AttributeRegistry::class);
    }

    /**
     * Test that the registry can be instantiated.
     *
     * @test
     */
    public function it_can_be_instantiated(): void
    {
        $this->assertInstanceOf(AttributeRegistry::class, $this->registry);
    }

    /**
     * Test that the registry returns an array of attributes.
     *
     * @test
     */
    public function it_returns_array_of_attributes(): void
    {
        $attributes = $this->registry->getAttributes();

        $this->assertIsArray($attributes);
    }

    /**
     * Test that the registry can filter attributes by type.
     *
     * @test
     */
    public function it_can_filter_attributes_by_type(): void
    {
        $routeAttributes = $this->registry->getAttributesByType(Route::class);

        $this->assertIsArray($routeAttributes);
    }
}

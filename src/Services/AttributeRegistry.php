<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Services;

use Illuminate\Support\Collection;
use olvlvl\ComposerAttributeCollector\Attributes;
use Pixielity\LaravelAttributeCollector\Interfaces\AttributeHandlerInterface;

/**
 * Central Registry for PHP Attribute Discovery and Management
 *
 * The AttributeRegistry serves as the central hub for discovering, managing, and processing
 * PHP 8+ attributes within a Laravel application. It leverages the composer-attribute-collector
 * plugin to provide zero-cost attribute discovery at build time.
 *
 * Key Responsibilities:
 * - Register and manage attribute handlers for different attribute types
 * - Coordinate the attribute processing workflow during application boot
 * - Provide convenient methods for querying attributes on classes, methods, and properties
 * - Abstract the complexity of the underlying composer-attribute-collector API
 *
 * The registry uses a handler-based architecture where each attribute type (routes, events, etc.)
 * has a dedicated handler responsible for processing and registering those attributes with
 * the appropriate Laravel systems.
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
class AttributeRegistry
{
    /**
     * Collection of registered attribute handlers.
     *
     * Each handler is responsible for processing a specific type of attribute
     * and integrating it with the appropriate Laravel system (routing, events, etc.).
     *
     * @var Collection<AttributeHandlerInterface> Collection of attribute handler instances
     */
    private Collection $handlers;

    /**
     * Initialize the AttributeRegistry with an empty Collection of handlers.
     */
    public function __construct()
    {
        $this->handlers = collect();
    }

    /**
     * Register an attribute handler with the registry.
     *
     * Handlers are responsible for processing specific types of attributes
     * and integrating them with Laravel's systems. Each handler must implement
     * the AttributeHandlerInterface to ensure consistent behavior.
     *
     * @param AttributeHandlerInterface $handler The handler to register
     */
    public function registerHandler(AttributeHandlerInterface $handler): void
    {
        $this->handlers->push($handler);
    }

    /**
     * Process all registered attributes using their respective handlers.
     *
     * This method triggers the attribute processing workflow by calling
     * the handle() method on each registered handler. This typically occurs
     * during application boot to ensure all attribute-defined components
     * are properly registered with Laravel before request handling begins.
     *
     * The processing order follows the handler registration order, allowing
     * for predictable attribute processing sequences if needed.
     */
    public function processAttributes(): void
    {
        $this->handlers->each(function (AttributeHandlerInterface $handler) {
            $handler->handle();
        });
    }

    /**
     * Find all classes decorated with a specific attribute.
     *
     * This method queries the composer-attribute-collector's compiled attribute
     * data to find all classes that have been decorated with the specified attribute.
     * The results are returned as a Laravel Collection for convenient manipulation.
     *
     * @param string $attributeClass Fully qualified class name of the attribute to search for
     *
     * @return Collection<array{class: string, attribute: object}> Collection of classes with their attribute instances
     */
    public function findClassesWithAttribute(string $attributeClass): Collection
    {
        // Query the compiled attribute data for class targets
        $targets = Attributes::findTargetClasses($attributeClass);

        // Transform the raw data into a more convenient format
        return collect($targets)->map(function ($target) {
            return [
                'class' => $target->name,
                'attribute' => $target->attribute,
            ];
        });
    }

    /**
     * Find all methods decorated with a specific attribute.
     *
     * This method is particularly useful for discovering controller methods
     * with Route attributes, event handler methods with Listen attributes,
     * or any other method-level attribute usage.
     *
     * @param string $attributeClass Fully qualified class name of the attribute to search for
     *
     * @return Collection<array{class: string, method: string, attribute: object}> Collection of methods with their attribute instances
     */
    public function findMethodsWithAttribute(string $attributeClass): Collection
    {
        // Query the compiled attribute data for method targets
        $targets = Attributes::findTargetMethods($attributeClass);

        // Transform the raw data to include class, method, and attribute information
        return collect($targets)->map(function ($target) {
            return [
                'class' => $target->class,
                'method' => $target->name,
                'attribute' => $target->attribute,
            ];
        });
    }

    /**
     * Find all properties decorated with a specific attribute.
     *
     * This method enables discovery of class properties that have been
     * decorated with attributes, useful for dependency injection,
     * configuration binding, or other property-level attribute usage.
     *
     * @param string $attributeClass Fully qualified class name of the attribute to search for
     *
     * @return Collection<array{class: string, property: string, attribute: object}> Collection of properties with their attribute instances
     */
    public function findPropertiesWithAttribute(string $attributeClass): Collection
    {
        // Query the compiled attribute data for property targets
        $targets = Attributes::findTargetProperties($attributeClass);

        // Transform the raw data to include class, property, and attribute information
        return collect($targets)->map(function ($target) {
            return [
                'class' => $target->class,
                'property' => $target->name,
                'attribute' => $target->attribute,
            ];
        });
    }

    /**
     * Get all attributes for a specific class.
     *
     * This method retrieves all attributes that have been applied to a given class,
     * providing a comprehensive view of the class's attribute decorations.
     *
     * @param string $className Fully qualified class name to query
     *
     * @return object Object containing all attributes for the specified class
     */
    public function getAttributesForClass(string $className): object
    {
        return Attributes::forClass($className);
    }

    /**
     * Get all collected attributes from the composer-attribute-collector.
     *
     * This method returns all attributes that have been collected during
     * the composer build process, providing a comprehensive view of all
     * attribute usage across the application.
     *
     * Note: Since the composer-attribute-collector doesn't provide a direct 'all()' method,
     * this implementation returns an empty array. Use specific finder methods like
     * findClassesWithAttribute(), findMethodsWithAttribute(), etc. instead.
     *
     * @return array Array of all collected attributes
     */
    public function getAttributes(): array
    {
        // The composer-attribute-collector library doesn't provide an 'all()' method.
        // Instead, it provides targeted search methods for specific attribute classes.
        // To get all attributes, you should use the specific finder methods:
        // - findClassesWithAttribute($attributeClass)
        // - findMethodsWithAttribute($attributeClass)
        // - findPropertiesWithAttribute($attributeClass)
        return [];
    }

    /**
     * Get all attributes of a specific type.
     *
     * This method finds all instances of a specific attribute class across
     * classes, methods, and properties in the application.
     *
     * @param string $attributeClass Fully qualified class name of the attribute type to filter by
     *
     * @return array Array of attributes matching the specified type, including their targets
     */
    public function getAttributesByType(string $attributeClass): array
    {
        $attributes = [];

        // Get all class attributes of the specified type
        $classTargets = $this->findClassesWithAttribute($attributeClass);
        foreach ($classTargets as $target) {
            $attributes[] = [
                'type' => 'class',
                'target' => $target['class'],
                'attribute' => $target['attribute'],
            ];
        }

        // Get all method attributes of the specified type
        $methodTargets = $this->findMethodsWithAttribute($attributeClass);
        foreach ($methodTargets as $target) {
            $attributes[] = [
                'type' => 'method',
                'target' => $target['class'].'::'.$target['method'],
                'class' => $target['class'],
                'method' => $target['method'],
                'attribute' => $target['attribute'],
            ];
        }

        // Get all property attributes of the specified type
        $propertyTargets = $this->findPropertiesWithAttribute($attributeClass);
        foreach ($propertyTargets as $target) {
            $attributes[] = [
                'type' => 'property',
                'target' => $target['class'].'::$'.$target['property'],
                'class' => $target['class'],
                'property' => $target['property'],
                'attribute' => $target['attribute'],
            ];
        }

        return $attributes;
    }
}

<?php

namespace Pixielity\LaravelAttributeCollector\Facades;

use Illuminate\Support\Facades\Facade;
use Pixielity\LaravelAttributeCollector\Services\AttributeRegistry as AttributeRegistryService;

/**
 * @method static \Illuminate\Support\Collection findClassesWithAttribute(string $attributeClass)
 * @method static \Illuminate\Support\Collection findMethodsWithAttribute(string $attributeClass)
 * @method static \Illuminate\Support\Collection findPropertiesWithAttribute(string $attributeClass)
 * @method static object                         getAttributesForClass(string $className)
 * @method static void                           registerHandler(\Pixielity\LaravelAttributeCollector\Interfaces\AttributeHandlerInterface $handler)
 * @method static void                           processAttributes()
 */
class AttributeRegistry extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AttributeRegistryService::class;
    }
}

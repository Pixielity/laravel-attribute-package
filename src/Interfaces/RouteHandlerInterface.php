<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Interfaces;

/**
 * Interface for Route-specific Attribute Handlers
 *
 * This interface extends the base handler interface with route-specific
 * functionality, following ISP by segregating route-related concerns.
 */
interface RouteHandlerInterface extends AttributeHandlerInterface
{
    /**
     * Register a single route from attribute data.
     *
     * @param  array  $methodData  Route attribute data
     */
    public function registerRoute(array $methodData): void;
}

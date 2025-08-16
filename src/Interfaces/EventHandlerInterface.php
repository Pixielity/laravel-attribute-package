<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Interfaces;

/**
 * Interface for Event-specific Attribute Handlers
 *
 * This interface handles event listener registration and management,
 * segregated from other handler types following ISP.
 */
interface EventHandlerInterface extends AttributeHandlerInterface
{
    /**
     * Register an event listener from attribute data.
     *
     * @param  array  $methodData  Event listener attribute data
     */
    public function registerListener(array $methodData): void;
}

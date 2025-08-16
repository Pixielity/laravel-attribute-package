<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Interfaces;

/**
 * Base Interface for All Attribute Handlers
 *
 * This is the core interface that all attribute handlers must implement.
 * Following the Interface Segregation Principle, this interface only
 * contains the essential method that all handlers need.
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
interface AttributeHandlerInterface
{
    /**
     * Process and handle attributes of the handler's specific type.
     */
    public function handle(): void;
}

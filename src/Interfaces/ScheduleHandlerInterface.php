<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Interfaces;

/**
 * Interface for Schedule-specific Attribute Handlers
 *
 * This interface handles scheduled task registration,
 * following ISP by isolating scheduling concerns.
 */
interface ScheduleHandlerInterface extends AttributeHandlerInterface
{
    /**
     * Register a scheduled task from attribute data.
     *
     * @param array $methodData Schedule attribute data
     */
    public function registerScheduledTask(array $methodData): void;
}

<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Attributes;

use Attribute;

/**
 * Listen Attribute for Laravel Event Listener Registration
 *
 * This attribute enables developers to register event listeners directly on methods or classes
 * using PHP 8+ attributes, eliminating the need for manual registration in EventServiceProvider.
 *
 * The Listen attribute supports all standard Laravel event listener features including:
 * - Multiple event types per listener
 * - Queue configuration for asynchronous processing
 * - Retry attempts and timeout configuration
 * - Both method-level and class-level listener registration
 *
 * Usage Examples:
 * ```php
 * #[Listen('App\Events\UserRegistered')]
 * public function handleUserRegistered($event) { }
 *
 * #[Listen(['App\Events\UserRegistered', 'App\Events\UserUpdated'], queue: 'emails')]
 * public function handleUserEvents($event) { }
 *
 * #[Listen('App\Events\OrderProcessed', queue: 'orders', tries: 3, timeout: 120)]
 * public function processOrder($event) { }
 * ```
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class Listen
{
    /**
     * Create a new Listen attribute instance.
     *
     * @param  string|array<string>  $events  Single event class name or array of event class names to listen for
     * @param  string|null  $queue  Optional queue name for asynchronous processing (null for synchronous)
     * @param  int  $tries  Number of retry attempts if the listener fails (default: 1)
     * @param  int  $timeout  Maximum execution time in seconds before timing out (default: 60)
     */
    public function __construct(
        /** @var array<string> Array of event class names this listener handles */
        public string|array $events,

        /** @var string|null Queue name for async processing, null for sync execution */
        public ?string $queue = null,

        /** @var int Number of retry attempts on failure */
        public int $tries = 1,

        /** @var int Maximum execution timeout in seconds */
        public int $timeout = 60
    ) {
        if (is_string($this->events)) {
            $this->events = [$this->events];
        }
    }
}

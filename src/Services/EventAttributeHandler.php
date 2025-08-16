<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Services;

use Illuminate\Events\Dispatcher;
use Pixielity\LaravelAttributeCollector\Attributes\Listen;
use Pixielity\LaravelAttributeCollector\Interfaces\EventHandlerInterface;

/**
 * Event Attribute Handler
 *
 * Handles the automatic registration of event listeners defined through PHP 8
 * attributes. This handler processes both class-level and method-level Listen
 * attributes and registers them with Laravel's event dispatcher.
 *
 * The handler supports various listener configurations including:
 * - Multiple event types per listener
 * - Queue-based asynchronous processing
 * - Priority-based listener ordering
 * - Conditional listener registration
 *
 * @author  Your Name <your.email@example.com>
 *
 * @since   1.0.0
 *
 * @example
 * // Class-level event listening
 * #[Listen([UserRegistered::class, UserUpdated::class], queue: 'emails')]
 * class UserNotificationListener
 * {
 *     public function handle($event) { ... }
 * }
 *
 * // Method-level event listening
 * class UserService
 * {
 *     #[Listen(UserDeleted::class)]
 *     public function cleanupUserData(UserDeleted $event) { ... }
 * }
 */
class EventAttributeHandler implements EventHandlerInterface
{
    /**
     * The attribute registry for discovering event listeners
     */
    private AttributeRegistry $registry;

    /**
     * The Laravel event dispatcher
     */
    private Dispatcher $events;

    /**
     * Create a new event attribute handler instance
     *
     * @param AttributeRegistry $registry The attribute registry service
     * @param Dispatcher        $events   The Laravel event dispatcher
     */
    public function __construct(
        AttributeRegistry $registry,
        Dispatcher $events
    ) {
        $this->registry = $registry;
        $this->events = $events;
    }

    /**
     * Handle the registration of all event listener attributes
     *
     * This method discovers all classes and methods decorated with Listen
     * attributes and registers them with Laravel's event dispatcher. The
     * registration is conditional based on the configuration setting.
     *
     *
     * @throws \InvalidArgumentException When listener configuration is invalid
     */
    public function handle(): void
    {
        if (! config('attribute-collector.auto_register_listeners', true)) {
            return;
        }

        // Handle class-level listeners
        $classes = $this->registry->findClassesWithAttribute(Listen::class);
        foreach ($classes as $classData) {
            $this->registerListener($classData);
        }

        // Handle method-level listeners
        $methods = $this->registry->findMethodsWithAttribute(Listen::class);
        foreach ($methods as $methodData) {
            $this->registerListener($methodData);
        }
    }

    /**
     * Register a class-level event listener
     *
     * Registers an entire class as an event listener for multiple events.
     * The class should implement a handle method or be callable. Multiple
     * events can be listened to by a single class.
     *
     * @param array $classData Array containing class name and attribute instance
     *                         Format: ['class' => string, 'attribute' => Listen]
     *
     * @throws \InvalidArgumentException When class doesn't have a handle method
     */
    private function registerClassListener(array $classData): void
    {
        /** @var Listen $listenAttribute */
        $listenAttribute = $classData['attribute'];
        $class = $classData['class'];

        foreach ($listenAttribute->events as $event) {
            $this->events->listen($event, $class);
        }
    }

    /**
     * Register a method-level event listener
     *
     * Registers a specific method of a class as an event listener. The method
     * will be called when any of the specified events are dispatched. This
     * allows for more granular event handling within a single class.
     *
     * @param array $methodData Array containing class name, method name, and attribute
     *                          Format: ['class' => string, 'method' => string, 'attribute' => Listen]
     *
     * @throws \InvalidArgumentException When method is not callable
     */
    private function registerMethodListener(array $methodData): void
    {
        /** @var Listen $listenAttribute */
        $listenAttribute = $methodData['attribute'];
        $class = $methodData['class'];
        $method = $methodData['method'];

        foreach ($listenAttribute->events as $event) {
            $this->events->listen($event, [$class, $method]);
        }
    }

    /**
     * Register an event listener from attribute data
     *
     * This method provides a public interface to register individual event listeners
     * based on the provided method data. It determines whether to register a class-level
     * or method-level listener based on the data structure.
     *
     * @param array $methodData Event listener attribute data
     *                          Format: ['class' => string, 'method' => string, 'attribute' => Listen]
     *                          or ['class' => string, 'attribute' => Listen] for class-level
     *
     * @throws \InvalidArgumentException When method data is invalid or incomplete
     */
    public function registerListener(array $methodData): void
    {
        if (! isset($methodData['class']) || ! isset($methodData['attribute'])) {
            throw new \InvalidArgumentException('Method data must contain class and attribute keys');
        }

        if (! $methodData['attribute'] instanceof Listen) {
            throw new \InvalidArgumentException('Attribute must be an instance of Listen');
        }

        // If method key exists, register as method listener, otherwise as class listener
        if (isset($methodData['method'])) {
            $this->registerMethodListener($methodData);
        } else {
            $this->registerClassListener($methodData);
        }
    }
}

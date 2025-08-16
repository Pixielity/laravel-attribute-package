<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Services;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Container\Container;
use Pixielity\LaravelAttributeCollector\Attributes\Schedule as ScheduleAttribute;
use Pixielity\LaravelAttributeCollector\Interfaces\ScheduleHandlerInterface;

/**
 * Job Attribute Handler
 *
 * Handles the registration and configuration of scheduled jobs defined through
 * PHP 8 attributes. This handler processes both class-level and method-level
 * Schedule attributes and automatically registers them with Laravel's scheduler.
 *
 * The handler supports various scheduling configurations including:
 * - Custom cron expressions
 * - Timezone specifications
 * - Overlap prevention
 * - Background execution
 * - Job descriptions
 *
 * @author  Your Name <your.email@example.com>
 *
 * @since   1.0.0
 *
 * @example
 * // Class-level scheduling
 * #[Schedule('0 2 * * *', description: 'Daily cleanup')]
 * class CleanupJob
 * {
 *     public function __invoke() { ... }
 * }
 *
 * // Method-level scheduling
 * class TaskService
 * {
 *     #[Schedule('*\/5 * * * *', withoutOverlapping: true)]
 *     public function processQueue() { ... }
 * }
 */
class JobAttributeHandler implements ScheduleHandlerInterface
{
    /**
     * The attribute registry for discovering scheduled jobs
     */
    private AttributeRegistry $registry;

    /**
     * The Laravel container for dependency injection
     */
    private Container $container;

    /**
     * Create a new job attribute handler instance
     *
     * @param  AttributeRegistry  $registry  The attribute registry service
     * @param  Container  $container  The Laravel service container
     */
    public function __construct(
        AttributeRegistry $registry,
        Container $container
    ) {
        $this->registry = $registry;
        $this->container = $container;
    }

    /**
     * Handle the registration of all scheduled job attributes
     *
     * This method discovers all classes and methods decorated with Schedule
     * attributes and registers them with Laravel's scheduler. The registration
     * is conditional based on the configuration setting.
     *
     *
     * @throws \InvalidArgumentException When schedule attribute configuration is invalid
     */
    public function handle(): void
    {
        if (! config('attribute-collector.auto_register_scheduled_jobs', true)) {
            return;
        }

        // Handle class-level scheduled jobs
        $classes = $this->registry->findClassesWithAttribute(ScheduleAttribute::class);
        foreach ($classes as $classData) {
            $this->registerClassSchedule($classData);
        }

        // Handle method-level scheduled jobs
        $methods = $this->registry->findMethodsWithAttribute(ScheduleAttribute::class);
        foreach ($methods as $methodData) {
            $this->registerMethodSchedule($methodData);
        }
    }

    /**
     * Register a class-level scheduled job
     *
     * Registers an entire class as a scheduled job. The class should implement
     * an __invoke method or be callable. The schedule configuration is applied
     * from the Schedule attribute.
     *
     * @param  array  $classData  Array containing class name and attribute instance
     *                            Format: ['class' => string, 'attribute' => ScheduleAttribute]
     *
     * @throws \InvalidArgumentException When class is not callable
     */
    private function registerClassSchedule(array $classData): void
    {
        /** @var ScheduleAttribute $scheduleAttribute */
        $scheduleAttribute = $classData['attribute'];
        $class = $classData['class'];

        $this->container->resolving(Schedule::class, function (Schedule $schedule) use ($scheduleAttribute, $class) {
            // Create the scheduled event using the class as a callable
            $event = $schedule->call($class)->cron($scheduleAttribute->expression);

            // Apply additional configuration from the attribute
            $this->configureScheduledEvent($event, $scheduleAttribute);
        });
    }

    /**
     * Register a method-level scheduled job
     *
     * Registers a specific method of a class as a scheduled job. The method
     * will be called according to the schedule configuration defined in the
     * Schedule attribute.
     *
     * @param  array  $methodData  Array containing class name, method name, and attribute
     *                             Format: ['class' => string, 'method' => string, 'attribute' => ScheduleAttribute]
     *
     * @throws \InvalidArgumentException When method is not callable
     */
    private function registerMethodSchedule(array $methodData): void
    {
        /** @var ScheduleAttribute $scheduleAttribute */
        $scheduleAttribute = $methodData['attribute'];
        $class = $methodData['class'];
        $method = $methodData['method'];

        $this->container->resolving(Schedule::class, function (Schedule $schedule) use ($scheduleAttribute, $class, $method) {
            // Create the scheduled event using the class method as a callable
            $event = $schedule->call([$class, $method])->cron($scheduleAttribute->expression);

            // Apply additional configuration from the attribute
            $this->configureScheduledEvent($event, $scheduleAttribute);
        });
    }

    /**
     * Configure a scheduled event with attribute options
     *
     * Applies various configuration options from the Schedule attribute to the
     * Laravel scheduled event. This includes timezone, overlap prevention,
     * background execution, and description settings.
     *
     * @param  mixed  $event  The Laravel scheduled event instance
     * @param  ScheduleAttribute  $scheduleAttribute  The schedule attribute with configuration
     */
    private function configureScheduledEvent($event, ScheduleAttribute $scheduleAttribute): void
    {
        if ($scheduleAttribute->timezone) {
            $event->timezone($scheduleAttribute->timezone);
        }

        if ($scheduleAttribute->withoutOverlapping) {
            $event->withoutOverlapping();
        }

        if ($scheduleAttribute->runInBackground) {
            $event->runInBackground();
        }

        if ($scheduleAttribute->description) {
            $event->description($scheduleAttribute->description);
        }
    }

    /**
     * Register a scheduled task from attribute data
     *
     * This method implements the ScheduleHandlerInterface requirement and provides
     * a way to register individual scheduled tasks. It determines whether the task
     * is class-level or method-level and delegates to the appropriate private method.
     *
     * @param  array  $methodData  Schedule attribute data containing class, method (optional), and attribute
     *                             Format: ['class' => string, 'method' => string|null, 'attribute' => ScheduleAttribute]
     *
     * @throws \InvalidArgumentException When method data is invalid
     */
    public function registerScheduledTask(array $methodData): void
    {
        if (! isset($methodData['class']) || ! isset($methodData['attribute'])) {
            throw new \InvalidArgumentException('Method data must contain class and attribute keys');
        }

        if (! $methodData['attribute'] instanceof ScheduleAttribute) {
            throw new \InvalidArgumentException('Attribute must be an instance of ScheduleAttribute');
        }

        // If method is specified, register as method-level schedule
        if (isset($methodData['method']) && ! empty($methodData['method'])) {
            $this->registerMethodSchedule($methodData);
        } else {
            // Otherwise, register as class-level schedule
            $classData = [
                'class' => $methodData['class'],
                'attribute' => $methodData['attribute'],
            ];
            $this->registerClassSchedule($classData);
        }
    }
}

<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Attributes;

use Attribute;

/**
 * Schedule Attribute for Laravel Task Scheduling
 *
 * This attribute enables developers to define scheduled tasks directly on methods or classes
 * using PHP 8+ attributes, eliminating the need for manual registration in the console kernel.
 *
 * The Schedule attribute supports all standard Laravel scheduling features including:
 * - Cron expressions for flexible scheduling
 * - Timezone configuration for global applications
 * - Overlap prevention to avoid concurrent executions
 * - Background execution for non-blocking operations
 * - Descriptive labels for monitoring and debugging
 *
 * Usage Examples:
 * ```php
 * #[Schedule::daily()]
 * public function cleanupLogs() { }
 *
 * #[Schedule('0 2 * * *', timezone: 'America/New_York', description: 'Nightly backup')]
 * public function performBackup() { }
 *
 * #[Schedule::hourly(withoutOverlapping: true, runInBackground: true)]
 * public function processQueue() { }
 * ```
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class Schedule
{
    /**
     * Create a new Schedule attribute instance.
     *
     * @param string      $expression         Cron expression defining when the task should run (e.g., '0 0 * * *' for daily at midnight)
     * @param string|null $timezone           Timezone for schedule execution (null uses application default)
     * @param bool        $withoutOverlapping Prevent concurrent executions of the same task
     * @param bool        $runInBackground    Execute task in background without blocking other scheduled tasks
     * @param string|null $description        Human-readable description for monitoring and debugging
     */
    public function __construct(
        /** @var string Cron expression (minute hour day month weekday) */
        public string $expression,

        /** @var string|null Timezone identifier (e.g., 'America/New_York', 'UTC') */
        public ?string $timezone = null,

        /** @var bool Prevent overlapping executions using mutex locks */
        public bool $withoutOverlapping = false,

        /** @var bool Run task in background process */
        public bool $runInBackground = false,

        /** @var string|null Descriptive label for the scheduled task */
        public ?string $description = null
    ) {}

    /**
     * Create a daily schedule attribute (runs at midnight).
     *
     * Convenience method for creating daily scheduled tasks.
     * Equivalent to cron expression '0 0 * * *'.
     *
     * @param string|null $timezone Timezone for execution
     *
     * @return self New Schedule instance configured for daily execution
     */
    public static function daily(?string $timezone = null): self
    {
        return new self('0 0 * * *', $timezone);
    }

    /**
     * Create an hourly schedule attribute (runs at the top of each hour).
     *
     * Convenience method for creating hourly scheduled tasks.
     * Equivalent to cron expression '0 * * * *'.
     *
     * @param string|null $timezone Timezone for execution
     *
     * @return self New Schedule instance configured for hourly execution
     */
    public static function hourly(?string $timezone = null): self
    {
        return new self('0 * * * *', $timezone);
    }

    /**
     * Create a per-minute schedule attribute (runs every minute).
     *
     * Convenience method for creating tasks that run every minute.
     * Equivalent to cron expression '* * * * *'.
     * Use with caution as this creates high-frequency execution.
     *
     * @param string|null $timezone Timezone for execution
     *
     * @return self New Schedule instance configured for per-minute execution
     */
    public static function everyMinute(?string $timezone = null): self
    {
        return new self('* * * * *', $timezone);
    }

    /**
     * Create a weekly schedule attribute (runs on Sundays at midnight).
     *
     * Convenience method for creating weekly scheduled tasks.
     * Equivalent to cron expression '0 0 * * 0' (Sunday = 0).
     *
     * @param string|null $timezone Timezone for execution
     *
     * @return self New Schedule instance configured for weekly execution
     */
    public static function weekly(?string $timezone = null): self
    {
        return new self('0 0 * * 0', $timezone);
    }

    /**
     * Create a monthly schedule attribute (runs on the 1st of each month at midnight).
     *
     * Convenience method for creating monthly scheduled tasks.
     * Equivalent to cron expression '0 0 1 * *'.
     *
     * @param string|null $timezone Timezone for execution
     *
     * @return self New Schedule instance configured for monthly execution
     */
    public static function monthly(?string $timezone = null): self
    {
        return new self('0 0 1 * *', $timezone);
    }
}

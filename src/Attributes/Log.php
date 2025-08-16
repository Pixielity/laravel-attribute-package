<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Attributes;

use Attribute;

/**
 * Logging Attribute for Automatic Method Logging
 *
 * This attribute enables automatic logging of method calls, parameters,
 * return values, and execution time. It provides a declarative way to
 * add comprehensive logging without modifying method implementations.
 *
 * Features:
 * - Configurable log levels and channels
 * - Parameter and return value logging
 * - Execution time tracking
 * - Exception logging
 * - Conditional logging based on context
 * - Performance impact monitoring
 *
 * Usage Examples:
 *
 * Basic method logging:
 * #[Log]
 * public function processPayment($amount) { ... }
 *
 * Custom log level and channel:
 * #[Log(level: 'warning', channel: 'audit')]
 * public function deleteUser(User $user) { ... }
 *
 * Log parameters and return values:
 * #[Log(logParams: true, logReturn: true, logTime: true)]
 * public function calculateTax($amount) { ... }
 *
 * @author Your Name <your.email@example.com>
 *
 * @version 1.0.0
 *
 * @since PHP 8.1
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Log
{
    /**
     * Create a new Log attribute instance.
     *
     * @param  string  $level  Log level (debug, info, notice, warning, error, critical, alert, emergency)
     * @param  string|null  $channel  Log channel to use
     * @param  bool  $logParams  Whether to log method parameters
     * @param  bool  $logReturn  Whether to log return values
     * @param  bool  $logTime  Whether to log execution time
     * @param  bool  $logExceptions  Whether to log exceptions
     * @param  string|null  $message  Custom log message template
     * @param  array  $context  Additional context data to include
     */
    public function __construct(
        /** @var string Log level for the method execution */
        public readonly string $level = 'info',

        /** @var string|null Specific log channel to use */
        public readonly ?string $channel = null,

        /** @var bool Whether to include method parameters in log */
        public readonly bool $logParams = false,

        /** @var bool Whether to include return value in log */
        public readonly bool $logReturn = false,

        /** @var bool Whether to include execution time in log */
        public readonly bool $logTime = false,

        /** @var bool Whether to log exceptions that occur */
        public readonly bool $logExceptions = true,

        /** @var string|null Custom message template for log entries */
        public readonly ?string $message = null,

        /** @var array Additional context data to include in logs */
        public readonly array $context = []
    ) {}
}

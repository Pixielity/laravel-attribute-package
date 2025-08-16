<?php

declare(strict_types=1);

namespace Pixielity\LaravelAttributeCollector\Commands;

use Illuminate\Console\Command;
use Pixielity\LaravelAttributeCollector\Attributes\Listen;
use Pixielity\LaravelAttributeCollector\Attributes\Route;
use Pixielity\LaravelAttributeCollector\Attributes\Schedule;
use Pixielity\LaravelAttributeCollector\Services\AttributeRegistry;

/**
 * Attribute Discovery Command
 *
 * An Artisan command that discovers and displays all attributes registered
 * in the application. This command provides a comprehensive overview of
 * attribute-based configurations including routes, event listeners, and
 * scheduled jobs.
 *
 * The command supports filtering by attribute type and provides detailed
 * tabular output for easy inspection and debugging of attribute-based
 * configurations.
 *
 * @author  Your Name <your.email@example.com>
 *
 * @since   1.0.0
 *
 * @example
 * // Discover all attributes
 * php artisan attributes:discover
 *
 * // Discover only route attributes
 * php artisan attributes:discover --type=routes
 *
 * // Discover only event listeners
 * php artisan attributes:discover --type=listeners
 */
class AttributeDiscoveryCommand extends Command
{
    /**
     * The name and signature of the console command
     *
     * Supports filtering by attribute type: all, routes, listeners, schedules
     *
     * @var string
     */
    protected $signature = 'attributes:discover {--type=all : Type of attributes to discover (all, routes, listeners, schedules)}';

    /**
     * The console command description
     *
     * @var string
     */
    protected $description = 'Discover and display all attributes in the application';

    /**
     * The attribute registry service for discovering attributes
     */
    private AttributeRegistry $registry;

    /**
     * Create a new command instance
     *
     * @param AttributeRegistry $registry The attribute registry service
     */
    public function __construct(AttributeRegistry $registry)
    {
        parent::__construct();
        $this->registry = $registry;
    }

    /**
     * Execute the console command
     *
     * Processes the type option and calls the appropriate discovery method
     * to display attribute information in a formatted table.
     */
    public function handle(): void
    {
        $type = $this->option('type');

        match ($type) {
            'routes' => $this->discoverRoutes(),
            'listeners' => $this->discoverListeners(),
            'schedules' => $this->discoverSchedules(),
            default => $this->discoverAll(),
        };
    }

    /**
     * Discover and display all attribute types
     *
     * Calls all individual discovery methods to provide a comprehensive
     * overview of all attributes in the application.
     */
    private function discoverAll(): void
    {
        $this->info('Discovering all attributes...');
        $this->newLine();

        $this->discoverRoutes();
        $this->newLine();
        $this->discoverListeners();
        $this->newLine();
        $this->discoverSchedules();
    }

    /**
     * Discover and display route attributes
     *
     * Finds all methods decorated with Route attributes and displays them
     * in a formatted table showing HTTP method, URI, controller, action,
     * and route name.
     */
    private function discoverRoutes(): void
    {
        $this->info('🛣️  Route Attributes:');

        $routes = $this->registry->findMethodsWithAttribute(Route::class);

        if ($routes->isEmpty()) {
            $this->warn('No route attributes found.');

            return;
        }

        $headers = ['Method', 'URI', 'Controller', 'Action', 'Name'];
        $rows = [];

        foreach ($routes as $route) {
            /** @var Route $attribute */
            $attribute = $route['attribute'];

            $rows[] = [
                $attribute->method,
                $attribute->uri,
                class_basename($route['class']),
                $route['method'],
                $attribute->name ?? '-',
            ];
        }

        $this->table($headers, $rows);
    }

    /**
     * Discover and display event listener attributes
     *
     * Finds all classes and methods decorated with Listen attributes and
     * displays them in a formatted table showing events, listener class,
     * method, and queue configuration.
     */
    private function discoverListeners(): void
    {
        $this->info('👂 Event Listener Attributes:');

        $listeners = $this->registry->findMethodsWithAttribute(Listen::class);
        $classListeners = $this->registry->findClassesWithAttribute(Listen::class);

        if ($listeners->isEmpty() && $classListeners->isEmpty()) {
            $this->warn('No listener attributes found.');

            return;
        }

        $headers = ['Events', 'Listener', 'Method', 'Queue'];
        $rows = [];

        foreach ($listeners as $listener) {
            /** @var Listen $attribute */
            $attribute = $listener['attribute'];

            $rows[] = [
                implode(', ', $attribute->events),
                class_basename($listener['class']),
                $listener['method'],
                $attribute->queue ?? '-',
            ];
        }

        foreach ($classListeners as $listener) {
            /** @var Listen $attribute */
            $attribute = $listener['attribute'];

            $rows[] = [
                implode(', ', $attribute->events),
                class_basename($listener['class']),
                'handle',
                $attribute->queue ?? '-',
            ];
        }

        $this->table($headers, $rows);
    }

    /**
     * Discover and display scheduled job attributes
     *
     * Finds all classes and methods decorated with Schedule attributes and
     * displays them in a formatted table showing cron expression, job class,
     * method, timezone, and description.
     */
    private function discoverSchedules(): void
    {
        $this->info('⏰ Scheduled Job Attributes:');

        $schedules = $this->registry->findMethodsWithAttribute(Schedule::class);
        $classSchedules = $this->registry->findClassesWithAttribute(Schedule::class);

        if ($schedules->isEmpty() && $classSchedules->isEmpty()) {
            $this->warn('No schedule attributes found.');

            return;
        }

        $headers = ['Expression', 'Job', 'Method', 'Timezone', 'Description'];
        $rows = [];

        foreach ($schedules as $schedule) {
            /** @var Schedule $attribute */
            $attribute = $schedule['attribute'];

            $rows[] = [
                $attribute->expression,
                class_basename($schedule['class']),
                $schedule['method'],
                $attribute->timezone ?? '-',
                $attribute->description ?? '-',
            ];
        }

        foreach ($classSchedules as $schedule) {
            /** @var Schedule $attribute */
            $attribute = $schedule['attribute'];

            $rows[] = [
                $attribute->expression,
                class_basename($schedule['class']),
                '__invoke',
                $attribute->timezone ?? '-',
                $attribute->description ?? '-',
            ];
        }

        $this->table($headers, $rows);
    }
}

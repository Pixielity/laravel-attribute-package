<?php

namespace App\Jobs;

use Pixielity\LaravelAttributeCollector\Attributes\Schedule;

class ExampleScheduledJob
{
    #[Schedule('daily', timezone: 'UTC', description: 'Clean up old logs')]
    public function cleanupLogs(): void
    {
        // Cleanup logic
    }

    #[Schedule('0 */6 * * *', withoutOverlapping: true, description: 'Process pending orders')]
    public function processOrders(): void
    {
        // Process orders logic
    }

    #[Schedule('hourly', runInBackground: true)]
    public function generateReports(): void
    {
        // Generate reports logic
    }
}

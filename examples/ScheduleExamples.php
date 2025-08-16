<?php

declare(strict_types=1);

namespace Examples;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Pixielity\LaravelAttributeCollector\Attributes\Schedule;

/**
 * Schedule Attribute Usage Examples
 *
 * This class demonstrates various ways to use the Schedule attribute
 * for defining scheduled tasks directly on methods.
 */
class ScheduleExamples
{
    /**
     * Daily cleanup task example
     *
     * Runs every day at midnight to clean up old log files.
     * Uses the convenient daily() method.
     */
    #[Schedule('daily')]
    public function cleanupLogs(): void
    {
        Log::info('Starting daily log cleanup');

        // Delete log files older than 30 days
        $oldLogs = Storage::disk('logs')->files();
        foreach ($oldLogs as $log) {
            if (Storage::disk('logs')->lastModified($log) < now()->subDays(30)->timestamp) {
                Storage::disk('logs')->delete($log);
            }
        }

        Log::info('Daily log cleanup completed');
    }

    /**
     * Hourly queue processing example
     *
     * Runs every hour to process pending queue items.
     * Prevents overlapping executions and runs in background.
     */
    #[Schedule('hourly')]
    public function processQueue(): void
    {
        Log::info('Processing hourly queue items');

        // Process pending queue items
        $pendingJobs = DB::table('jobs')->where('available_at', '<=', now())->count();
        Log::info("Processing {$pendingJobs} pending jobs");

        // Queue processing logic here
    }

    /**
     * Custom cron expression example
     *
     * Runs at 2 AM every day in New York timezone.
     * Uses custom cron expression for precise timing.
     */
    #[Schedule('0 2 * * *', timezone: 'America/New_York', description: 'Nightly database backup')]
    public function performBackup(): void
    {
        Log::info('Starting nightly database backup');

        // Database backup logic
        $backupFile = 'backup_'.now()->format('Y_m_d_H_i_s').'.sql';

        // Perform backup operations
        Log::info("Database backup completed: {$backupFile}");
    }

    /**
     * Weekly report generation example
     *
     * Runs every Sunday at midnight to generate weekly reports.
     * Uses the convenient weekly() method.
     */
    #[Schedule('weekly', timezone: 'UTC')]
    public function generateWeeklyReports(): void
    {
        Log::info('Generating weekly reports');

        // Generate various weekly reports
        $this->generateSalesReport();
        $this->generateUserActivityReport();
        $this->generateSystemHealthReport();

        Log::info('Weekly reports generation completed');
    }

    /**
     * Monthly billing task example
     *
     * Runs on the 1st of each month to process billing.
     * Uses the convenient monthly() method.
     */
    #[Schedule('monthly')]
    public function processBilling(): void
    {
        Log::info('Starting monthly billing process');

        // Process monthly billing for all customers
        $customers = DB::table('customers')->where('billing_active', true)->get();

        foreach ($customers as $customer) {
            $this->processCustomerBilling($customer);
        }

        Log::info('Monthly billing process completed');
    }

    /**
     * High-frequency monitoring task example
     *
     * Runs every minute to monitor system health.
     * Use with caution due to high frequency.
     */
    #[Schedule('everyMinute')]
    public function monitorSystemHealth(): void
    {
        // Quick system health checks
        $memoryUsage = memory_get_usage(true);
        $diskSpace = disk_free_space('/');

        if ($memoryUsage > 1024 * 1024 * 1024) { // 1GB
            Log::warning('High memory usage detected', ['usage' => $memoryUsage]);
        }

        if ($diskSpace < 1024 * 1024 * 1024) { // 1GB
            Log::critical('Low disk space detected', ['free_space' => $diskSpace]);
        }
    }

    /**
     * Business hours task example
     *
     * Runs Monday to Friday at 9 AM EST.
     * Custom cron expression for business-specific timing.
     */
    #[Schedule('0 9 * * 1-5', timezone: 'America/New_York', description: 'Daily business reports')]
    public function generateBusinessReports(): void
    {
        Log::info('Generating daily business reports');

        // Generate reports only during business days
        $this->generateDailySalesReport();
        $this->generateInventoryReport();
        $this->generateCustomerServiceReport();

        Log::info('Daily business reports completed');
    }

    /**
     * Background task with overlap prevention example
     *
     * Long-running task that prevents multiple instances.
     * Runs in background without blocking other scheduled tasks.
     */
    #[Schedule('*/15 * * * *', withoutOverlapping: true, runInBackground: true, description: 'Data synchronization')]
    public function synchronizeData(): void
    {
        Log::info('Starting data synchronization');

        // Long-running data synchronization process
        $this->syncUserData();
        $this->syncProductData();
        $this->syncOrderData();

        Log::info('Data synchronization completed');
    }

    // Helper methods (would be implemented in real application)
    private function generateSalesReport(): void
    { /* Implementation */
    }

    private function generateUserActivityReport(): void
    { /* Implementation */
    }

    private function generateSystemHealthReport(): void
    { /* Implementation */
    }

    private function processCustomerBilling($customer): void
    { /* Implementation */
    }

    private function generateDailySalesReport(): void
    { /* Implementation */
    }

    private function generateInventoryReport(): void
    { /* Implementation */
    }

    private function generateCustomerServiceReport(): void
    { /* Implementation */
    }

    private function syncUserData(): void
    { /* Implementation */
    }

    private function syncProductData(): void
    { /* Implementation */
    }

    private function syncOrderData(): void
    { /* Implementation */
    }
}

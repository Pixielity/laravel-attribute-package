<?php

declare(strict_types=1);

namespace Examples;

use App\Events\OrderProcessed;
use App\Events\PaymentFailed;
use App\Events\UserRegistered;
use App\Events\UserUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Pixielity\LaravelAttributeCollector\Attributes\Listen;

/**
 * Listen Attribute Usage Examples
 *
 * This class demonstrates various ways to use the Listen attribute
 * for registering event listeners directly on methods.
 */
class ListenExamples
{
    /**
     * Basic event listener example
     *
     * Simple synchronous event listener that handles user registration.
     * Executes immediately when UserRegistered event is fired.
     */
    #[Listen(UserRegistered::class)]
    public function handleUserRegistered(UserRegistered $event): void
    {
        Log::info('New user registered', ['user_id' => $event->user->id]);

        // Send welcome email or perform other registration tasks
        Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
    }

    /**
     * Queued event listener example
     *
     * Asynchronous event listener that processes in background queue.
     * Useful for time-consuming tasks like sending emails.
     */
    #[Listen(UserRegistered::class, queue: 'emails')]
    public function sendWelcomeEmail(UserRegistered $event): void
    {
        // This will be processed asynchronously in the 'emails' queue
        Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
    }

    /**
     * Multiple events listener example
     *
     * Single listener method that handles multiple related events.
     * Useful for common processing logic across different events.
     */
    #[Listen([UserRegistered::class, UserUpdated::class], queue: 'user-processing')]
    public function handleUserEvents($event): void
    {
        // Handle both user registration and update events
        if ($event instanceof UserRegistered) {
            $this->processNewUser($event->user);
        } elseif ($event instanceof UserUpdated) {
            $this->processUpdatedUser($event->user);
        }
    }

    /**
     * High-priority listener with retry configuration
     *
     * Critical event listener with retry attempts and timeout settings.
     * Suitable for important business logic that must not fail.
     */
    #[Listen(OrderProcessed::class, queue: 'orders', tries: 3, timeout: 120)]
    public function processOrder(OrderProcessed $event): void
    {
        // Critical order processing with retry logic
        try {
            $this->updateInventory($event->order);
            $this->generateInvoice($event->order);
            $this->notifyFulfillment($event->order);
        } catch (\Exception $e) {
            Log::error('Order processing failed', [
                'order_id' => $event->order->id,
                'error' => $e->getMessage(),
            ]);
            throw $e; // Re-throw to trigger retry
        }
    }

    /**
     * Synchronous critical listener example
     *
     * Synchronous listener for events that require immediate processing.
     * No queue specified, so it runs immediately in the same process.
     */
    #[Listen(PaymentFailed::class)]
    public function handlePaymentFailure(PaymentFailed $event): void
    {
        // Immediate processing for payment failures
        Log::critical('Payment failed', [
            'user_id' => $event->user->id,
            'amount' => $event->amount,
            'reason' => $event->reason,
        ]);

        // Immediately notify user and admin
        $this->notifyPaymentFailure($event);
    }

    /**
     * Class-level listener example
     *
     * When applied to a class, the Listen attribute makes the entire
     * class an event listener (useful for dedicated listener classes).
     */
    #[Listen(UserRegistered::class, queue: 'notifications')]
    public function __invoke(UserRegistered $event): void
    {
        // This class acts as a single-purpose event listener
        $this->sendNotifications($event->user);
        $this->updateAnalytics($event->user);
        $this->triggerWelcomeWorkflow($event->user);
    }

    // Helper methods (would be implemented in real application)
    private function processNewUser($user): void
    { /* Implementation */
    }

    private function processUpdatedUser($user): void
    { /* Implementation */
    }

    private function updateInventory($order): void
    { /* Implementation */
    }

    private function generateInvoice($order): void
    { /* Implementation */
    }

    private function notifyFulfillment($order): void
    { /* Implementation */
    }

    private function notifyPaymentFailure($event): void
    { /* Implementation */
    }

    private function sendNotifications($user): void
    { /* Implementation */
    }

    private function updateAnalytics($user): void
    { /* Implementation */
    }

    private function triggerWelcomeWorkflow($user): void
    { /* Implementation */
    }
}

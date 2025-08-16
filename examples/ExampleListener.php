<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Events\UserRegistered;
use Pixielity\LaravelAttributeCollector\Attributes\Listen;

class ExampleListener
{
    #[Listen(UserRegistered::class, queue: 'emails')]
    public function handleUserRegistered(UserRegistered $event): void
    {
        // Send welcome email
    }

    #[Listen([UserLoggedIn::class, 'user.activity'], queue: 'analytics')]
    public function handleUserActivity($event): void
    {
        // Log user activity
    }
}

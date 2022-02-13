<?php

namespace App\Providers;

use App\Events\CustomerRegistered;
use App\Events\OrderCreated;
use App\Events\OrderStatusCreated;
use App\Listeners\SendTelegramNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        CustomerRegistered::class => [
            SendTelegramNotification::class,
        ],

        OrderCreated::class => [
            SendTelegramNotification::class,
        ],

        OrderStatusCreated::class => [
            SendTelegramNotification::class,
        ],
    ];
}

<?php

namespace App\Providers;

use App\Contracts\BookingNotificationProvider;
use App\Services\MailNotificationProvider;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{

    public $bindings = [
        BookingNotificationProvider::class => MailNotificationProvider::class
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Listeners;

use App\Events\BookingConfirmed;
use App\Services\MailNotificationProvider;

class SendBookingNotification
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Create the event listener.
     */
    public function __construct(
        //
    ) {}

    /**
     * Handle the event.
     */
    public function handle(BookingConfirmed $event): void
    {
        (new MailNotificationProvider())->sendBookingNotification($event->booking);
    }
}

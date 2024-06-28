<?php

namespace App\Services;

use App\Contracts\BookingNotificationProvider;
use App\Mail\BookingConfirmedEmail;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;

class MailNotificationProvider implements BookingNotificationProvider
{
    /**
     * Send a booking confirmation by email.
     */
    public function sendBookingNotification(Booking $booking)
    {
        Mail::to($booking->customer_email)->send(new BookingConfirmedEmail($booking));
    }
}

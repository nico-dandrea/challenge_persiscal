<?php

namespace App\Contracts;

use App\Models\Booking;

interface BookingNotificationProvider
{
    public function sendBookingNotification(Booking $booking);
}

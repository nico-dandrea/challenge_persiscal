<?php

namespace App\Enums;

enum BookingStatusEnum: string
{
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
}

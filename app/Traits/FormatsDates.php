<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait FormatsDates
{
    /**
     * Returns a formatted date in the format "day of week (day number) of month of year"
     *
     * @param  DateTimeInterface|WeekDay|Month|string|int|float|null  $date,
     * @return string
     */
    public function getFormattedDate(
        \DateTimeInterface|\Carbon\WeekDay|\Carbon\Month|string|int|float|null $date
    ) {
        return Carbon::parse($date)->format('l (jS) \o\f F \o\f Y');
    }
}

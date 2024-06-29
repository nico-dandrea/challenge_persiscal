<?php

namespace App\Exports\Sheets;

use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class BookingsPerYearAndMonthSheet implements FromView, WithTitle
{
    public function __construct(private int $year, private int $month) {}

    /**
     * @return Collection
     */
    public function view(): View
    {
        return view('exports.bookings', ['bookings' => Booking::query()
            ->whereYear('booking_date', $this->year)
            ->whereMonth('booking_date', $this->month)
            ->get()]);
    }

    public function title(): string
    {
        return $this->year.'-'.$this->month;
    }
}

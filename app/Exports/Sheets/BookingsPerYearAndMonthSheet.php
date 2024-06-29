<?php

namespace App\Exports\Sheets;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class BookingsPerYearAndMonthSheet implements FromCollection, WithTitle
{
    private $month;
    private $year;

    public function __construct(int $year, int $month)
    {
        $this->month = $month;
        $this->year  = $year;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return Booking
            ::query()
            ->whereYear('booking_date', $this->year)
            ->whereMonth('booking_date', $this->month)
            ->get();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->year . '-' . $this->month;
    }
}

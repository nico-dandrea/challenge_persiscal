<?php

namespace App\Exports;

use App\Exports\Sheets\BookingsPerYearAndMonthSheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BookingsExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(
        protected Collection|array $dates
    ) {}

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->dates as $date) {
            $sheets[] = new BookingsPerYearAndMonthSheet($date->year, $date->month);
        }

        return $sheets;
    }
}

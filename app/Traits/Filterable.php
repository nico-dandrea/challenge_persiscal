<?php

namespace App\Traits;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Tour;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

trait Filterable
{
    public function scopeFilter(Builder $query, array $filters)
    {
        return $this->applyFilters($query, $filters);
    }

    protected function applyFilters(Builder $query, array $filters)
    {
        $dateColumn = Arr::get($filters, 'date');
        $dateFilters = Arr::only($filters, ['from', 'to']);
        $filters = Arr::except($filters, ['date', 'from', 'to', 'order_by', 'order_direction']);
        
        foreach ($filters as $filter => $value) {
            $method = 'filter' . ucfirst(Str::camel($filter));
            if (method_exists($this, $method)) {
                $this->{$method}($query, $value);
            }
        }

        if ($dateColumn) {
            $this->applyDateFilters($query, $dateColumn, $dateFilters);
        }

        $this->applyOrderBy($query, $filters);

        return $query;
    }

    protected function filterCustomerName(Builder $query, $value)
    {
        $this->applyFilter($query, 'customer_name', $value, [
            Booking::class,
            Hotel::class => 'bookings',
            Tour::class => 'bookings',
        ]);
    }

    protected function filterTourName(Builder $query, $value)
    {
        $this->applyFilter($query, 'name', $value, [
            Booking::class => 'tour',
            Tour::class,
            Hotel::class => 'bookings.tour',
        ]);
    }

    protected function filterHotelName(Builder $query, $value)
    {
        $this->applyFilter($query, 'name', $value, [
            Booking::class => 'hotel',
            Hotel::class,
            Tour::class => 'bookings.hotel',
        ]);
    }

    protected function applyFilter(Builder $query, $column, $value, array $models)
    {
        $className = get_class($this);

        foreach ($models as $model => $relation) {
            if (is_numeric($model)) {
                $model = $relation;
                $relation = null;
            }

            if ($className === $model) {
                if ($relation) {
                    $query->whereHas($relation, function ($q) use ($column, $value) {
                        $q->where($column, 'like', "%{$value}%");
                    });
                } else {
                    $query->where($column, 'like', "%{$value}%");
                }

                return;
            }
        }
    }

    protected function applyDateFilters(Builder $query, string $dateColumn, array $dateFilters)
    {
        $dateFields = [
            'booking_date' => Booking::class,
            'start_date' => Tour::class,
            'end_date' => Tour::class,
        ];

        if (!array_key_exists($dateColumn, $dateFields)) {
            return;
        }

        $models = $dateFields[$dateColumn];
        if (is_string($models)) {
            $models = [$models];
        }

        foreach ($models as $model) {
            if (get_class($this) === $model) {
                $from = isset($dateFilters['from']) ? Carbon::parse($dateFilters['from']) : null;
                $to = isset($dateFilters['to']) ? Carbon::parse($dateFilters['to']) : null;

                if ($from && $to) {
                    $query->whereBetween($dateColumn, [$from, $to]);
                } elseif ($from) {
                    $query->where($dateColumn, '>=', $from);
                } elseif ($to) {
                    $query->where($dateColumn, '<=', $to);
                }
            }
        }
    }

    protected function applyOrderBy(Builder $query, array $filters)
    {
        $orderBy = Arr::get($filters, 'order_by');
        $orderDirection = Arr::get($filters, 'order_direction', 'asc');

        $validColumns = [
            'customer_name',
            'tour_name',
            'hotel_name',
            'booking_date',
            'start_date',
            'end_date',
        ];

        if (in_array($orderBy, $validColumns)) {
            $query->orderBy($orderBy, $orderDirection);
        }
    }
}

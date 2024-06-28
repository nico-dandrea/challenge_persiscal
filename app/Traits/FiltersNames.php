<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Tour;

trait FiltersNames
{
    public function scopeFilter(Builder $query, array $filters)
    {
        return $this->applyFilters($query, $filters);
    }

    protected function applyFilters(Builder $query, array $filters)
    {
        foreach ($filters as $filter => $value) {
            $method = 'filter' . ucfirst(Str::camel($filter));

            if (method_exists($this, $method)) {
                $this->{$method}($query, $value);
            }
        }

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
}
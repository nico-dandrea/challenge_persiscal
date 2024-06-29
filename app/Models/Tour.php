<?php

namespace App\Models;

use App\Traits\FiltersNames;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use FiltersNames, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'start_date',
        'end_date',
    ];

    public function bookings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeListing(\Illuminate\Database\Eloquent\Builder $query, array $filters)
    {
        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (isset($filters['start_date'])) {
            $query->where('start_date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('end_date', '<=', $filters['end_date']);
        }
    }
}

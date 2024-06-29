<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use Filterable, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'rating',
        'price_per_night',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeListing(\Illuminate\Database\Eloquent\Builder $query, array $filters)
    {
        if (isset($filters['min_price'])) {
            $query->where('price_per_night', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price_per_night', '<=', $filters['max_price']);
        }

        if (isset($filters['min_rating'])) {
            $query->where('rating', '>=', $filters['min_rating']);
        }

        if (isset($filters['max_rating'])) {
            $query->where('rating', '<=', $filters['max_rating']);
        }
    }
}

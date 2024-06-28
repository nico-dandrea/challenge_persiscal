<?php

namespace App\Models;

use App\Traits\FiltersNames;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use FiltersNames, HasFactory;

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
}

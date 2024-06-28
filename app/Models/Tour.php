<?php

namespace App\Models;

use App\Traits\FiltersNames;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory, FiltersNames;

    protected $fillable = [
        'name',
        'description',
        'price',
        'start_date',
        'end_date',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

<?php

namespace App\Models;

use App\Casts\BookingStatus;
use App\Traits\FiltersNames;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use FiltersNames, HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => BookingStatus::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tour_id',
        'hotel_id',
        'customer_name',
        'customer_email',
        'number_of_people',
        'booking_date',
    ];

    /**
     * Get the tour that owns the Booking
     */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /**
     * Get the hotel that owns the Booking
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Determine if the booking can be canceled.
     */
    public function canBeCanceled(): bool
    {
        return $this->status === \App\Enums\BookingStatusEnum::CONFIRMED;
    }
}

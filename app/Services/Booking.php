<?php

namespace App\Services;

use App\Http\Resources\BookingResource;
use App\Models\Booking as BookingModel;

class Booking
{
    /**
     * Creates a new booking and send a booking confirmed notification
     */
    public function create(array $data): BookingResource
    {
        $booking = BookingModel::create($data);

        \App\Events\BookingConfirmed::dispatch($booking->load('tour', 'hotel'));

        return new BookingResource($booking);
    }

    /**
     * Updates a booking
     */
    public function update(array $data, BookingModel $booking): BookingResource
    {
        $booking->update($data);

        return new BookingResource($booking);
    }
}

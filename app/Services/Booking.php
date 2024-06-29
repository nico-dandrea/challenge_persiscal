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

    /**
     * Cancels a booking
     */
    public function cancel(BookingModel $booking): void
    {
        if (!$booking->canBeCancelled()) {
            throw new \App\Exceptions\BookingCannotBeCancelledException($booking->id);
        }

        $booking->status = \App\Enums\BookingStatusEnum::CANCELLED;
        $booking->save();
    }
}

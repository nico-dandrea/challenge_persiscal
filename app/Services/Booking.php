<?php

namespace App\Services;

use App\Http\Resources\BookingResource;
use App\Models\Booking as BookingModel;
class Booking
{
	/**
	 * Creates a new booking and send a booking confirmed notification
	 * 
	 * @param array $data
	 * @return BookingResource
	 */
	public function create(array $data): BookingResource
	{
		$booking = BookingModel::create($data);

        \App\Events\BookingConfirmed::dispatch($booking->load('tour', 'hotel'));

        return new BookingResource($booking);
	}

	/**
	 * Updates a booking
	 * 
	 * @param array $data
	 * @param BookingModel $booking
	 * @return BookingResource
	 */
	public function update(array $data, BookingModel $booking): BookingResource
	{
		$booking->update($data);

		return new BookingResource($booking);
	}
}
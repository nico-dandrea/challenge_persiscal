<?php

namespace App\Exceptions;

use Exception;

class BookingCannotBeCancelledException extends Exception
{
    protected $bookingId;

    /**
     * BookingCannotBeCanceledException constructor.
     *
     * @param  int  $orderId
     */
    public function __construct(int $bookingId)
    {
        $this->bookingId = $bookingId;

        // Customize the exception message
        $message = 'Cannot cancel a confirmed booking.';

        parent::__construct($message);
    }

    /**
     * Get the exceptions's context information.
     *
     * @return array<string, mixed>
     */
    public function context(): array
    {
        return ['booking_id' => $this->bookingId];
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @return \Illuminate\Http\Response
     */
    public function render(\Illuminate\Http\Request $request)
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 422); // Unprocessable Entity
    }
}

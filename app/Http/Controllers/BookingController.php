<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse as Response;

class BookingController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Booking::query();

        if ($request->has('start_date')) {
            $query->where('booking_date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('booking_date', '<=', $request->end_date);
        }

        $bookings = $query->get();

        foreach ($bookings as $booking) {
            $booking->tour;
            $booking->hotel;
        }

        return response()->json($bookings, Response::HTTP_OK);
    }

    public function store(Request $request): Response
    {
        $validatedData = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'hotel_id' => 'required|exists:hotels,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'number_of_people' => 'required|integer|min:1',
            'booking_date' => 'required|date',
        ]);

        $booking = Booking::create($validatedData);

        \App\Events\BookingConfirmed::dispatch($booking->load('tour', 'hotel'));

        return response()->json($booking, Response::HTTP_CREATED);
    }

    public function show(Booking $booking): Response
    {
        return response()->json($booking, Response::HTTP_OK);
    }

    public function update(Request $request, Booking $booking): Response
    {
        $validatedData = $request->validate([
            'tour_id' => 'sometimes|exists:tours,id',
            'hotel_id' => 'sometimes|exists:hotels,id',
            'customer_name' => 'sometimes|string|max:255',
            'customer_email' => 'sometimes|email|max:255',
            'number_of_people' => 'sometimes|integer|min:1',
            'booking_date' => 'sometimes|date',
        ]);

        $booking->update($validatedData);
        return response()->json($booking, Response::HTTP_OK);
    }

    public function destroy(Booking $booking): Response
    {
        $booking->delete();
        return response()->json(["id" => $booking->id], Response::HTTP_OK);
    }

    public function cancel(Booking $booking): Response
    {
        try {
            $booking->status = \App\Enums\BookingStatusEnum::CANCELED;
            $booking->save();
            return response()->json([
                "message" => "The booking has been canceled successfully"
            ], Response::HTTP_OK);
        } catch (\App\Exceptions\CannotCancelConfirmedBookingException $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}

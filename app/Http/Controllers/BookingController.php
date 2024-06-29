<?php

namespace App\Http\Controllers;

use App\Exports\BookingsExport;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\JsonResponse as Response;
use Illuminate\Http\Request;

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

        $filters = $request->except(['start_date', 'end_date']);

        $bookings = $query->filter($filters)->with(['tour', 'hotel']);

        return BookingResource::collection($bookings->paginate())->response()->setStatusCode(Response::HTTP_OK);
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

        return (new BookingResource($booking))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Booking $booking): Response
    {
        return (new BookingResource($booking))->response()->setStatusCode(Response::HTTP_OK);
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

        return (new BookingResource($booking))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Booking $booking): Response
    {
        $booking->delete();

        return response()->json(['id' => $booking->id], Response::HTTP_NO_CONTENT);
    }

    public function cancel(Booking $booking): Response
    {
        try {
            $booking->status = \App\Enums\BookingStatusEnum::CANCELLED;
            $booking->save();

            return response()->json([
                'message' => 'The booking has been canceLled successfully',
            ], Response::HTTP_OK);
        } catch (\App\Exceptions\BookingCannotBeCancelledException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse | Response
    {
        // Get all the years from every single booking
        $dates = Booking::selectRaw('YEAR(booking_date) as year, MONTH(booking_date) as month')
            ->distinct()
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        if ($dates->isEmpty()) {
            return response()->json([
                'message' => 'No bookings found',
            ], Response::HTTP_NOT_FOUND);
        }

        return (new BookingsExport($dates))->download('bookings.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}

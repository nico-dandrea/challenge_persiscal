<?php

namespace App\Http\Controllers;

use App\Exports\BookingsExport;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\Booking as BookingService;
use Illuminate\Http\JsonResponse as Response;

class BookingController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    public function index(\App\Http\Requests\FilterRequest $request): Response
    {
        $filters = $request->validated();

        // Extract pagination parameters
        $page = $filters['page'] ?? 1;
        $perPage = $filters['per_page'] ?? 15;

        // Remove pagination parameters from filters
        unset($filters['page'], $filters['per_page']);
        $bookings = Booking::filter($request->validated())->with(['tour', 'hotel']);

        return BookingResource::collection($bookings->paginate($perPage, ['*'], 'page', $page))
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(\App\Http\Requests\StoreBookingRequest $request): Response
    {
        return $this->bookingService->create($request->validated())
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Booking $booking): Response
    {
        return (new BookingResource($booking))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(\App\Http\Requests\UpdateBookingRequest $request, Booking $booking): Response
    {
        return $this->bookingService->update($request->validated(), $booking)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Booking $booking): Response
    {
        $booking->delete();

        return response()->json(['id' => $booking->id], Response::HTTP_NO_CONTENT);
    }

    public function cancel(Booking $booking): Response
    {
        try {
            $this->bookingService->cancel($booking);

            return response()->json([
                'message' => sprintf('The booking (ID: %s) has been canceLled successfully', $booking->id),
            ], Response::HTTP_OK);
        } catch (\App\Exceptions\BookingCannotBeCancelledException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse|Response
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

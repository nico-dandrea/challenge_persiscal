<?php

namespace App\Http\Controllers;

use App\Http\Resources\HotelResource;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse as Response;

class HotelController extends Controller
{
    public function index(\App\Http\Requests\FilterRequest $request): Response
    {
        $hotelFilters = $request->only(['min_rating', 'max_rating', 'min_price', 'max_price']);
        $paginationFilters = $request->only(['page', 'per_page']);
        $page = $paginationFilters['page'] ?? 1;
        $perPage = $paginationFilters['per_page'] ?? 15;
        $validatedFilters = $request->validated();
        $hotels = Hotel::listing($hotelFilters)->filter($validatedFilters)->paginate($perPage, ['*'], 'page', $page);

        return HotelResource::collection($hotels)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(\App\Http\Requests\StoreHotelRequest $request): Response
    {
        $hotel = Hotel::create($request->validated());

        return (new HotelResource($hotel))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Hotel $hotel): Response
    {
        return (new HotelResource($hotel))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(\App\Http\Requests\UpdateHotelRequest $request, Hotel $hotel): Response
    {
        $hotel->update($request->validated());

        return (new HotelResource($hotel))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Hotel $hotel): Response
    {
        $hotel->delete();

        return response()->json(['id' => $hotel->id], Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\HotelResource;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse as Response;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Hotel::query();

        if ($request->has('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        if ($request->has('max_rating')) {
            $query->where('rating', '<=', $request->max_rating);
        }

        if ($request->has('min_price')) {
            $query->where('price_per_night', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        $filters = $request->except(['min_rating', 'max_rating', 'min_price', 'max_price']);

        $hotels = $query->filter($filters);

        return HotelResource::collection($hotels->paginate())->response()->setStatusCode(Response::HTTP_OK);
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

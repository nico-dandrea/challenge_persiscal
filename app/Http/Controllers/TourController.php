<?php

namespace App\Http\Controllers;

use App\Http\Resources\TourResource;
use App\Models\Tour;
use Illuminate\Http\JsonResponse as Response;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Tour::query();

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        $filters = $request->except(['min_price', 'max_price', 'start_date', 'end_date']);

        $tours = $query->filter($filters);

        return TourResource::collection($tours->paginate())->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(\App\Http\Requests\StoreTourRequest $request): Response
    {
        $tour = Tour::create($request->validated());

        return (new TourResource($tour))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Tour $tour): Response
    {
        return (new TourResource($tour))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(\App\Http\Requests\UpdateTourRequest $request, Tour $tour): Response
    {
        $tour->update($request->validated());

        return (new TourResource($tour))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Tour $tour): Response
    {
        $tour->delete();

        return response()->json(['id' => $tour->id], Response::HTTP_NO_CONTENT);
    }
}

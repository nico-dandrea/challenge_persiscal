<?php

namespace App\Http\Controllers;

use App\Http\Resources\TourResource;
use App\Models\Tour;
use Illuminate\Http\JsonResponse as Response;

class TourController extends Controller
{
    public function index(\App\Http\Requests\FilterRequest $request): Response
    {
        $tourFilters = $request->only(['start_date', 'end_date', 'min_price', 'max_price']);
        $paginationFilters = $request->only(['page', 'per_page']);
        $page = $paginationFilters['page'] ?? 1;
        $perPage = $paginationFilters['per_page'] ?? 15;
        $validatedFilters = $request->validated();
        $tours = Tour::listing($tourFilters)->filter($validatedFilters)->paginate($perPage, ['*'], 'page', $page);

        return TourResource::collection($tours)->response()->setStatusCode(Response::HTTP_OK);
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

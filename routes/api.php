<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TourController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\BookingController;

Route::apiResource('tours', TourController::class);
Route::apiResource('hotels', HotelController::class);
Route::delete('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
Route::apiResource('bookings', BookingController::class);

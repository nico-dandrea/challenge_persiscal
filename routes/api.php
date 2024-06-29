<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\TourController;
use Illuminate\Support\Facades\Route;

Route::apiResource('tours', TourController::class);
Route::apiResource('hotels', HotelController::class);
Route::delete('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
Route::get('bookings/export', [BookingController::class, 'export'])->name('bookings.export');
Route::apiResource('bookings', BookingController::class);

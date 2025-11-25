<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoomTypeController;
use App\Http\Controllers\Api\GuestController;
use App\Http\Controllers\Api\AmenityController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\RatePlanController;
use App\Http\Controllers\Api\RatePlanPriceController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\ReservationRoomController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;

Route::get('/ping', fn () => ['ok' => true]);

// Room Types
Route::apiResource('room-types', RoomTypeController::class);

// Guests
Route::apiResource('guests', GuestController::class);

// Amenities
Route::apiResource('amenities', AmenityController::class);

// Rooms
Route::apiResource('rooms', RoomController::class);

// Rate Plans
Route::apiResource('rate-plans', RatePlanController::class);

// Rate Plan Prices
Route::apiResource('rate-plan-prices', RatePlanPriceController::class);
// Endpoint extra de lookup por fecha
Route::get('rate-plan-prices/lookup', [RatePlanPriceController::class, 'getPriceForDate']);

// Reservations
Route::apiResource('reservations', ReservationController::class);

// Reservation Rooms
Route::get('/reservation-rooms', [ReservationRoomController::class, 'index']);
Route::post('/reservation-rooms', [ReservationRoomController::class, 'store']);
Route::get('/reservation-rooms/{id}', [ReservationRoomController::class, 'show']);
Route::put('/reservation-rooms/{id}', [ReservationRoomController::class, 'update']);
Route::delete('/reservation-rooms/{id}', [ReservationRoomController::class, 'destroy']);

// Roles
Route::get('/roles', [RoleController::class, 'index']);
Route::post('/roles', [RoleController::class, 'store']);
Route::get('/roles/{id}', [RoleController::class, 'show']);
Route::put('/roles/{id}', [RoleController::class, 'update']);
Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

// Users
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);


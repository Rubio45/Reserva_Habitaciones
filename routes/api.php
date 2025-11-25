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
Route::get('/guests', [GuestController::class, 'index']);
Route::post('/guests', [GuestController::class, 'store']);
Route::get('/guests/{id}', [GuestController::class, 'show']);
Route::put('/guests/{id}', [GuestController::class, 'update']);
Route::delete('/guests/{id}', [GuestController::class, 'destroy']);

// Amenities
Route::apiResource('amenities', AmenityController::class);

// Rooms
Route::apiResource('rooms', RoomController::class);

// Rate Plans
Route::get('/rate-plans', [RatePlanController::class, 'index']);
Route::post('/rate-plans', [RatePlanController::class, 'store']);
Route::get('/rate-plans/{id}', [RatePlanController::class, 'show']);
Route::put('/rate-plans/{id}', [RatePlanController::class, 'update']);
Route::delete('/rate-plans/{id}', [RatePlanController::class, 'destroy']);

// Rate Plan Prices
Route::get('/rate-plan-prices', [RatePlanPriceController::class, 'index']);
Route::post('/rate-plan-prices', [RatePlanPriceController::class, 'store']);
Route::get('/rate-plan-prices/{id}', [RatePlanPriceController::class, 'show']);
Route::put('/rate-plan-prices/{id}', [RatePlanPriceController::class, 'update']);
Route::delete('/rate-plan-prices/{id}', [RatePlanPriceController::class, 'destroy']);

// Reservations
Route::get('/reservations', [ReservationController::class, 'index']);
Route::post('/reservations', [ReservationController::class, 'store']);
Route::get('/reservations/{id}', [ReservationController::class, 'show']);
Route::put('/reservations/{id}', [ReservationController::class, 'update']);
Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);

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


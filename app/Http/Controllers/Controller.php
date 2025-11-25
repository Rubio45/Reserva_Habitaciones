<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Hotel Reservation System API',
    description: 'API REST para el sistema de reservas de hotel. Incluye gestión de amenities, tipos de habitación y habitaciones físicas.',
    contact: new OA\Contact(
        name: 'API Support',
        email: 'adiazy@uamv.edu.ni'
    )
)]
#[OA\Server(
    url: '/api',
    description: 'API Server'
)]
#[OA\Tag(
    name: 'Amenities',
    description: 'Endpoints para gestión de amenities (comodidades)'
)]
#[OA\Tag(
    name: 'Room Types',
    description: 'Endpoints para gestión de tipos de habitación'
)]
#[OA\Tag(
    name: 'Rooms',
    description: 'Endpoints para gestión de habitaciones físicas'
)]
#[OA\Tag(
    name: 'Rate Plans',
    description: 'Endpoints para gestión de planes de tarifa'
)]
#[OA\Tag(
    name: 'Rate Plan Prices',
    description: 'Endpoints para gestión de precios de planes de tarifa'
)]
#[OA\Tag(
    name: 'Guests',
    description: 'Endpoints para gestión de huéspedes/clientes'
)]
#[OA\Tag(
    name: 'Reservations',
    description: 'Endpoints para gestión de reservas'
)]
abstract class Controller
{
    //
}

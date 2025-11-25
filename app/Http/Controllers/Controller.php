<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Hotel Reservation System API',
    description: 'API REST para el sistema de reservas de hotel. Incluye gestión de amenities, tipos de habitación y habitaciones físicas.',
    contact: new OA\Contact(
        name: 'API Support',
        email: 'support@hotelreservation.com'
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
abstract class Controller
{
    //
}

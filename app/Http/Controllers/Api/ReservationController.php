<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\ReservationRoom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class ReservationController extends Controller
{
    /**
     * Lista de reservas
     * 
     * Filtros opcionales vía query params:
     * - ?status=CONFIRMED
     * - ?from=2025-01-01&to=2025-01-31 (sobre check_in)
     * - ?guest_id=5
     */
    #[OA\Get(
        path: '/api/reservations',
        summary: 'Obtener lista de reservas',
        tags: ['Reservations'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                in: 'query',
                description: 'Número de página',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'status',
                in: 'query',
                description: 'Filtrar por estado',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    enum: ['PENDING', 'CONFIRMED', 'CHECKED_IN', 'CHECKED_OUT', 'CANCELLED', 'NO_SHOW'],
                    example: 'CONFIRMED'
                )
            ),
            new OA\Parameter(
                name: 'from',
                in: 'query',
                description: 'Fecha desde (formato: YYYY-MM-DD)',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date', example: '2025-01-01')
            ),
            new OA\Parameter(
                name: 'to',
                in: 'query',
                description: 'Fecha hasta (formato: YYYY-MM-DD)',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date', example: '2025-01-31')
            ),
            new OA\Parameter(
                name: 'guest_id',
                in: 'query',
                description: 'Filtrar por ID de huésped',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 5)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de reservas obtenida exitosamente',
                content: new OA\JsonContent(
                    properties: [
                        'data' => new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        'meta' => new OA\Property(property: 'meta', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $query = Reservation::query()->with(['guest:id,first_name,last_name,email']);

        // Filtro por status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filtro por rango de fechas (check_in)
        if ($request->has('from')) {
            $query->where('check_in', '>=', $request->input('from'));
        }

        if ($request->has('to')) {
            $query->where('check_in', '<=', $request->input('to'));
        }

        // Filtro por guest_id
        if ($request->has('guest_id')) {
            $query->where('guest_id', $request->input('guest_id'));
        }

        $reservations = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'data' => ReservationResource::collection($reservations->items()),
            'meta' => [
                'current_page' => $reservations->currentPage(),
                'last_page' => $reservations->lastPage(),
                'per_page' => $reservations->perPage(),
                'total' => $reservations->total(),
            ],
        ]);
    }

    /**
     * Crear una nueva reserva
     * 
     * Crea la reserva y sus reservation_rooms en una transacción.
     * Si rooms viene en el payload, crea los registros en reservation_rooms.
     * Opcionalmente recalcula total_amount como suma de noches × precio.
     */
    #[OA\Post(
        path: '/api/reservations',
        summary: 'Crear una nueva reserva',
        tags: ['Reservations'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['code', 'guest_id', 'check_in', 'check_out', 'adults'],
                properties: [
                    'code' => new OA\Property(property: 'code', type: 'string', maxLength: 20, example: 'ABC123'),
                    'guest_id' => new OA\Property(property: 'guest_id', type: 'integer', example: 5),
                    'status' => new OA\Property(property: 'status', type: 'string', enum: ['PENDING', 'CONFIRMED', 'CHECKED_IN', 'CHECKED_OUT', 'CANCELLED', 'NO_SHOW'], nullable: true, example: 'CONFIRMED'),
                    'channel' => new OA\Property(property: 'channel', type: 'string', enum: ['DIRECT', 'PHONE', 'WALKIN', 'OTA'], nullable: true, example: 'DIRECT'),
                    'check_in' => new OA\Property(property: 'check_in', type: 'string', format: 'date', example: '2025-01-10'),
                    'check_out' => new OA\Property(property: 'check_out', type: 'string', format: 'date', example: '2025-01-12'),
                    'adults' => new OA\Property(property: 'adults', type: 'integer', example: 2),
                    'children' => new OA\Property(property: 'children', type: 'integer', nullable: true, example: 1),
                    'currency' => new OA\Property(property: 'currency', type: 'string', maxLength: 3, nullable: true, example: 'NIO'),
                    'total_amount' => new OA\Property(property: 'total_amount', type: 'number', format: 'float', nullable: true, example: 160.00),
                    'paid_amount' => new OA\Property(property: 'paid_amount', type: 'number', format: 'float', nullable: true, example: 50.00),
                    'notes' => new OA\Property(property: 'notes', type: 'string', nullable: true, example: 'Llegan tarde.'),
                    'rooms' => new OA\Property(
                        property: 'rooms',
                        type: 'array',
                        nullable: true,
                        items: new OA\Items(
                            properties: [
                                'room_type_id' => new OA\Property(property: 'room_type_id', type: 'integer', example: 1),
                                'room_id' => new OA\Property(property: 'room_id', type: 'integer', nullable: true, example: 5),
                                'rate_plan_id' => new OA\Property(property: 'rate_plan_id', type: 'integer', example: 2),
                                'nightly_price' => new OA\Property(property: 'nightly_price', type: 'number', format: 'float', example: 80.00),
                                'date_from' => new OA\Property(property: 'date_from', type: 'string', format: 'date', example: '2025-01-10'),
                                'date_to' => new OA\Property(property: 'date_to', type: 'string', format: 'date', example: '2025-01-12'),
                            ],
                            type: 'object'
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Reserva creada exitosamente'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreReservationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $rooms = $data['rooms'] ?? [];
        unset($data['rooms']);

        // Valores por defecto
        $data['status'] = $data['status'] ?? 'PENDING';
        $data['channel'] = $data['channel'] ?? 'DIRECT';
        $data['currency'] = $data['currency'] ?? 'NIO';
        $data['total_amount'] = $data['total_amount'] ?? 0;
        $data['paid_amount'] = $data['paid_amount'] ?? 0;

        DB::beginTransaction();
        try {
            // Crear la reserva
            $reservation = Reservation::create($data);

            // Crear los reservation_rooms si vienen en el payload
            if (!empty($rooms)) {
                foreach ($rooms as $roomData) {
                    ReservationRoom::create([
                        'reservation_id' => $reservation->id,
                        'room_type_id' => $roomData['room_type_id'],
                        'room_id' => $roomData['room_id'] ?? null,
                        'rate_plan_id' => $roomData['rate_plan_id'],
                        'nightly_price' => $roomData['nightly_price'],
                        'date_from' => $roomData['date_from'],
                        'date_to' => $roomData['date_to'],
                    ]);
                }

                // Opcionalmente recalcular total_amount como suma de noches × precio
                if ($data['total_amount'] == 0) {
                    $totalAmount = 0;
                    foreach ($rooms as $roomData) {
                        $from = \Carbon\Carbon::parse($roomData['date_from']);
                        $to = \Carbon\Carbon::parse($roomData['date_to']);
                        $nights = $from->diffInDays($to);
                        $totalAmount += $nights * $roomData['nightly_price'];
                    }
                    $reservation->update(['total_amount' => $totalAmount]);
                }
            }

            DB::commit();

            $reservation->load(['guest:id,first_name,last_name,email', 'rooms.roomType:id,code,name', 'rooms.room:id,room_number', 'rooms.ratePlan:id,code,name']);

            return response()->json([
                'data' => new ReservationResource($reservation),
                'message' => 'Reservation created successfully.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtener una reserva específica
     */
    #[OA\Get(
        path: '/api/reservations/{id}',
        summary: 'Obtener una reserva por ID',
        tags: ['Reservations'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID de la reserva',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Reserva obtenida exitosamente'),
            new OA\Response(response: 404, description: 'Reserva no encontrada'),
        ]
    )]
    public function show(Reservation $reservation): JsonResponse
    {
        $reservation->load([
            'guest:id,first_name,last_name,email',
            'rooms.roomType:id,code,name',
            'rooms.room:id,room_number',
            'rooms.ratePlan:id,code,name',
        ]);

        return response()->json([
            'data' => new ReservationResource($reservation),
        ]);
    }

    /**
     * Actualizar una reserva
     * 
     * Opción A: No tocar rooms en update (solo datos generales).
     * Para simplificar, aquí solo permitimos actualizar datos básicos.
     * Si quisieras permitir reemplazar lista de rooms (Opción B), 
     * necesitarías agregar lógica adicional para eliminar/crear reservation_rooms.
     */
    #[OA\Put(
        path: '/api/reservations/{id}',
        summary: 'Actualizar una reserva',
        tags: ['Reservations'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID de la reserva',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    'code' => new OA\Property(property: 'code', type: 'string', maxLength: 20),
                    'guest_id' => new OA\Property(property: 'guest_id', type: 'integer'),
                    'status' => new OA\Property(property: 'status', type: 'string', enum: ['PENDING', 'CONFIRMED', 'CHECKED_IN', 'CHECKED_OUT', 'CANCELLED', 'NO_SHOW']),
                    'channel' => new OA\Property(property: 'channel', type: 'string', enum: ['DIRECT', 'PHONE', 'WALKIN', 'OTA']),
                    'check_in' => new OA\Property(property: 'check_in', type: 'string', format: 'date'),
                    'check_out' => new OA\Property(property: 'check_out', type: 'string', format: 'date'),
                    'adults' => new OA\Property(property: 'adults', type: 'integer'),
                    'children' => new OA\Property(property: 'children', type: 'integer', nullable: true),
                    'currency' => new OA\Property(property: 'currency', type: 'string', maxLength: 3, nullable: true),
                    'total_amount' => new OA\Property(property: 'total_amount', type: 'number', format: 'float', nullable: true),
                    'paid_amount' => new OA\Property(property: 'paid_amount', type: 'number', format: 'float', nullable: true),
                    'notes' => new OA\Property(property: 'notes', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Reserva actualizada exitosamente'),
            new OA\Response(response: 404, description: 'Reserva no encontrada'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateReservationRequest $request, Reservation $reservation): JsonResponse
    {
        $data = $request->validated();
        $reservation->update($data);

        $reservation->load([
            'guest:id,first_name,last_name,email',
            'rooms.roomType:id,code,name',
            'rooms.room:id,room_number',
            'rooms.ratePlan:id,code,name',
        ]);

        return response()->json([
            'data' => new ReservationResource($reservation),
            'message' => 'Reservation updated successfully.',
        ]);
    }

    /**
     * Eliminar una reserva
     * 
     * Opción A: Borrar las reservation_rooms y luego la reservation.
     * En un sistema real no siempre se elimina físicamente, pero para este ejercicio
     * implementamos eliminación física. Alternativa (Opción B): marcar status = 'CANCELLED'.
     */
    #[OA\Delete(
        path: '/api/reservations/{id}',
        summary: 'Eliminar una reserva',
        tags: ['Reservations'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID de la reserva',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Reserva eliminada exitosamente',
                content: new OA\JsonContent(
                    properties: [
                        'message' => new OA\Property(property: 'message', type: 'string', example: 'Reservation cancelled successfully.'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Reserva no encontrada'),
        ]
    )]
    public function destroy(Reservation $reservation): JsonResponse
    {
        DB::beginTransaction();
        try {
            // Eliminar reservation_rooms primero
            $reservation->rooms()->delete();
            // Eliminar la reserva
            $reservation->delete();

            DB::commit();

            return response()->json([
                'message' => 'Reservation cancelled successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

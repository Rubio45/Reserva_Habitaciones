<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class RoomController extends Controller
{
    /**
     * Lista de habitaciones
     * 
     * Filtros opcionales vía query params:
     * - ?status=AVAILABLE
     * - ?room_type_id=1
     */
    #[OA\Get(
        path: '/api/rooms',
        summary: 'Obtener lista de habitaciones',
        tags: ['Rooms'],
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
                    enum: ['AVAILABLE', 'OUT_OF_SERVICE', 'CLEANING', 'OCCUPIED'],
                    example: 'AVAILABLE'
                )
            ),
            new OA\Parameter(
                name: 'room_type_id',
                in: 'query',
                description: 'Filtrar por tipo de habitación',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de habitaciones obtenida exitosamente',
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
        $query = Room::query()->with('roomType:id,code,name');

        // Filtro por status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filtro por room_type_id
        if ($request->has('room_type_id')) {
            $query->where('room_type_id', $request->input('room_type_id'));
        }

        $rooms = $query->orderBy('room_number')->paginate(15);

        return response()->json([
            'data' => RoomResource::collection($rooms->items()),
            'meta' => [
                'current_page' => $rooms->currentPage(),
                'last_page' => $rooms->lastPage(),
                'per_page' => $rooms->perPage(),
                'total' => $rooms->total(),
            ],
        ]);
    }

    /**
     * Crear una nueva habitación
     */
    #[OA\Post(
        path: '/api/rooms',
        summary: 'Crear una nueva habitación',
        tags: ['Rooms'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['room_type_id', 'room_number'],
                properties: [
                    'room_type_id' => new OA\Property(property: 'room_type_id', type: 'integer', example: 1),
                    'room_number' => new OA\Property(property: 'room_number', type: 'string', maxLength: 32, example: '101'),
                    'floor' => new OA\Property(property: 'floor', type: 'string', nullable: true, maxLength: 16, example: '1'),
                    'status' => new OA\Property(
                        property: 'status',
                        type: 'string',
                        nullable: true,
                        enum: ['AVAILABLE', 'OUT_OF_SERVICE', 'CLEANING', 'OCCUPIED'],
                        example: 'AVAILABLE'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Habitación creada exitosamente'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreRoomRequest $request): JsonResponse
    {
        $room = Room::create($request->validated());
        $room->load('roomType:id,code,name');

        return response()->json([
            'data' => new RoomResource($room),
            'message' => 'Room created successfully.',
        ], 201);
    }

    /**
     * Obtener una habitación específica
     */
    #[OA\Get(
        path: '/api/rooms/{id}',
        summary: 'Obtener una habitación por ID',
        tags: ['Rooms'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID de la habitación',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Habitación obtenida exitosamente'),
            new OA\Response(response: 404, description: 'Habitación no encontrada'),
        ]
    )]
    public function show(Room $room): JsonResponse
    {
        $room->load('roomType:id,code,name');

        return response()->json([
            'data' => new RoomResource($room),
        ]);
    }

    /**
     * Actualizar una habitación
     */
    #[OA\Put(
        path: '/api/rooms/{id}',
        summary: 'Actualizar una habitación',
        tags: ['Rooms'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID de la habitación',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['room_type_id', 'room_number'],
                properties: [
                    'room_type_id' => new OA\Property(property: 'room_type_id', type: 'integer'),
                    'room_number' => new OA\Property(property: 'room_number', type: 'string', maxLength: 32),
                    'floor' => new OA\Property(property: 'floor', type: 'string', nullable: true, maxLength: 16),
                    'status' => new OA\Property(
                        property: 'status',
                        type: 'string',
                        nullable: true,
                        enum: ['AVAILABLE', 'OUT_OF_SERVICE', 'CLEANING', 'OCCUPIED']
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Habitación actualizada exitosamente'),
            new OA\Response(response: 404, description: 'Habitación no encontrada'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateRoomRequest $request, Room $room): JsonResponse
    {
        $room->update($request->validated());
        $room->load('roomType:id,code,name');

        return response()->json([
            'data' => new RoomResource($room),
            'message' => 'Room updated successfully.',
        ]);
    }

    /**
     * Eliminar una habitación
     * 
     * Opción A: Eliminación física (delete())
     * Alternativa: Podrías cambiar status a OUT_OF_SERVICE en lugar de eliminar
     */
    #[OA\Delete(
        path: '/api/rooms/{id}',
        summary: 'Eliminar una habitación',
        tags: ['Rooms'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID de la habitación',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Habitación eliminada exitosamente'),
            new OA\Response(response: 404, description: 'Habitación no encontrada'),
        ]
    )]
    public function destroy(Room $room): JsonResponse
    {
        $room->delete();

        return response()->json([
            'message' => 'Room deleted successfully.',
        ]);
    }
}

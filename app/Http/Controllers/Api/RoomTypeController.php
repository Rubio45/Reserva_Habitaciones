<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomTypeRequest;
use App\Http\Requests\UpdateRoomTypeRequest;
use App\Http\Resources\RoomTypeResource;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class RoomTypeController extends Controller
{
    /**
     * Lista de tipos de habitación
     */
    #[OA\Get(
        path: '/api/room-types',
        summary: 'Obtener lista de tipos de habitación',
        tags: ['Room Types'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                in: 'query',
                description: 'Número de página',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de tipos de habitación obtenida exitosamente',
                content: new OA\JsonContent(
                    properties: [
                        'data' => new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        'meta' => new OA\Property(property: 'meta', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $roomTypes = RoomType::orderBy('code')->paginate(15);

        return response()->json([
            'data' => RoomTypeResource::collection($roomTypes->items()),
            'meta' => [
                'current_page' => $roomTypes->currentPage(),
                'last_page' => $roomTypes->lastPage(),
                'per_page' => $roomTypes->perPage(),
                'total' => $roomTypes->total(),
            ],
        ]);
    }

    /**
     * Crear un nuevo tipo de habitación
     */
    #[OA\Post(
        path: '/api/room-types',
        summary: 'Crear un nuevo tipo de habitación',
        tags: ['Room Types'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['code', 'name', 'base_occupancy', 'max_occupancy'],
                properties: [
                    'code' => new OA\Property(property: 'code', type: 'string', maxLength: 32, example: 'STDQ'),
                    'name' => new OA\Property(property: 'name', type: 'string', maxLength: 120, example: 'Standard Queen'),
                    'description' => new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Habitación estándar con cama Queen.'),
                    'base_occupancy' => new OA\Property(property: 'base_occupancy', type: 'integer', example: 2),
                    'max_occupancy' => new OA\Property(property: 'max_occupancy', type: 'integer', example: 3),
                    'bed_config' => new OA\Property(property: 'bed_config', type: 'string', nullable: true, maxLength: 120, example: '1 Queen'),
                    'area_m2' => new OA\Property(property: 'area_m2', type: 'number', format: 'float', nullable: true, example: 20.5),
                    'is_active' => new OA\Property(property: 'is_active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Tipo de habitación creado exitosamente'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreRoomTypeRequest $request): JsonResponse
    {
        $roomType = RoomType::create($request->validated());

        return response()->json([
            'data' => new RoomTypeResource($roomType),
            'message' => 'Room type created successfully.',
        ], 201);
    }

    /**
     * Obtener un tipo de habitación específico
     */
    #[OA\Get(
        path: '/api/room-types/{id}',
        summary: 'Obtener un tipo de habitación por ID',
        tags: ['Room Types'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID del tipo de habitación',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Tipo de habitación obtenido exitosamente'),
            new OA\Response(response: 404, description: 'Tipo de habitación no encontrado'),
        ]
    )]
    public function show(RoomType $roomType): JsonResponse
    {
        return response()->json([
            'data' => new RoomTypeResource($roomType),
        ]);
    }

    /**
     * Actualizar un tipo de habitación
     */
    #[OA\Put(
        path: '/api/room-types/{id}',
        summary: 'Actualizar un tipo de habitación',
        tags: ['Room Types'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID del tipo de habitación',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['code', 'name', 'base_occupancy', 'max_occupancy'],
                properties: [
                    'code' => new OA\Property(property: 'code', type: 'string', maxLength: 32),
                    'name' => new OA\Property(property: 'name', type: 'string', maxLength: 120),
                    'description' => new OA\Property(property: 'description', type: 'string', nullable: true),
                    'base_occupancy' => new OA\Property(property: 'base_occupancy', type: 'integer'),
                    'max_occupancy' => new OA\Property(property: 'max_occupancy', type: 'integer'),
                    'bed_config' => new OA\Property(property: 'bed_config', type: 'string', nullable: true),
                    'area_m2' => new OA\Property(property: 'area_m2', type: 'number', format: 'float', nullable: true),
                    'is_active' => new OA\Property(property: 'is_active', type: 'boolean'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Tipo de habitación actualizado exitosamente'),
            new OA\Response(response: 404, description: 'Tipo de habitación no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateRoomTypeRequest $request, RoomType $roomType): JsonResponse
    {
        $roomType->update($request->validated());

        return response()->json([
            'data' => new RoomTypeResource($roomType),
            'message' => 'Room type updated successfully.',
        ]);
    }

    /**
     * Eliminar un tipo de habitación
     */
    #[OA\Delete(
        path: '/api/room-types/{id}',
        summary: 'Eliminar un tipo de habitación',
        tags: ['Room Types'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID del tipo de habitación',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Tipo de habitación eliminado exitosamente'),
            new OA\Response(response: 404, description: 'Tipo de habitación no encontrado'),
        ]
    )]
    public function destroy(RoomType $roomType): JsonResponse
    {
        $roomType->delete();

        return response()->json([
            'message' => 'Room type deleted successfully.',
        ]);
    }
}

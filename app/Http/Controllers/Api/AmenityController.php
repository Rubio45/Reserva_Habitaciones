<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAmenityRequest;
use App\Http\Requests\UpdateAmenityRequest;
use App\Http\Resources\AmenityResource;
use App\Models\Amenity;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class AmenityController extends Controller
{
    /**
     * Lista de amenities
     */
    #[OA\Get(
        path: '/api/amenities',
        summary: 'Obtener lista de amenities',
        tags: ['Amenities'],
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
                description: 'Lista de amenities obtenida exitosamente',
                content: new OA\JsonContent(
                    properties: [
                        'data' => new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                    'code' => new OA\Property(property: 'code', type: 'string', example: 'WIFI'),
                                    'name' => new OA\Property(property: 'name', type: 'string', example: 'High-Speed WiFi'),
                                ],
                                type: 'object'
                            )
                        ),
                        'meta' => new OA\Property(
                            property: 'meta',
                            properties: [
                                'current_page' => new OA\Property(property: 'current_page', type: 'integer', example: 1),
                                'last_page' => new OA\Property(property: 'last_page', type: 'integer', example: 1),
                                'total' => new OA\Property(property: 'total', type: 'integer', example: 2),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $amenities = Amenity::orderBy('code')->paginate(15);

        return response()->json([
            'data' => AmenityResource::collection($amenities->items()),
            'meta' => [
                'current_page' => $amenities->currentPage(),
                'last_page' => $amenities->lastPage(),
                'total' => $amenities->total(),
            ],
        ]);
    }

    /**
     * Crear un nuevo amenity
     */
    #[OA\Post(
        path: '/api/amenities',
        summary: 'Crear un nuevo amenity',
        tags: ['Amenities'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['code', 'name'],
                properties: [
                    'code' => new OA\Property(property: 'code', type: 'string', maxLength: 32, example: 'WIFI'),
                    'name' => new OA\Property(property: 'name', type: 'string', maxLength: 120, example: 'High-Speed WiFi'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Amenity creado exitosamente',
                content: new OA\JsonContent(
                    properties: [
                        'data' => new OA\Property(
                            property: 'data',
                            properties: [
                                'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                'code' => new OA\Property(property: 'code', type: 'string', example: 'WIFI'),
                                'name' => new OA\Property(property: 'name', type: 'string', example: 'High-Speed WiFi'),
                            ],
                            type: 'object'
                        ),
                        'message' => new OA\Property(property: 'message', type: 'string', example: 'Amenity created successfully.'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreAmenityRequest $request): JsonResponse
    {
        $amenity = Amenity::create($request->validated());

        return response()->json([
            'data' => new AmenityResource($amenity),
            'message' => 'Amenity created successfully.',
        ], 201);
    }

    /**
     * Obtener un amenity específico
     */
    #[OA\Get(
        path: '/api/amenities/{id}',
        summary: 'Obtener un amenity por ID',
        tags: ['Amenities'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID del amenity',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Amenity obtenido exitosamente',
                content: new OA\JsonContent(
                    properties: [
                        'data' => new OA\Property(
                            property: 'data',
                            properties: [
                                'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                'code' => new OA\Property(property: 'code', type: 'string', example: 'WIFI'),
                                'name' => new OA\Property(property: 'name', type: 'string', example: 'High-Speed WiFi'),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Amenity no encontrado'),
        ]
    )]
    public function show(Amenity $amenity): JsonResponse
    {
        return response()->json([
            'data' => new AmenityResource($amenity),
        ]);
    }

    /**
     * Actualizar un amenity
     */
    #[OA\Put(
        path: '/api/amenities/{id}',
        summary: 'Actualizar un amenity',
        tags: ['Amenities'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID del amenity',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['code', 'name'],
                properties: [
                    'code' => new OA\Property(property: 'code', type: 'string', maxLength: 32, example: 'WIFI'),
                    'name' => new OA\Property(property: 'name', type: 'string', maxLength: 120, example: 'WiFi Premium'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Amenity actualizado exitosamente',
                content: new OA\JsonContent(
                    properties: [
                        'data' => new OA\Property(
                            property: 'data',
                            properties: [
                                'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                                'code' => new OA\Property(property: 'code', type: 'string', example: 'WIFI'),
                                'name' => new OA\Property(property: 'name', type: 'string', example: 'WiFi Premium'),
                            ],
                            type: 'object'
                        ),
                        'message' => new OA\Property(property: 'message', type: 'string', example: 'Amenity updated successfully.'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Amenity no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateAmenityRequest $request, Amenity $amenity): JsonResponse
    {
        $amenity->update($request->validated());

        return response()->json([
            'data' => new AmenityResource($amenity),
            'message' => 'Amenity updated successfully.',
        ]);
    }

    /**
     * Eliminar un amenity
     */
    #[OA\Delete(
        path: '/api/amenities/{id}',
        summary: 'Eliminar un amenity',
        tags: ['Amenities'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID del amenity',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Amenity eliminado exitosamente',
                content: new OA\JsonContent(
                    properties: [
                        'message' => new OA\Property(property: 'message', type: 'string', example: 'Amenity deleted successfully.'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Amenity no encontrado'),
        ]
    )]
    public function destroy(Amenity $amenity): JsonResponse
    {
        $amenity->delete();

        return response()->json([
            'message' => 'Amenity deleted successfully.',
        ]);
    }
}

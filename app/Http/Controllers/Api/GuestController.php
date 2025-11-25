<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Http\Resources\GuestResource;
use App\Models\Guest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class GuestController extends Controller
{
    /**
     * Lista de huéspedes
     * 
     * Búsqueda simple por query param q:
     * - Busca en: first_name, last_name, email, document_number
     * 
     * Filtros opcionales:
     * - ?country_code=NI
     */
    #[OA\Get(
        path: '/api/guests',
        summary: 'Obtener lista de huéspedes',
        tags: ['Guests'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                in: 'query',
                description: 'Número de página',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'q',
                in: 'query',
                description: 'Búsqueda por nombre, email o número de documento',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'juan')
            ),
            new OA\Parameter(
                name: 'country_code',
                in: 'query',
                description: 'Filtrar por código de país (2 caracteres)',
                required: false,
                schema: new OA\Schema(type: 'string', maxLength: 2, example: 'NI')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de huéspedes obtenida exitosamente',
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
        $query = Guest::query();

        // Búsqueda simple por parámetro q
        if ($request->has('q') && !empty($request->input('q'))) {
            $searchTerm = $request->input('q');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('document_number', 'like', "%{$searchTerm}%");
            });
        }

        // Filtro por country_code
        if ($request->has('country_code')) {
            $query->where('country_code', $request->input('country_code'));
        }

        $guests = $query->orderBy('last_name')->orderBy('first_name')->paginate(15);

        return response()->json([
            'data' => GuestResource::collection($guests->items()),
            'meta' => [
                'current_page' => $guests->currentPage(),
                'last_page' => $guests->lastPage(),
                'per_page' => $guests->perPage(),
                'total' => $guests->total(),
            ],
        ]);
    }

    /**
     * Crear un nuevo huésped
     */
    #[OA\Post(
        path: '/api/guests',
        summary: 'Crear un nuevo huésped',
        tags: ['Guests'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['first_name', 'last_name'],
                properties: [
                    'first_name' => new OA\Property(property: 'first_name', type: 'string', maxLength: 120, example: 'Juan'),
                    'last_name' => new OA\Property(property: 'last_name', type: 'string', maxLength: 120, example: 'Pérez'),
                    'email' => new OA\Property(property: 'email', type: 'string', format: 'email', nullable: true, maxLength: 120, example: 'juan.perez@example.com'),
                    'phone' => new OA\Property(property: 'phone', type: 'string', nullable: true, maxLength: 40, example: '+50588888888'),
                    'document_type' => new OA\Property(property: 'document_type', type: 'string', nullable: true, maxLength: 32, example: 'PASSPORT'),
                    'document_number' => new OA\Property(property: 'document_number', type: 'string', nullable: true, maxLength: 64, example: 'A1234567'),
                    'country_code' => new OA\Property(property: 'country_code', type: 'string', nullable: true, maxLength: 2, example: 'NI'),
                    'notes' => new OA\Property(property: 'notes', type: 'string', nullable: true, example: 'Cliente frecuente.'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Huésped creado exitosamente'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreGuestRequest $request): JsonResponse
    {
        $guest = Guest::create($request->validated());

        return response()->json([
            'data' => new GuestResource($guest),
            'message' => 'Guest created successfully.',
        ], 201);
    }

    /**
     * Obtener un huésped específico
     */
    #[OA\Get(
        path: '/api/guests/{id}',
        summary: 'Obtener un huésped por ID',
        tags: ['Guests'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID del huésped',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Huésped obtenido exitosamente'),
            new OA\Response(response: 404, description: 'Huésped no encontrado'),
        ]
    )]
    public function show(Guest $guest): JsonResponse
    {
        return response()->json([
            'data' => new GuestResource($guest),
        ]);
    }

    /**
     * Actualizar un huésped
     */
    #[OA\Put(
        path: '/api/guests/{id}',
        summary: 'Actualizar un huésped',
        tags: ['Guests'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID del huésped',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    'first_name' => new OA\Property(property: 'first_name', type: 'string', maxLength: 120),
                    'last_name' => new OA\Property(property: 'last_name', type: 'string', maxLength: 120),
                    'email' => new OA\Property(property: 'email', type: 'string', format: 'email', nullable: true, maxLength: 120),
                    'phone' => new OA\Property(property: 'phone', type: 'string', nullable: true, maxLength: 40),
                    'document_type' => new OA\Property(property: 'document_type', type: 'string', nullable: true, maxLength: 32),
                    'document_number' => new OA\Property(property: 'document_number', type: 'string', nullable: true, maxLength: 64),
                    'country_code' => new OA\Property(property: 'country_code', type: 'string', nullable: true, maxLength: 2),
                    'notes' => new OA\Property(property: 'notes', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Huésped actualizado exitosamente'),
            new OA\Response(response: 404, description: 'Huésped no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateGuestRequest $request, Guest $guest): JsonResponse
    {
        $guest->update($request->validated());

        return response()->json([
            'data' => new GuestResource($guest),
            'message' => 'Guest updated successfully.',
        ]);
    }

    /**
     * Eliminar un huésped
     * 
     * Opción simple: Eliminación física (delete()).
     * Nota: En proyectos reales, a veces se evita borrar huéspedes por historial de reservas.
     * Alternativa: Podrías usar soft deletes o marcar como inactivo.
     */
    #[OA\Delete(
        path: '/api/guests/{id}',
        summary: 'Eliminar un huésped',
        tags: ['Guests'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID del huésped',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Huésped eliminado exitosamente'),
            new OA\Response(response: 404, description: 'Huésped no encontrado'),
        ]
    )]
    public function destroy(Guest $guest): JsonResponse
    {
        $guest->delete();

        return response()->json([
            'message' => 'Guest deleted successfully.',
        ]);
    }
}

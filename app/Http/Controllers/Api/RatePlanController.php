<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRatePlanRequest;
use App\Http\Requests\UpdateRatePlanRequest;
use App\Http\Resources\RatePlanResource;
use App\Models\RatePlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class RatePlanController extends Controller
{
    /**
     * Lista de planes de tarifa
     * 
     * Filtro opcional vía query param:
     * - ?is_active=1
     */
    #[OA\Get(
        path: '/api/rate-plans',
        summary: 'Obtener lista de planes de tarifa',
        tags: ['Rate Plans'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                in: 'query',
                description: 'Número de página',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'is_active',
                in: 'query',
                description: 'Filtrar por estado activo (1 o 0)',
                required: false,
                schema: new OA\Schema(type: 'integer', enum: [0, 1], example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de planes de tarifa obtenida exitosamente',
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
        $query = RatePlan::query();

        // Filtro por is_active
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $ratePlans = $query->orderBy('code')->paginate(15);

        return response()->json([
            'data' => RatePlanResource::collection($ratePlans->items()),
            'meta' => [
                'current_page' => $ratePlans->currentPage(),
                'last_page' => $ratePlans->lastPage(),
                'per_page' => $ratePlans->perPage(),
                'total' => $ratePlans->total(),
            ],
        ]);
    }

    /**
     * Crear un nuevo plan de tarifa
     */
    #[OA\Post(
        path: '/api/rate-plans',
        summary: 'Crear un nuevo plan de tarifa',
        tags: ['Rate Plans'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['code', 'name', 'meal_plan'],
                properties: [
                    'code' => new OA\Property(property: 'code', type: 'string', maxLength: 32, example: 'BAR'),
                    'name' => new OA\Property(property: 'name', type: 'string', maxLength: 120, example: 'Best Available Rate'),
                    'description' => new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Tarifa estándar flexible.'),
                    'cancellation_policy' => new OA\Property(property: 'cancellation_policy', type: 'string', nullable: true, example: 'Cancelación gratuita hasta 24 horas antes del check-in.'),
                    'meal_plan' => new OA\Property(
                        property: 'meal_plan',
                        type: 'string',
                        enum: ['RO', 'BB', 'HB', 'FB', 'AI'],
                        example: 'RO',
                        description: 'RO=Room Only, BB=Bed & Breakfast, HB=Half Board, FB=Full Board, AI=All Inclusive'
                    ),
                    'is_refundable' => new OA\Property(property: 'is_refundable', type: 'boolean', example: true),
                    'is_active' => new OA\Property(property: 'is_active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Plan de tarifa creado exitosamente'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreRatePlanRequest $request): JsonResponse
    {
        $ratePlan = RatePlan::create($request->validated());

        return response()->json([
            'data' => new RatePlanResource($ratePlan),
            'message' => 'Rate plan created successfully.',
        ], 201);
    }

    /**
     * Obtener un plan de tarifa específico
     */
    #[OA\Get(
        path: '/api/rate-plans/{id}',
        summary: 'Obtener un plan de tarifa por ID',
        tags: ['Rate Plans'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID del plan de tarifa',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Plan de tarifa obtenido exitosamente'),
            new OA\Response(response: 404, description: 'Plan de tarifa no encontrado'),
        ]
    )]
    public function show(RatePlan $ratePlan): JsonResponse
    {
        return response()->json([
            'data' => new RatePlanResource($ratePlan),
        ]);
    }

    /**
     * Actualizar un plan de tarifa
     */
    #[OA\Put(
        path: '/api/rate-plans/{id}',
        summary: 'Actualizar un plan de tarifa',
        tags: ['Rate Plans'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID del plan de tarifa',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['code', 'name', 'meal_plan'],
                properties: [
                    'code' => new OA\Property(property: 'code', type: 'string', maxLength: 32),
                    'name' => new OA\Property(property: 'name', type: 'string', maxLength: 120),
                    'description' => new OA\Property(property: 'description', type: 'string', nullable: true),
                    'cancellation_policy' => new OA\Property(property: 'cancellation_policy', type: 'string', nullable: true),
                    'meal_plan' => new OA\Property(property: 'meal_plan', type: 'string', enum: ['RO', 'BB', 'HB', 'FB', 'AI']),
                    'is_refundable' => new OA\Property(property: 'is_refundable', type: 'boolean'),
                    'is_active' => new OA\Property(property: 'is_active', type: 'boolean'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Plan de tarifa actualizado exitosamente'),
            new OA\Response(response: 404, description: 'Plan de tarifa no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateRatePlanRequest $request, RatePlan $ratePlan): JsonResponse
    {
        $ratePlan->update($request->validated());

        return response()->json([
            'data' => new RatePlanResource($ratePlan),
            'message' => 'Rate plan updated successfully.',
        ]);
    }

    /**
     * Desactivar un plan de tarifa
     * 
     * Opción B (más realista): En lugar de eliminar físicamente, marcamos is_active = false.
     * Esto permite mantener el historial y referencias en reservas existentes.
     * Alternativa (Opción A): Podrías usar $ratePlan->delete() para eliminación física.
     */
    #[OA\Delete(
        path: '/api/rate-plans/{id}',
        summary: 'Desactivar un plan de tarifa',
        tags: ['Rate Plans'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID del plan de tarifa',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Plan de tarifa desactivado exitosamente',
                content: new OA\JsonContent(
                    properties: [
                        'message' => new OA\Property(property: 'message', type: 'string', example: 'Rate plan deactivated successfully.'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Plan de tarifa no encontrado'),
        ]
    )]
    public function destroy(RatePlan $ratePlan): JsonResponse
    {
        $ratePlan->update(['is_active' => false]);

        return response()->json([
            'message' => 'Rate plan deactivated successfully.',
        ]);
    }
}

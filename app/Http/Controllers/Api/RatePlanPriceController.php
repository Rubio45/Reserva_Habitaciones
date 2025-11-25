<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRatePlanPriceRequest;
use App\Http\Requests\UpdateRatePlanPriceRequest;
use App\Http\Resources\RatePlanPriceResource;
use App\Models\RatePlanPrice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class RatePlanPriceController extends Controller
{
    /**
     * Lista de precios de planes de tarifa
     *
     * Filtros opcionales vía query params:
     * - ?rate_plan_id=1
     * - ?room_type_id=2
     * - ?from=2025-01-01
     * - ?to=2025-01-31
     */
    #[OA\Get(
        path: '/api/rate-plan-prices',
        summary: 'Obtener lista de precios de planes de tarifa',
        tags: ['Rate Plan Prices'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                in: 'query',
                description: 'Número de página',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'rate_plan_id',
                in: 'query',
                description: 'Filtrar por plan de tarifa',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'room_type_id',
                in: 'query',
                description: 'Filtrar por tipo de habitación',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 2)
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
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de precios obtenida exitosamente',
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
        $query = RatePlanPrice::query()->with(['ratePlan:id,code,name', 'roomType:id,code,name']);

        // Filtro por rate_plan_id
        if ($request->has('rate_plan_id')) {
            $query->where('rate_plan_id', $request->input('rate_plan_id'));
        }

        // Filtro por room_type_id
        if ($request->has('room_type_id')) {
            $query->where('room_type_id', $request->input('room_type_id'));
        }

        // Filtro por rango de fechas
        if ($request->has('from')) {
            $query->where('date_to', '>=', $request->input('from'));
        }

        if ($request->has('to')) {
            $query->where('date_from', '<=', $request->input('to'));
        }

        $ratePlanPrices = $query->orderBy('date_from')->paginate(15);

        return response()->json([
            'data' => RatePlanPriceResource::collection($ratePlanPrices->items()),
            'meta' => [
                'current_page' => $ratePlanPrices->currentPage(),
                'last_page' => $ratePlanPrices->lastPage(),
                'per_page' => $ratePlanPrices->perPage(),
                'total' => $ratePlanPrices->total(),
            ],
        ]);
    }

    /**
     * Crear un nuevo precio de plan de tarifa
     */
    #[OA\Post(
        path: '/api/rate-plan-prices',
        summary: 'Crear un nuevo precio de plan de tarifa',
        tags: ['Rate Plan Prices'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['rate_plan_id', 'room_type_id', 'date_from', 'date_to', 'occupancy', 'price'],
                properties: [
                    'rate_plan_id' => new OA\Property(property: 'rate_plan_id', type: 'integer', example: 1),
                    'room_type_id' => new OA\Property(property: 'room_type_id', type: 'integer', example: 2),
                    'date_from' => new OA\Property(property: 'date_from', type: 'string', format: 'date', example: '2025-01-01'),
                    'date_to' => new OA\Property(property: 'date_to', type: 'string', format: 'date', example: '2025-01-31'),
                    'occupancy' => new OA\Property(property: 'occupancy', type: 'integer', example: 2),
                    'price' => new OA\Property(property: 'price', type: 'number', format: 'float', example: 80.00),
                    'extra_adult' => new OA\Property(property: 'extra_adult', type: 'number', format: 'float', nullable: true, example: 10.00),
                    'extra_child' => new OA\Property(property: 'extra_child', type: 'number', format: 'float', nullable: true, example: 5.00),
                    'currency' => new OA\Property(property: 'currency', type: 'string', maxLength: 3, nullable: true, example: 'NIO'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Precio creado exitosamente'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreRatePlanPriceRequest $request): JsonResponse
    {
        $ratePlanPrice = RatePlanPrice::create($request->validated());
        $ratePlanPrice->load(['ratePlan:id,code,name', 'roomType:id,code,name']);

        return response()->json([
            'data' => new RatePlanPriceResource($ratePlanPrice),
            'message' => 'Rate plan price created successfully.',
        ], 201);
    }

    /**
     * Obtener un precio de plan de tarifa específico
     */
    #[OA\Get(
        path: '/api/rate-plan-prices/{id}',
        summary: 'Obtener un precio de plan de tarifa por ID',
        tags: ['Rate Plan Prices'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID del precio',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Precio obtenido exitosamente'),
            new OA\Response(response: 404, description: 'Precio no encontrado'),
        ]
    )]
    public function show(RatePlanPrice $ratePlanPrice): JsonResponse
    {
        $ratePlanPrice->load(['ratePlan:id,code,name', 'roomType:id,code,name']);

        return response()->json([
            'data' => new RatePlanPriceResource($ratePlanPrice),
        ]);
    }

    /**
     * Actualizar un precio de plan de tarifa
     */
    #[OA\Put(
        path: '/api/rate-plan-prices/{id}',
        summary: 'Actualizar un precio de plan de tarifa',
        tags: ['Rate Plan Prices'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID del precio',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    'rate_plan_id' => new OA\Property(property: 'rate_plan_id', type: 'integer'),
                    'room_type_id' => new OA\Property(property: 'room_type_id', type: 'integer'),
                    'date_from' => new OA\Property(property: 'date_from', type: 'string', format: 'date'),
                    'date_to' => new OA\Property(property: 'date_to', type: 'string', format: 'date'),
                    'occupancy' => new OA\Property(property: 'occupancy', type: 'integer'),
                    'price' => new OA\Property(property: 'price', type: 'number', format: 'float'),
                    'extra_adult' => new OA\Property(property: 'extra_adult', type: 'number', format: 'float', nullable: true),
                    'extra_child' => new OA\Property(property: 'extra_child', type: 'number', format: 'float', nullable: true),
                    'currency' => new OA\Property(property: 'currency', type: 'string', maxLength: 3, nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Precio actualizado exitosamente'),
            new OA\Response(response: 404, description: 'Precio no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateRatePlanPriceRequest $request, RatePlanPrice $ratePlanPrice): JsonResponse
    {
        $ratePlanPrice->update($request->validated());
        $ratePlanPrice->load(['ratePlan:id,code,name', 'roomType:id,code,name']);

        return response()->json([
            'data' => new RatePlanPriceResource($ratePlanPrice),
            'message' => 'Rate plan price updated successfully.',
        ]);
    }

    /**
     * Eliminar un precio de plan de tarifa
     */
    #[OA\Delete(
        path: '/api/rate-plan-prices/{id}',
        summary: 'Eliminar un precio de plan de tarifa',
        tags: ['Rate Plan Prices'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID del precio',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Precio eliminado exitosamente'),
            new OA\Response(response: 404, description: 'Precio no encontrado'),
        ]
    )]
    public function destroy(RatePlanPrice $ratePlanPrice): JsonResponse
    {
        $ratePlanPrice->delete();

        return response()->json([
            'message' => 'Rate plan price deleted successfully.',
        ]);
    }

    /**
     * Lookup de precio para una fecha específica
     *
     * Busca el primer registro donde:
     * - rate_plan_id = ?
     * - room_type_id = ?
     * - occupancy = ?
     * - date_from <= date <= date_to
     */
    #[OA\Get(
        path: '/api/rate-plan-prices/lookup',
        summary: 'Buscar precio para una fecha específica',
        tags: ['Rate Plan Prices'],
        parameters: [
            new OA\Parameter(
                name: 'rate_plan_id',
                in: 'query',
                required: true,
                description: 'ID del plan de tarifa',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'room_type_id',
                in: 'query',
                required: true,
                description: 'ID del tipo de habitación',
                schema: new OA\Schema(type: 'integer', example: 2)
            ),
            new OA\Parameter(
                name: 'date',
                in: 'query',
                required: true,
                description: 'Fecha a consultar (formato: YYYY-MM-DD)',
                schema: new OA\Schema(type: 'string', format: 'date', example: '2025-01-10')
            ),
            new OA\Parameter(
                name: 'occupancy',
                in: 'query',
                required: true,
                description: 'Ocupación (número de huéspedes)',
                schema: new OA\Schema(type: 'integer', example: 2)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Precio encontrado exitosamente'),
            new OA\Response(response: 404, description: 'No se encontró precio para los criterios especificados'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function getPriceForDate(Request $request): JsonResponse
    {
        $request->validate([
            'rate_plan_id' => ['required', 'integer', 'exists:rate_plans,id'],
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            'date' => ['required', 'date'],
            'occupancy' => ['required', 'integer', 'min:1'],
        ]);

        $ratePlanPrice = RatePlanPrice::where('rate_plan_id', $request->input('rate_plan_id'))
            ->where('room_type_id', $request->input('room_type_id'))
            ->where('occupancy', $request->input('occupancy'))
            ->forDate($request->input('date'))
            ->with(['ratePlan:id,code,name', 'roomType:id,code,name'])
            ->first();

        if (!$ratePlanPrice) {
            return response()->json([
                'message' => 'No rate plan price found for the specified criteria.',
            ], 404);
        }

        return response()->json([
            'data' => new RatePlanPriceResource($ratePlanPrice),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RatePlanPrice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RatePlanPriceController extends Controller
{
    public function index()
    {
        $ratePlanPrices = RatePlanPrice::with(['ratePlan:id,code,name', 'roomType:id,code,name,description'])
            ->orderBy('date_from')
            ->get();

        return response()->json($ratePlanPrices);
    }

    public function show($id)
    {
        $ratePlanPrice = RatePlanPrice::with([
            'ratePlan:id,code,name,description,is_refundable',
            'roomType:id,code,name,description,base_occupancy,max_occupancy'
        ])->findOrFail($id);

        return response()->json($ratePlanPrice);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rate_plan_id' => ['required','exists:rate_plans,id'],
            'room_type_id' => ['required','exists:room_types,id'],
            'date_from'    => ['required','date'],
            'date_to'      => ['required','date','after:date_from'],
            'occupancy'    => ['required','integer','min:1'],
            'price'        => ['required','numeric','min:0'],
            'extra_adult'  => ['nullable','numeric','min:0'],
            'extra_child'  => ['nullable','numeric','min:0'],
            'currency'     => ['required','string','size:3'],
        ]);

        $ratePlanPrice = RatePlanPrice::create($data);

        return response()->json($ratePlanPrice, 201);
    }

    public function update(Request $request, $id)
    {
        $ratePlanPrice = RatePlanPrice::findOrFail($id);

        $data = $request->validate([
            'rate_plan_id' => ['required','exists:rate_plans,id'],
            'room_type_id' => ['required','exists:room_types,id'],
            'date_from'    => ['required','date'],
            'date_to'      => ['required','date','after:date_from'],
            'occupancy'    => ['required','integer','min:1'],
            'price'        => ['required','numeric','min:0'],
            'extra_adult'  => ['nullable','numeric','min:0'],
            'extra_child'  => ['nullable','numeric','min:0'],
            'currency'     => ['required','string','size:3'],
        ]);

        $ratePlanPrice->update($data);

        return response()->json($ratePlanPrice);
    }

    public function destroy($id)
    {
        $ratePlanPrice = RatePlanPrice::findOrFail($id);
        $ratePlanPrice->delete();

        return response()->json(['message' => 'Rate plan price deleted successfully'], 200);
    }
}
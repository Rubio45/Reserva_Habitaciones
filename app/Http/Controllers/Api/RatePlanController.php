<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RatePlan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RatePlanController extends Controller
{
    public function index()
    {
        $ratePlans = RatePlan::with(['prices:id,rate_plan_id,room_type_id,date_from,date_to,price,currency'])
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        return response()->json($ratePlans);
    }

    public function show($id)
    {
        $ratePlan = RatePlan::with([
            'prices.roomType:id,code,name,description',
            'reservationRooms.reservation:id,code,guest_id,status,check_in,check_out',
            'reservationRooms.reservation.guest:id,first_name,last_name,email'
        ])->findOrFail($id);

        return response()->json($ratePlan);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'               => ['required','string','max:50', Rule::unique('rate_plans','code')],
            'name'               => ['required','string','max:200'],
            'description'        => ['nullable','string'],
            'cancellation_policy'=> ['nullable','string'],
            'meal_plan'          => ['nullable','string','max:100'],
            'is_refundable'      => ['required','boolean'],
            'is_active'          => ['required','boolean'],
        ]);

        $ratePlan = RatePlan::create($data);

        return response()->json($ratePlan, 201);
    }

    public function update(Request $request, $id)
    {
        $ratePlan = RatePlan::findOrFail($id);

        $data = $request->validate([
            'code'               => ['required','string','max:50', Rule::unique('rate_plans','code')->ignore($ratePlan->id)],
            'name'               => ['required','string','max:200'],
            'description'        => ['nullable','string'],
            'cancellation_policy'=> ['nullable','string'],
            'meal_plan'          => ['nullable','string','max:100'],
            'is_refundable'      => ['required','boolean'],
            'is_active'          => ['required','boolean'],
        ]);

        $ratePlan->update($data);

        return response()->json($ratePlan);
    }

    public function destroy($id)
    {
        $ratePlan = RatePlan::findOrFail($id);
        $ratePlan->delete();

        return response()->json(['message' => 'Rate plan deleted successfully'], 200);
    }
}
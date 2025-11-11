<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReservationRoom;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservationRoomController extends Controller
{
    public function index()
    {
        $reservationRooms = ReservationRoom::with([
            'reservation:id,code,guest_id,status,check_in,check_out',
            'reservation.guest:id,first_name,last_name,email',
            'roomType:id,code,name,description',
            'room:id,room_number,floor,status',
            'ratePlan:id,code,name,meal_plan,is_refundable'
        ])->orderBy('created_at','desc')->paginate(15);

        return response()->json($reservationRooms);
    }

    public function show($id)
    {
        $reservationRoom = ReservationRoom::with([
            'reservation.guest:id,first_name,last_name,email,phone,document_type',
            'roomType:id,code,name,description,base_occupancy,max_occupancy',
            'room:id,room_number,floor,status',
            'ratePlan:id,code,name,description,meal_plan,cancellation_policy,is_refundable'
        ])->findOrFail($id);

        return response()->json($reservationRoom);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reservation_id' => ['required','exists:reservations,id'],
            'room_type_id'   => ['required','exists:room_types,id'],
            'room_id'        => ['nullable','exists:rooms,id'],
            'rate_plan_id'   => ['required','exists:rate_plans,id'],
            'nightly_price'  => ['required','numeric','min:0'],
            'date_from'      => ['required','date'],
            'date_to'        => ['required','date','after:date_from'],
        ]);

        $reservationRoom = ReservationRoom::create($data);

        return response()->json($reservationRoom, 201);
    }

    public function update(Request $request, $id)
    {
        $reservationRoom = ReservationRoom::findOrFail($id);

        $data = $request->validate([
            'reservation_id' => ['required','exists:reservations,id'],
            'room_type_id'   => ['required','exists:room_types,id'],
            'room_id'        => ['nullable','exists:rooms,id'],
            'rate_plan_id'   => ['required','exists:rate_plans,id'],
            'nightly_price'  => ['required','numeric','min:0'],
            'date_from'      => ['required','date'],
            'date_to'        => ['required','date','after:date_from'],
        ]);

        $reservationRoom->update($data);

        return response()->json($reservationRoom);
    }

    public function destroy($id)
    {
        $reservationRoom = ReservationRoom::findOrFail($id);
        $reservationRoom->delete();

        return response()->json(['message' => 'Reservation room deleted successfully'], 200);
    }
}
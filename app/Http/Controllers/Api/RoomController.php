<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['type:id,code,name', 'reservationRooms:id,reservation_id,date_from,date_to'])
            ->orderBy('room_number')
            ->get();

        return response()->json($rooms);
    }

    public function show($id)
    {
        $room = Room::with([
            'type:id,code,name,description,base_occupancy,max_occupancy',
            'reservationRooms.reservation:id,code,guest_id,status,check_in,check_out',
            'reservationRooms.reservation.guest:id,first_name,last_name,email'
        ])->findOrFail($id);

        return response()->json($room);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_type_id' => ['required','exists:room_types,id'],
            'room_number'  => ['required','string','max:20', Rule::unique('rooms','room_number')],
            'floor'        => ['nullable','integer'],
            'status'       => ['required','string','max:50'],
        ]);

        $room = Room::create($data);

        return response()->json($room, 201);
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $data = $request->validate([
            'room_type_id' => ['required','exists:room_types,id'],
            'room_number'  => ['required','string','max:20', Rule::unique('rooms','room_number')->ignore($room->id)],
            'floor'        => ['nullable','integer'],
            'status'       => ['required','string','max:50'],
        ]);

        $room->update($data);

        return response()->json($room);
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return response()->json(['message' => 'Room deleted successfully'], 200);
    }
}
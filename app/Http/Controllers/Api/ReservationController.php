<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with([
            'guest:id,first_name,last_name,email,phone',
            'roomsLines.roomType:id,code,name,description',
            'roomsLines.room:id,room_number,floor',
            'roomsLines.ratePlan:id,code,name,meal_plan',
            'invoice:id,total,paid,status',
            'payments:id,amount,payment_method,status,paid_at'
        ])->orderBy('created_at','desc')->paginate(15);

        return response()->json($reservations);
    }

    public function show($id)
    {
        $reservation = Reservation::with([
            'guest:id,first_name,last_name,email,phone,document_type,document_number,country_code',
            'roomsLines.roomType:id,code,name,description,base_occupancy,max_occupancy',
            'roomsLines.room:id,room_number,floor,status',
            'roomsLines.ratePlan:id,code,name,description,meal_plan,is_refundable',
            'invoice:id,total,paid,status,issue_date,due_date',
            'payments:id,amount,payment_method,status,paid_at,reference'
        ])->findOrFail($id);

        return response()->json($reservation);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'         => ['required','string','max:50', Rule::unique('reservations','code')],
            'guest_id'     => ['required','exists:guests,id'],
            'status'       => ['required','string','max:50'],
            'adults'       => ['required','integer','min:1'],
            'children'     => ['nullable','integer','min:0'],
            'currency'     => ['required','string','size:3'],
            'total_amount' => ['required','numeric','min:0'],
            'paid_amount'  => ['nullable','numeric','min:0'],
            'check_in'     => ['required','date'],
            'check_out'    => ['required','date','after:check_in'],
            'channel'      => ['nullable','string','max:100'],
        ]);

        $reservation = Reservation::create($data);

        return response()->json($reservation, 201);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $data = $request->validate([
            'code'         => ['required','string','max:50', Rule::unique('reservations','code')->ignore($reservation->id)],
            'guest_id'     => ['required','exists:guests,id'],
            'status'       => ['required','string','max:50'],
            'adults'       => ['required','integer','min:1'],
            'children'     => ['nullable','integer','min:0'],
            'currency'     => ['required','string','size:3'],
            'total_amount' => ['required','numeric','min:0'],
            'paid_amount'  => ['nullable','numeric','min:0'],
            'check_in'     => ['required','date'],
            'check_out'    => ['required','date','after:check_in'],
            'channel'      => ['nullable','string','max:100'],
        ]);

        $reservation->update($data);

        return response()->json($reservation);
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted successfully'], 200);
    }
}
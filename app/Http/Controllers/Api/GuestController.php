<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuestController extends Controller
{
    public function index()
    {
        return response()->json(
            Guest::query()->latest()->paginate(15)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'      => ['required','string','max:120'],
            'last_name'       => ['nullable','string','max:120'],
            'email'           => ['nullable','email','max:120', Rule::unique('guests','email')],
            'phone'           => ['nullable','string','max:40'],
            'document_type'   => ['nullable','string','max:32'],
            'document_number' => ['nullable','string','max:120'],
            'country_code'    => ['nullable','string','size:2'],
            'notes'           => ['nullable','string'],
        ]);

        $guest = Guest::create($data);

        return response()->json($guest, 201);
    }

    public function show($id)
    {
        $guest = Guest::with(['reservations:id,code,status,check_in,check_out,total_amount'])
            ->findOrFail($id);

        return response()->json($guest);
    }

    public function update(Request $request, $id)
    {
        $guest = Guest::findOrFail($id);

        $data = $request->validate([
            'first_name'      => ['required','string','max:120'],
            'last_name'       => ['nullable','string','max:120'],
            'email'           => ['nullable','email','max:120', Rule::unique('guests','email')->ignore($guest->id)],
            'phone'           => ['nullable','string','max:40'],
            'document_type'   => ['nullable','string','max:32'],
            'document_number' => ['nullable','string','max:120'],
            'country_code'    => ['nullable','string','size:2'],
            'notes'           => ['nullable','string'],
        ]);

        $guest->update($data);

        return response()->json($guest);
    }

    public function destroy($id)
    {
        $guest = Guest::findOrFail($id);
        $guest->delete();

        return response()->json(['message' => 'Guest deleted successfully'], 200);
    }
}

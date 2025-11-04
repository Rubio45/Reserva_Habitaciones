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
}

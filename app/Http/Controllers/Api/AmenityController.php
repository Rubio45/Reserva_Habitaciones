<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AmenityController extends Controller
{
    public function index()
    {
        $amenities = Amenity::with(['roomTypes:id,code,name'])
            ->orderBy('code')
            ->get();

        return response()->json($amenities);
    }

    public function show($id)
    {
        $amenity = Amenity::with(['roomTypes:id,code,name,description,base_occupancy'])
            ->findOrFail($id);

        return response()->json($amenity);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required','string','max:50', Rule::unique('amenities','code')],
            'name' => ['required','string','max:200'],
        ]);

        $amenity = Amenity::create($data);

        return response()->json($amenity, 201);
    }

    public function update(Request $request, $id)
    {
        $amenity = Amenity::findOrFail($id);

        $data = $request->validate([
            'code' => ['required','string','max:50', Rule::unique('amenities','code')->ignore($amenity->id)],
            'name' => ['required','string','max:200'],
        ]);

        $amenity->update($data);

        return response()->json($amenity);
    }

    public function destroy($id)
    {
        $amenity = Amenity::findOrFail($id);
        $amenity->delete();

        return response()->json(['message' => 'Amenity deleted successfully'], 200);
    }
}
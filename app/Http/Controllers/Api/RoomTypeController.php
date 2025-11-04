<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomType;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::query()
            ->with('amenities:id,code,name')
            ->withCount('rooms')
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        return response()->json($roomTypes);
    }

    public function show($id)
    {
        $roomType = RoomType::with(['amenities:id,code,name','rooms:id,room_type_id,room_number,status'])
            ->findOrFail($id);

        return response()->json($roomType);
    }
}

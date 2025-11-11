<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = RoomType::all();
        
        $rooms = [
            // Standard Rooms (STD)
            ['room_type_id' => 1, 'room_number' => '101', 'floor' => 1, 'status' => 'available'],
            ['room_type_id' => 1, 'room_number' => '102', 'floor' => 1, 'status' => 'available'],
            ['room_type_id' => 1, 'room_number' => '201', 'floor' => 2, 'status' => 'available'],
            ['room_type_id' => 1, 'room_number' => '202', 'floor' => 2, 'status' => 'occupied'],
            
            // Deluxe Rooms (DLX)
            ['room_type_id' => 2, 'room_number' => '301', 'floor' => 3, 'status' => 'available'],
            ['room_type_id' => 2, 'room_number' => '302', 'floor' => 3, 'status' => 'maintenance'],
            ['room_type_id' => 2, 'room_number' => '401', 'floor' => 4, 'status' => 'available'],
            ['room_type_id' => 2, 'room_number' => '402', 'floor' => 4, 'status' => 'occupied'],
            
            // Suite Rooms (STE)
            ['room_type_id' => 3, 'room_number' => '501', 'floor' => 5, 'status' => 'available'],
            ['room_type_id' => 3, 'room_number' => '502', 'floor' => 5, 'status' => 'available'],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
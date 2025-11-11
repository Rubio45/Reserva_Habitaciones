<?php

namespace Database\Seeders;

use App\Models\RoomType;
use App\Models\Amenity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = [
            [
                'code' => 'STD',
                'description' => 'Standard Room',
                'base_occupancy' => 2,
                'max_occupancy' => 2,
                'bed_config' => '1 Queen Bed',
                'area_m2' => 25,
                'is_active' => true,
                'amenities' => ['WIFI', 'TV', 'AC', 'MINIBAR', 'SAFE']
            ],
            [
                'code' => 'DLX',
                'description' => 'Deluxe Room',
                'base_occupancy' => 2,
                'max_occupancy' => 3,
                'bed_config' => '1 King Bed',
                'area_m2' => 35,
                'is_active' => true,
                'amenities' => ['WIFI', 'TV', 'AC', 'MINIBAR', 'SAFE', 'BALCONY', 'BATHTUB']
            ],
            [
                'code' => 'STE',
                'description' => 'Suite',
                'base_occupancy' => 4,
                'max_occupancy' => 6,
                'bed_config' => '1 King Bed + 1 Sofa Bed',
                'area_m2' => 60,
                'is_active' => true,
                'amenities' => ['WIFI', 'TV', 'AC', 'MINIBAR', 'SAFE', 'BALCONY', 'BATHTUB', 'HAIRDRYER', 'OCEANVIEW']
            ]
        ];

        foreach ($roomTypes as $roomTypeData) {
            $amenities = $roomTypeData['amenities'];
            unset($roomTypeData['amenities']);
            
            $roomType = RoomType::create($roomTypeData);
            
            // Attach amenities
            $amenityIds = Amenity::whereIn('code', $amenities)->pluck('id');
            $roomType->amenities()->attach($amenityIds);
        }
    }
}
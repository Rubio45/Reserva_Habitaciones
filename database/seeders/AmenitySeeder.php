<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            ['code' => 'WIFI', 'name' => 'Free WiFi'],
            ['code' => 'TV', 'name' => 'Cable TV'],
            ['code' => 'AC', 'name' => 'Air Conditioning'],
            ['code' => 'MINIBAR', 'name' => 'Minibar'],
            ['code' => 'SAFE', 'name' => 'In-room Safe'],
            ['code' => 'BALCONY', 'name' => 'Private Balcony'],
            ['code' => 'OCEANVIEW', 'name' => 'Ocean View'],
            ['code' => 'BATHTUB', 'name' => 'Bathtub'],
            ['code' => 'SHOWER', 'name' => 'Rain Shower'],
            ['code' => 'HAIRDRYER', 'name' => 'Hair Dryer'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }
    }
}
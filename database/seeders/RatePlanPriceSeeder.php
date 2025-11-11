<?php

namespace Database\Seeders;

use App\Models\RatePlanPrice;
use App\Models\RatePlan;
use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatePlanPriceSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ratePlans = RatePlan::all();
        $roomTypes = RoomType::all();
        
        $baseDate = now()->startOfMonth();
        $endDate = now()->endOfYear();
        
        // Create prices for each rate plan and room type combination
        foreach ($ratePlans as $ratePlan) {
            foreach ($roomTypes as $roomType) {
                $basePrice = match($roomType->code) {
                    'STD' => 100,
                    'DLX' => 150,
                    'STE' => 250,
                    default => 100
                };
                
                // Apply rate plan multiplier
                $price = match($ratePlan->code) {
                    'BAR' => $basePrice,
                    'NRF' => $basePrice * 0.9, // 10% discount
                    'BB' => $basePrice * 1.2, // 20% increase
                    default => $basePrice
                };
                
                RatePlanPrice::create([
                    'rate_plan_id' => $ratePlan->id,
                    'room_type_id' => $roomType->id,
                    'date_from' => $baseDate,
                    'date_to' => $endDate,
                    'occupancy' => 2,
                    'price' => $price,
                    'extra_adult' => 30,
                    'extra_child' => 15,
                    'currency' => 'USD',
                ]);
            }
        }
    }
}
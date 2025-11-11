<?php

namespace Database\Seeders;

use App\Models\RatePlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatePlanSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ratePlans = [
            [
                'code' => 'BAR',
                'name' => 'Best Available Rate',
                'description' => 'Standard rate with free cancellation up to 24 hours before check-in',
                'cancellation_policy' => 'Free cancellation up to 24 hours before arrival',
                'meal_plan' => 'Room Only',
                'is_refundable' => true,
                'is_active' => true,
            ],
            [
                'code' => 'NRF',
                'name' => 'Non-Refundable Rate',
                'description' => 'Discounted rate, no cancellation allowed',
                'cancellation_policy' => 'No cancellation allowed, 100% charge applies',
                'meal_plan' => 'Room Only',
                'is_refundable' => false,
                'is_active' => true,
            ],
            [
                'code' => 'BB',
                'name' => 'Bed & Breakfast',
                'description' => 'Rate includes daily breakfast for 2 guests',
                'cancellation_policy' => 'Free cancellation up to 48 hours before arrival',
                'meal_plan' => 'Bed & Breakfast',
                'is_refundable' => true,
                'is_active' => true,
            ],
        ];

        foreach ($ratePlans as $ratePlan) {
            RatePlan::create($ratePlan);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Guest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuestSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guests = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@email.com',
                'phone' => '+1-555-0101',
                'document_type' => 'Passport',
                'document_number' => 'A1234567',
                'country_code' => 'US',
                'notes' => 'VIP guest, prefers high floors',
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@email.com',
                'phone' => '+44-20-1234-5678',
                'document_type' => 'Driver License',
                'document_number' => 'DL987654',
                'country_code' => 'GB',
                'notes' => 'Vegetarian meal preference',
            ],
            [
                'first_name' => 'Carlos',
                'last_name' => 'Rodriguez',
                'email' => 'carlos.rodriguez@email.com',
                'phone' => '+34-91-555-1234',
                'document_type' => 'NIE',
                'document_number' => 'NIE12345678',
                'country_code' => 'ES',
                'notes' => 'Honeymoon couple',
            ],
            [
                'first_name' => 'Maria',
                'last_name' => 'Gonzalez',
                'email' => 'maria.gonzalez@email.com',
                'phone' => '+52-55-1234-5678',
                'document_type' => 'Passport',
                'document_number' => 'P7654321',
                'country_code' => 'MX',
                'notes' => 'Frequent business traveler',
            ],
        ];

        foreach ($guests as $guest) {
            Guest::create($guest);
        }
    }
}
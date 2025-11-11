<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Administrator')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $receptionistRole = Role::where('name', 'Receptionist')->first();

        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@hotel.com',
                'password' => Hash::make('admin123'),
                'roles' => [$adminRole->id],
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@hotel.com',
                'password' => Hash::make('manager123'),
                'roles' => [$managerRole->id],
            ],
            [
                'name' => 'Reception User',
                'email' => 'reception@hotel.com',
                'password' => Hash::make('reception123'),
                'roles' => [$receptionistRole->id],
            ],
        ];

        foreach ($users as $userData) {
            $roles = $userData['roles'];
            unset($userData['roles']);
            
            $user = User::create($userData);
            $user->roles()->attach($roles);
        }
    }
}
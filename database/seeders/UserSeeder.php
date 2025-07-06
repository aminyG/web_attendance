<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);
$user = User::create([
            'name' => 'Aminy',
            'email' => 'aminyghaisan11@gmail.com',
            'password' => bcrypt('password123'),
        ]);
        // Memberikan peran 'admin' kepada user tersebut
        $user->assignRole('admin');
    }
}

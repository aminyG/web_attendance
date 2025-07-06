<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // // Membuat role admin dan super-admin jika belum ada
        // Role::firstOrCreate(['name' => 'admin']);
        // Role::firstOrCreate(['name' => 'super-admin']);

        // // Membuat user baru
        // $user = User::create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@example.com',
        //     'password' => bcrypt('password123'),
        // ]);

        // // Memberikan role admin kepada user
        // $user->assignRole('admin');

        // // Kamu bisa menambahkan user lainnya jika diperlukan
        // $user2 = User::create([
        //     'name' => 'Super Admin User',
        //     'email' => 'superadmin@example.com',
        //     'password' => bcrypt('password123'),
        // ]);

        // // Memberikan role super-admin kepada user kedua
        // $user2->assignRole('super-admin');

        $this->call(AttendanceSeeder::class);
    }

}

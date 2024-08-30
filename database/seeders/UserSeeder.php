<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan pengguna
        // $users = [
        //     [
        //         'name' => 'Admin User',
        //         'email' => 'admin@example.com',
        //         'password' => Hash::make('password123'),
        //         'role' => 'manajer', // Pastikan role ini sudah ada di tabel roles
        //     ],
        //     [
        //         'name' => 'Employee User',
        //         'email' => 'employee@example.com',
        //         'password' => Hash::make('password123'),
        //         'role' => 'karyawan', // Pastikan role ini sudah ada di tabel roles
        //     ],
        // ];

        // foreach ($users as $userData) {
        //     $user = User::create([
        //         'name' => $userData['name'],
        //         'email' => $userData['email'],
        //         'password' => $userData['password'],
        //     ]);

        //     // Assign role if Spatie Laravel Permission is used
        //     if (isset($userData['role'])) {
        //         $user->assignRole($userData['role']);
        //     }
        // }
    }
}

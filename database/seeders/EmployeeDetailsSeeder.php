<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('employee_details')->insert([
        //     [
        //         'user_id' => 1,
        //         'department_id' => 1,
        //         'position_id' => 1,
        //         'fullname' => 'John Doe',
        //         'nik' => '1234567890123456',
        //         'photo' => 'photos/john_doe.jpg',
        //         'cv' => 'cvs/john_doe_cv.pdf',
        //         'phone' => '081234567890',
        //         'gender' => 'male',
        //         'address' => 'Jl. Example No. 123, Jakarta',
        //         'hire_date' => '2023-01-15',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'user_id' => 2,
        //         'department_id' => 2,
        //         'position_id' => 2,
        //         'fullname' => 'Jane Smith',
        //         'nik' => '6543210987654321',
        //         'photo' => 'photos/jane_smith.jpg',
        //         'cv' => 'cvs/jane_smith_cv.pdf',
        //         'phone' => '081298765432',
        //         'gender' => 'female',
        //         'address' => 'Jl. Example No. 456, Bandung',
        //         'hire_date' => '2023-02-20',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     // Tambahkan data lain jika diperlukan
        // ]);
    }
}

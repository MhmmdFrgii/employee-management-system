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
        DB::table('employee_details')->insert([
            [
                'company_id' => 1,
                'user_id' => 2,
                'department_id' => 1,
                'position_id' => 1,
                'status' => 'approved',
                'name' => 'Jane Smith',
                'email' => 'jane@gmail.com',
                'photo' => 'jane_smith.jpg',
                'cv' => 'cv.jpg',
                'phone' => '081298765432',
                'gender' => 'female',
                'address' => 'Jl. Thamrin No. 5, Jakarta',
                'hire_date' => '2022-03-15',
            ],
        ]);

        DB::table('employee_details')->insert([
            [
                'company_id' => 1,
                'user_id' => 3,
                'department_id' => 2,
                'position_id' => 2,
                'status' => 'approved',
                'name' => 'John Doe',
                'email' => 'john.doe@gmail.com',
                'photo' => 'john_doe.jpg',
                'cv' => 'john_cv.jpg',
                'phone' => '081234567890',
                'gender' => 'male',
                'address' => 'Jl. Sudirman No. 10, Jakarta',
                'hire_date' => '2021-08-20',
            ],
        ]);

        DB::table('employee_details')->insert([
            [
                'company_id' => 2,
                'user_id' => 4,
                'department_id' => 1,
                'position_id' => 1,
                'status' => 'approved',
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@innovatech.com',
                'photo' => 'alice_johnson.jpg',
                'cv' => 'alice_cv.jpg',
                'phone' => '082345678901',
                'gender' => 'female',
                'address' => 'Jl. Karya No. 12, Bandung',
                'hire_date' => '2023-01-10',
            ],
            [
                'company_id' => 2,
                'user_id' => 5,
                'department_id' => 2,
                'position_id' => 2,
                'status' => 'approved',
                'name' => 'Bob Brown',
                'email' => 'bob.brown@innovatech.com',
                'photo' => 'bob_brown.jpg',
                'cv' => 'bob_cv.jpg',
                'phone' => '082987654321',
                'gender' => 'male',
                'address' => 'Jl. Maju No. 7, Surabaya',
                'hire_date' => '2023-03-22',
            ],
        ]);
    }
}
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
    }
}

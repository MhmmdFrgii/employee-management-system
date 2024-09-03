<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('positions')->insert([
            [
                'company_id' => 1,
                'name' => 'Software Engineer',
                'description' => 'Responsible for developing and maintaining software applications.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'name' => 'Product Manager',
                'description' => 'Oversees the development and strategy of the company’s products.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 2,
                'name' => 'Magang',
                'description' => 'Lorem ipsum dolor sit amet.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 2,
                'name' => 'Mentor',
                'description' => 'Lorem ipsum dolor sit amet.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

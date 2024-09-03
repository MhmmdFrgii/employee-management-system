<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Gunakan namespace yang benar untuk DB

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('departments')->insert([
            [
                'company_id' => 1,
                'name' => 'Human Resources',
                'description' => 'Handles employee relations and company policies.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'name' => 'Engineering',
                'description' => 'Develops and maintains products and systems.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 2,
                'name' => 'Web',
                'description' => 'Develops and maintains products and systems.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 2,
                'name' => 'Mobile',
                'description' => 'Develops and maintains products and systems.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

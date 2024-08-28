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
                'name' => 'Keuangan',
                'description' => 'Departemen Keuangan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Digital Marketing',
                'description' => 'Departemen Digital Marketing.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Website',
                'description' => 'Departemen Website.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Pastikan Anda mengimpor DB
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('projects')->insert([
            [
                'company_id' => 1,
                'name' => 'Project Alpha',
                'price' => 4000000,
                'description' => 'Description for Project Alpha.',
                'start_date' => Carbon::parse('2024-01-01'),
                'end_date' => Carbon::parse('2024-06-30'),
                'status' => 'completed',
                'completed_at' => Carbon::parse('2024-06-30'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 2,
                'name' => 'Project Delta',
                'price' => 1200000.00,
                'description' => 'Description for Project Delta.',
                'start_date' => Carbon::parse('2024-09-01'),
                'end_date' => Carbon::parse('2025-03-31'),
                'status' => 'active',
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('kanban_boards')->insert([
            [
                'name' => 'Kanban Alpha',
                'project_id' => '1',
                'description' => 'deskripsi'
            ],
            [
                'name' => 'Kanban Delta',
                'project_id' => '2',
                'description' => 'deskripsi'
            ]
        ]);
    }
}

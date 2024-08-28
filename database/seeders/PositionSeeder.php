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
                'name' => 'Manager',
                'description' => 'Bertanggung jawab atas manajemen umum dan pengawasan tim.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supervisor',
                'description' => 'Mengawasi pekerjaan staf dan memastikan mereka bekerja dengan benar.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff',
                'description' => 'Melaksanakan tugas operasional sehari-hari sesuai instruksi supervisor.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

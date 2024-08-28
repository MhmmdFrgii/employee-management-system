<?php

namespace Database\Seeders;

use App\Models\Applicant;
use App\Models\Department;
use App\Models\EmployeeDetail;
use App\Models\Position;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(25)->create();
        Department::factory(25)->create();
        Position::factory(25)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        User::create([
            'name' => 'Iqbal',
            'email' => 'iqbal@gmail.com',
            'password' => bcrypt('password')
        ]);

        Department::create([
            'name' => 'Keuangan',
            'description' => 'Keuangan Description'
        ]);

        $this->call([
            DepartmentSeeder::class,
            PositionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            EmployeeDetailsSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Position;
use App\Models\Applicant;
use App\Models\Attendance;
use App\Models\Department;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\EmployeeDetail;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CompanySeeder;
use Database\Seeders\ProjectSeeder;
use Database\Seeders\PositionSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\EmployeeDetailsSeeder;
use Faker\Factory as Faker;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Department::factory(25)->create();
        Position::factory(25)->create();

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
        $this->call([
            CompanySeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            PositionSeeder::class,
            EmployeeDetailsSeeder::class,
            ProjectSeeder::class,
        ]);

        $faker = Faker::create();

        // Mendapatkan semua employee
        $employees = EmployeeDetail::all();

        // Loop setiap employee untuk memberikan absensi dari tanggal 1 sampai 30
        foreach ($employees as $employee) {
            for ($day = 1; $day <= 30; $day++) {
                // Membuat tanggal dengan day ke-$day pada bulan dan tahun ini
                $date = Carbon::createFromDate(null, null, $day)->format('Y-m-d');

                // Membuat status acak
                $status = $faker->randomElement(['present', 'late', 'absent']);

                // Membuat entry attendance
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date,
                    'status' => $status,
                ]);
            }
        }
    }
}

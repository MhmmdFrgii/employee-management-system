<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $statuses = ['Active', 'Completed'];
        // $projects = [];

        // for ($month = 1; $month <= 5; $month++) {
        //     for ($i = 1; $i <= 2; $i++) {
        //         $startDate = Carbon::createFromDate(2024, $month, rand(1, 15));
        //         $endDate = (clone $startDate)->addWeeks(rand(1, 4));
        //         $status = $statuses[array_rand($statuses)];
        //         $completedAt = $status === 'Completed' ? $endDate->copy()->addDays(rand(1, 7)) : null;

        //         $projects[] = [
        //             'name' => "Project $month-$i",
        //             'description' => "Description for project $month-$i",
        //             'start_date' => $startDate->format('Y-m-d'),
        //             'end_date' => $endDate->format('Y-m-d'),
        //             'status' => $status,
        //             'completed_at' => $completedAt,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ];
        //     }
        // }

        // Project::insert($projects);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\EmployeeDetail;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        $employee_count = EmployeeDetail::count();
        $project_count = Project::count();
        $department_count = Department::count();


        return view('dashboard.index', compact('employee_count', 'project_count', 'department_count'));
    }

    public function userDashboard()
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // Ambil data proyek berdasarkan bulan dan tahun saat ini
        $activeProjects = Project::where('status', 'Active')
            ->whereYear('start_date', $currentYear)
            ->whereMonth('start_date', $currentMonth)
            ->count();

        $completedProjects = Project::where('status', 'Completed')
            ->whereYear('completed_at', $currentYear)
            ->whereMonth('completed_at', $currentMonth)
            ->count();

        // Data untuk chart bulanan
        $months = [];
        $activeCounts = [];
        $completedCounts = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->format('F');
            $activeCounts[] = Project::where('status', 'Active')
                ->whereYear('start_date', $currentYear)
                ->whereMonth('start_date', $i)
                ->count();

            $completedCounts[] = Project::where('status', 'Completed')
                ->whereYear('completed_at', $currentYear)
                ->whereMonth('completed_at', $i)
                ->count();
        }

        return view('dashboard.employee.index', [
            'activeProjects' => $activeProjects,
            'completedProjects' => $completedProjects,
            'months' => $months,
            'activeCounts' => $activeCounts,
            'completedCounts' => $completedCounts
        ]);
    }

}

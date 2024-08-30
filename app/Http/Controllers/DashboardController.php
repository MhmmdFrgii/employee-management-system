<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\EmployeeDetail;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $employee_count = EmployeeDetail::count();
        $project_count = Project::count();
        $department_count = Department::count();

        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // Ambil data proyek berdasarkan tenggat yang terdekat
        $projectsWithNearestDeadlines = Project::where('end_date', '>=', $now)
        ->orderBy('end_date', 'asc')
        ->get();

        // Ambil data proyek berdasarkan bulan dan tahun saat ini
        $activeProjects = Project::where('status', 'Active')
            ->whereYear('start_date', $currentYear)
            ->whereMonth('start_date', $currentMonth)
            ->count();

        // Data untuk chart bulanan
    $months = [];
    $activeCounts = [];

    for ($i = 1; $i <= 12; $i++) {
        $months[] = Carbon::create()->month($i)->format('F');

        // Hitung jumlah proyek yang dimulai pada bulan ini dan dinyatakan 'completed'
        $activeCounts[] = Project::where('status', 'Completed')
            ->whereYear('start_date', $currentYear)
            ->whereMonth('start_date', $i)
            ->count();
    }

    // Mendapatkan user yang sedang login
    $user = Auth::user();

    return view('dashboard.index', [
        'months' => $months,
        'activeCounts' => $activeCounts,
        'employee_count' => $employee_count,
        'project_count' => $project_count,
        'department_count' => $department_count,
        'projectsWithNearestDeadlines' => $projectsWithNearestDeadlines
    ]);
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

        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Ambil data attendance dengan relasi ke employee_detail dan user
        $attendanceData = Attendance::selectRaw('status, COUNT(*) as count')
            ->whereHas('employeeDetail', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Mendefinisikan status yang akan ditampilkan
        $statuses = ['present', 'absent', 'late', 'alpha'];
        $attendanceCounts = [];

        // Mengisi array attendanceCounts berdasarkan status yang telah didefinisikan
        foreach ($statuses as $status) {
            $attendanceCounts[] = $attendanceData[$status] ?? 0;
        }

        return view('dashboard.employee.index', [
            'activeProjects' => $activeProjects,
            'completedProjects' => $completedProjects,
            'months' => $months,
            'activeCounts' => $activeCounts,
            'completedCounts' => $completedCounts,
            'attendanceCounts' => $attendanceCounts,
        ]);
    }
}

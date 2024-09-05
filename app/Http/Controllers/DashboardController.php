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
        $company_id = Auth::user()->company->id;

        $employee_count = EmployeeDetail::where('company_id', $company_id)
            ->where('status', 'approved')
            ->whereHas('user')
            ->count();
        $applicant_count = EmployeeDetail::where('company_id', $company_id)
            ->where('status', 'pending')
            ->count();
        $project_count = Project::where('company_id', $company_id)->count();
        $project_done = Project::where('company_id', $company_id)
            ->where('status', 'completed')
            ->count();

        if ($project_count > 0) {
            $performance = ($project_done / $project_count) * 100;
            $performance = round($performance, 2);
        } else {
            $performance = 0;
        }


        $department_count = Department::where('company_id', $company_id)->count();

        $now = Carbon::now();
        $projectsWithNearestDeadlines = Project::where('end_date', '>=', $now)
            ->where('company_id', $company_id)
            ->where('status', '!=', 'completed') // Exclude completed projects
            ->orderBy('end_date', 'asc')
            ->get();

        $months = [];
        $activeCounts = [];
        $earningCounts = [];

        $currentMonth = Carbon::now()->month; // Ambil bulan saat ini
        $currentYear = Carbon::now()->year; // Ambil tahun saat ini
        $company_id = $company_id;

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i); // Mengurangi bulan secara iteratif
            $months[] = $month->format('F'); // Ambil nama bulan

            $activeCounts[] = Project::where('status', 'completed')
                ->where('company_id', $company_id)
                ->whereYear('start_date', $month->year)
                ->whereMonth('start_date', $month->month)
                ->count();

            $earningCounts[] = Project::where('status', 'completed')
                ->where('company_id', $company_id)
                ->whereYear('start_date', $month->year)
                ->whereMonth('start_date', $month->month)
                ->sum('price');
        }

        for ($i = 0; $i < 6; $i++) {
            $project_data[] = [
                $i + 1,
                $activeCounts[$i],
                $earningCounts[$i]
            ];
        }

        $departments = Department::where('company_id', $company_id)
            ->pluck('name')
            ->toArray();

        $department_data = Department::where('company_id', $company_id)
            ->withCount('employee_details')
            ->pluck('employee_details_count')
            ->toArray();

        $now = Carbon::now();
        $currentYear = $now->year;
        $months = [];
        $attendanceData = [
            'present' => [],
            'absent' => [],
            'alpha' => [],
            'late' => []
        ];

        // Loop untuk mendapatkan data 6 bulan terakhir termasuk bulan sekarang
        for ($i = 5; $i >= 0; $i--) {
            $currentMonth = $now->copy()->subMonths($i);

            // Format bulan dan tahun
            $months[] = $currentMonth->format('F Y');

            // Hitung jumlah kehadiran untuk setiap bulan
            $attendanceData['present'][] = Attendance::where('status', 'present')
                ->whereHas('employee_detail', function ($query) use ($company_id) {
                    $query->where('company_id', $company_id);
                })
                ->whereYear('created_at', $currentMonth->year)
                ->whereMonth('created_at', $currentMonth->month)
                ->count();

            $attendanceData['absent'][] = Attendance::where('status', 'absent')
                ->whereHas('employee_detail', function ($query) use ($company_id) {
                    $query->where('company_id', $company_id);
                })
                ->whereYear('created_at', $currentMonth->year)
                ->whereMonth('created_at', $currentMonth->month)
                ->count();

            $attendanceData['alpha'][] = Attendance::where('status', 'alpha')
                ->whereHas('employee_detail', function ($query) use ($company_id) {
                    $query->where('company_id', $company_id);
                })
                ->whereYear('created_at', $currentMonth->year)
                ->whereMonth('created_at', $currentMonth->month)
                ->count();

            $attendanceData['late'][] = Attendance::where('status', 'late')
                ->whereHas('employee_detail', function ($query) use ($company_id) {
                    $query->where('company_id', $company_id);
                })
                ->whereYear('created_at', $currentMonth->year)
                ->whereMonth('created_at', $currentMonth->month)
                ->count();
        }

        // Balikkan array bulan untuk menampilkan bulan terlama terlebih dahulu
    // $months = array_reverse($months);

        return view('dashboard.index', [
            'employee_count' => $employee_count,
            'project_count' => $project_count,
            'project_done' => $project_done,
            'department_count' => $department_count,
            'applicant_count' => $applicant_count,
            'months' => $months,
            'performance' => $performance,
            'project_data' => $project_data,
            'departments' => $departments,
            'department_data' => $department_data,
            'projectsWithNearestDeadlines' => $projectsWithNearestDeadlines,
            'attendanceData' => $attendanceData,
        ]);
    }


    // USER KARYAWAN
    public function userDashboard()
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // Data untuk chart bulanan
        $months = [];
        $activeCounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i); // Mengurangi bulan secara iteratif
            $months[] = $month->format('F'); // Ambil nama bulan

            $projectCounts[] = Project::where('status', 'completed')
                ->whereHas('employee_details', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })
                ->whereYear('start_date', $currentYear)
                ->whereMonth('start_date', $i)
                ->count();
        }

        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Ambil data attendance dengan relasi ke employee_detail dan user
        $attendanceData = Attendance::selectRaw('status, COUNT(*) as count')
            ->whereHas('employee_detail', function ($query) use ($user) {
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
            'months' => $months,
            'projectCounts' => $projectCounts,
            'statuses' => $statuses,
            'attendanceCounts' => $attendanceCounts,
        ]);
    }
}

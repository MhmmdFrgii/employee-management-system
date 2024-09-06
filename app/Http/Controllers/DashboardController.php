<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Project;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\EmployeeDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $newNotificationCount = $this->getNewNotificationCount();

        // Mengambil data untuk chart salaries
        $monthlyData = $this->getMonthlyData();

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

        // Ambil ID pengguna yang sedang login
        $userId = Auth::id();

        // Hitung jumlah notifikasi baru


        $newNotificationCount = Notification::where('user_id', Auth::id())->where('is_read', false)->count();

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
            'newNotificationCount' => $newNotificationCount,
            'monthlyData' => $monthlyData,
        ]);
    }

    private function getMonthlyData()
    {
        $year = date('Y'); // Tahun saat ini
        $data = DB::table('salaries')
            ->select(
                DB::raw('MONTH(transaction_date) as month'),
                DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
            )
            ->where('company_id', Auth::user()->company->id)
            ->whereYear('transaction_date', $year)
            ->groupBy(DB::raw('MONTH(transaction_date)'))
            ->orderBy('month')
            ->get();

        $months = [];
        $income = [];
        $expense = [];

        // Daftar nama bulan dalam bahasa Indonesia
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        foreach ($data as $item) {
            $months[] = $monthNames[$item->month]; // Menggunakan nama bulan dalam bahasa Indonesia
            $income[] = (float) $item->income; // Pastikan data adalah angka
            $expense[] = (float) $item->expense; // Pastikan data adalah angka
        }

        return [
            'months' => $months,
            'income' => $income,
            'expense' => $expense
        ];
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

        $userId = Auth::user()->id;

        // Hitung notifikasi yang belum dibaca
        $newNotificationCount = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return view('dashboard.employee.index', [
            'months' => $months,
            'projectCounts' => $projectCounts,
            'statuses' => $statuses,
            'attendanceCounts' => $attendanceCounts,
            'newNotificationCount' => $newNotificationCount,
        ]);
    }

    public function getNewNotificationCount()
    {
        // Mengambil ID pengguna yang sedang login
        $userId = Auth::id();

        // Menghitung jumlah notifikasi yang belum dibaca
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }
}
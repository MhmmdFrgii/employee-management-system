<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Project;
use App\Models\Attendance;
use App\Models\Department;

use Illuminate\Http\Request;
use App\Models\EmployeeDetail;
use App\Models\Salary;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // $newNotificationCount = $this->getNewNotificationCount();
        $monthlyData = $this->getMonthlyData();
        $company_id = Auth::user()->company->id;

        // Hitung data yang ada seperti semula
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

        $performance = ($project_count > 0) ? round(($project_done / $project_count) * 100, 2) : 0;

        $department_count = Department::where('company_id', $company_id)->count();
        $now = Carbon::now();
        $projectsWithNearestDeadlines = Project::where('end_date', '>=', $now)
            ->where('company_id', $company_id)
            ->where('status', '!=', 'completed')
            ->orderBy('end_date', 'asc')
            ->get();

        // Logika untuk pendapatan dan pengeluaran bulanan
        $months = [];
        $activeCounts = [];
        $earningCounts = [];
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('F');
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

        $project_data = [];
        for ($i = 0; $i < 6; $i++) {
            $project_data[] = [
                $i + 1,
                $activeCounts[$i],
                $earningCounts[$i]
            ];
        }

        // Transaksi pendapatan dan pengeluaran
        $monthtransa = [];
        $incomes = [];
        $expenses = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthData = Carbon::now()->subMonths($i);
            $monthtransa[] = $monthData->format('F');

            $monthlyIncomes = Transaction::where('company_id', $company_id)
                ->where('type', 'income')
                ->whereYear('created_at', $monthData->year)
                ->whereMonth('created_at', $monthData->month)
                ->sum('amount');

            $monthlyExpenses = Transaction::where('company_id', $company_id)
                ->where('type', 'expense')
                ->whereYear('created_at', $monthData->year)
                ->whereMonth('created_at', $monthData->month)
                ->sum('amount');

            $incomes[] = $monthlyIncomes;
            $expenses[] = $monthlyExpenses;
        }

        $totalIncomes = array_sum($incomes);
        $totalExpenses = array_sum($expenses);

        $departments = Department::where('company_id', $company_id)->pluck('name')->toArray();
        $department_data = Department::where('company_id', $company_id)
            ->withCount('employee_details')
            ->pluck('employee_details_count')
            ->toArray();

        // Panggil data proyek yang selesai dari tabel department
        $completedProjectsData = $this->getCompletedProjectsByDepartment($company_id);

        return view('dashboard.index', [
            'employee_count' => $employee_count,
            'project_count' => $project_count,
            'project_done' => $project_done,
            'department_count' => $department_count,
            'applicant_count' => $applicant_count,
            'months' => $months,
            'monthtransa' => $monthtransa,
            'incomes' => $incomes,
            'expenses' => $expenses,
            'totalIncomes' => $totalIncomes,
            'totalExpenses' => $totalExpenses,
            'performance' => $performance,
            'project_data' => $project_data,
            'departments' => $departments,
            'department_data' => $department_data,
            'projectsWithNearestDeadlines' => $projectsWithNearestDeadlines,
            // 'newNotificationCount' => $newNotificationCount,
            'monthlyData' => $monthlyData,
            'completedProjects' => $completedProjectsData['completedProjects'], // Data dari kolom project_complete
            'departmentNames' => $completedProjectsData['departmentNames'],
        ]);
    }

    protected function getCompletedProjectsByDepartment($company_id)
    {
        $departments = Department::where('company_id', $company_id)->get();

        $completedProjects = [];
        $departmentNames = [];

        foreach ($departments as $department) {
            $departmentNames[] = $department->name;

            // Buat array proyek selesai berdasarkan bulan
            $monthlyCompletedProjects = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $monthlyCompletedProjects[$month->format('F')] = Project::where('department_id', $department->id)
                    ->where('status', 'completed')
                    ->whereYear('end_date', $month->year)
                    ->whereMonth('end_date', $month->month)
                    ->count();
            }
            $completedProjects[$department->name] = $monthlyCompletedProjects;
        }

        return [
            'completedProjects' => $completedProjects, // Data proyek selesai per departemen per bulan
            'departmentNames' => $departmentNames,
        ];
    }

    private function getMonthlyData()
    {
        $year = date('Y');
        $data = DB::table('transactions')
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
            $months[] = $monthNames[$item->month];
            $income[] = (float) $item->income;
            $expense[] = (float) $item->expense;
        }

        return [
            'months' => $months,
            'income' => $income,
            'expense' => $expense
        ];
    }

    public function userDashboard()
    {
        $now = Carbon::now();
        $currentYear = $now->year;
        $months = [];
        $projectCounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('F');
            $projectCounts[] = Project::where('status', 'completed')
                ->whereHas('employee_details', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })
                ->whereYear('start_date', $currentYear)
                ->whereMonth('start_date', $month->month)
                ->count();
        }

        $user = Auth::user();
        $attendanceData = Attendance::selectRaw('status, COUNT(*) as count')
            ->whereHas('employee_detail', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statuses = ['present', 'absent', 'late', 'alpha'];
        $attendanceCounts = [];

        foreach ($statuses as $status) {
            $attendanceCounts[] = $attendanceData[$status] ?? 0;
        }

        // $newNotificationCount = Notification::where('user_id', Auth::user()->id)
        //     ->where('is_read', false)
        //     ->count();

        return view('dashboard.employee.index', [
            'months' => $months,
            'projectCounts' => $projectCounts,
            'statuses' => $statuses,
            'attendanceCounts' => $attendanceCounts,
            // 'newNotificationCount' => $newNotificationCount,
        ]);
    }

    // public function getNewNotificationCount()
    // {
    //     $userId = Auth::id();
    //     return Notification::where('user_id', $userId)
    //         ->where('is_read', false)
    //         ->count();
    // }
}
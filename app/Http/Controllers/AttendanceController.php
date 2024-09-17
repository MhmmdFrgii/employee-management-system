<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\EmployeeDetail;
use App\Exports\AttendanceExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redis;

class AttendanceController extends Controller
{
    // Mark Absentees today
    public function mark_absentees()
    {
        $today = Carbon::today()->toDateString();

        // Ambil semua karyawan
        $employees = User::all(); // Sesuaikan model jika berbeda

        foreach ($employees as $employee) {
            // Cek apakah karyawan ini sudah absen hari ini
            $attendance = Attendance::where('employee_id', $employee->id)
                ->where('date', $today)
                ->first();

            // Jika belum absen, tandai sebagai alpha
            if (!$attendance) {
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $today,
                    'status' => 'alpha', // Tandai sebagai alpha
                ]);
            }
        }
    }

    // display a list of each user attendance
    public function user_index()
    {
        $user = Auth::user()->employee_detail->id;

        $today = Carbon::today();
        $total_attendance = Attendance::where('employee_id', $user)->whereDate('date', '<=', $today)->count();
        $total_present = Attendance::where('employee_id', $user)->whereDate('date', '<=', $today)->where('status', 'present')->count();
        $total_absent = Attendance::where('employee_id', $user)->whereDate('date', '<=', $today)->where('status', 'absent')->count();
        $total_alpha = Attendance::where('employee_id', $user)->whereDate('date', '<=', $today)->where('status', 'alpha')->count();
        $attendance_count = Attendance::where('employee_id', $user)->whereDate('date', '<=', $today)->count();

        $attendances = Attendance::where('employee_id', $user)->get();

        return view('absenUser.index', compact('attendances', 'attendance_count', 'total_attendance', 'total_present', 'total_absent', 'total_alpha'));
    }

    /**
     * Store a newly attendance for each user.
     */
    public function user_attendance(Request $request)
    {
        $employee = Auth::user()->employee_detail->id;
        $company = Auth::user()->company; // Ambil perusahaan tempat user bekerja

        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();

        $checkin_start = Carbon::parse($company->checkin_start);
        $checkin_end = Carbon::parse($company->checkin_end);

        $today_attendance = Attendance::where('employee_id', $employee)
            ->where('date', $today)
            ->exists();

        if ($today_attendance) {
            return redirect()->route($request->route)->with('info', 'Kamu sudah absen hari ini!');
        }

        if ($now->lessThan($checkin_start)) {
            return redirect()->route($request->route)->with('danger', 'Belum bisa absen, waktu absen belum dimulai!');
        }


        if ($now->lessThanOrEqualTo($checkin_end)) {
            $status = 'present';
        } else {
            $status = 'late';
        }

        // Buat entri absensi baru
        Attendance::create([
            'employee_id' => $employee,
            'date' => $today,
            'status' => $status,
        ]);

        return redirect()->route($request->route)->with('success', 'Berhasil absen!');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $selectedDate = $request->has('date') ? Carbon::parse($request->date) : Carbon::today();

        $searchQuery = $request->input('search');


        $employees = EmployeeDetail::where('company_id', $user->company_id)
            ->where('status', 'approved')
            ->when($searchQuery, function ($query) use ($searchQuery) {

                $query->where('name', 'like', '%' . $searchQuery . '%')
                      ->orWhereHas('department', function ($q) use ($searchQuery) {
                          $q->where('name', 'like', '%' . $searchQuery . '%');
                      });
            })
            ->with(['attendances' => function ($query) use ($selectedDate) {

                $query->whereDate('date', $selectedDate);
            }])
            ->get();

        // Filter status
        $statusFilters = $request->status ?? [];

        if (in_array('alpha', $statusFilters)) {
            $employeesAlpha = EmployeeDetail::where('company_id', $user->company_id)
                ->where('status', 'approved')
                ->whereDoesntHave('attendances', function ($query) use ($selectedDate) {
                    $query->whereDate('date', $selectedDate);
                })
                ->get();

            $employees = $employees->merge($employeesAlpha);
        }

        if (!empty($statusFilters)) {
            $employees = $employees->filter(function ($employee) use ($statusFilters) {
                if (in_array('alpha', $statusFilters) && $employee->attendances->isEmpty()) {
                    return true;
                }

                foreach ($employee->attendances as $attendance) {
                    if (in_array($attendance->status, $statusFilters)) {
                        return true;
                    }
                }
                return false;
            });
        }

        return view('attendance.index', compact('employees', 'selectedDate', 'searchQuery'));
    }

    public function export(Request $request)
    {
        // Validasi input year dan month
        $validated = $request->validate([
            'year' => 'required|integer|digits:4',
            'month' => 'required|integer|between:1,12',
        ]);

        $year = $validated['year'];
        $month = $validated['month'];

        // Proses export
        return Excel::download(new AttendanceExport($year, $month), 'rekap_absensi_' . $month . '_' . $year . '.xlsx');
    }
}

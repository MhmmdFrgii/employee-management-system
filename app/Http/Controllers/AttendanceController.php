<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EmployeeDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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

        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();

        $limit = (Carbon::parse(date('Y-m-d') . ' 08:00:00'));


        if ($now->greaterThan($limit)) {
            $status = 'late';
        } else {
            $status = 'present';
        }

        $today_attendance = Attendance::where('employee_id', $employee)
            ->where('date', $today)
            ->exists();

        if ($today_attendance) {
            return redirect()->route($request->route)->with('info', 'Kamu sudah absen!');
        }

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

        $employees = EmployeeDetail::where('company_id', $user->company_id)
            ->with(['attendances' => function ($query) use ($selectedDate) {
                $query->whereDate('date', $selectedDate);
            }])
            ->get();

        if ($request->has('search')) {
            $employees = $employees->filter(function ($employee) use ($request) {
                return stripos($employee->name, $request->search) !== false;
            });
        }

        if ($request->has('status') && is_array($request->status)) {
            $employees = $employees->filter(function ($employee) use ($request) {
                return $employee->attendances->whereIn('status', $request->status)->isNotEmpty();
            });
        }

        return view('attendance.index', compact('employees', 'selectedDate'));
    }
}

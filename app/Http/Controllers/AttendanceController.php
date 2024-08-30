<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
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

        $total_attendance = Attendance::where('employee_id', $user)->count();
        $total_present = Attendance::where('employee_id', $user)->where('status', 'present')->count();
        $total_absent = Attendance::where('employee_id', $user)->where('status', 'absent')->count();
        $total_alpha = Attendance::where('employee_id', $user)->where('status', 'alpha')->count();
        $attendance_count = Attendance::where('employee_id', $user)->count();

        $attendances = Attendance::where('employee_id', $user)->get();

        return view('absenUser.index', compact('attendances', 'attendance_count', 'total_attendance', 'total_present', 'total_absent', 'total_alpha'));
    }

    /**
     * Store a newly attendance for each user.
     */
    public function user_attendance()
    {
        $today_attendance = Attendance::where('employee_id', Auth::id())
            ->where('date', date('Y-m-d'))
            ->exists();

        if ($today_attendance) {
            return redirect()->route('attendance.user')->with('info', 'Kamu sudah absen!');
        }

        Attendance::create([
            'employee_id' => Auth::id(),
            'date' => date('Y-m-d'),
            'status' => 'present',
        ]);

        return redirect()->route('attendance.user')->with('success', 'Berhasil absen!');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Attendance::query();

        // Terapkan filter pencarian
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('employee_id', 'like', '%' . $request->search . '%')
                    ->orWhere('date', 'like', '%' . $request->search . '%');
            });
        }

        // Terapkan filter status
        if ($request->has('status') && is_array($request->status)) {
            $query->whereIn('status', $request->status);
        }

        // Terapkan sorting
        if ($request->has('sortBy')) {
            $direction = $request->sortDirection === 'desc' ? 'desc' : 'asc';
            $query->orderBy($request->sortBy, $direction);
        }

        $attendances = $query->paginate(10); // Sesuaikan pagination sesuai kebutuhan

        return view('attendance.index', compact('attendances'));
    }
}
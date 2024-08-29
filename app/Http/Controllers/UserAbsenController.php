<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class UserAbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // Mark Absentees today
    public function markAbsentees()
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


    public function index()
    {


        $user = Auth::user()->employeeDetails->id;

        $total_attendance = Attendance::where('employee_id', $user)->count();
        $total_present = Attendance::where('employee_id', $user)->where('status', 'present')->count();
        $total_absent = Attendance::where('employee_id', $user)->where('status', 'absent')->count();
        $total_alpha = Attendance::where('employee_id', $user)->where('status', 'ajlpha')->count();
        $attendance_count = Attendance::where('employee_id', $user)->count();

        $attendances = Attendance::where('employee_id', $user)->get();

        // dd($attendances);

        return view('absenUser.index', compact('attendances', 'attendance_count', 'total_attendance', 'total_present', 'total_absent', 'total_alpha'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $today_attendance = Attendance::where('employee_id', Auth::id())
            ->Where('date', date('Y-m-d'))
            ->exists();

        if ($today_attendance) {
            return redirect()->route('absensi.index')->with('info', 'kamu sudah absen!');
        }

        Attendance::create([
            // for now just user_id
            'employee_id' => Auth::user()->employeeDetails->id,
            'date' => date('Y-m-d'),
            'status' => 'present',
        ]);
        return redirect()->route('absensi.index')->with('success', 'berhasil!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

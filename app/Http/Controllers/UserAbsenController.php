<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $today = Carbon::today()->toDateString();

        // Ambil semua karyawan
        $employees = User::all(); // Sesuaikan model jika berbeda

        return view('absenUser.index');
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
            return redirect()->route('attendance.index')->with('info', 'kamu sudah absen!');
        }

        Attendance::create([
            // for now just user_id
            'employee_id' => Auth::user()->employee->id,
            'date' => date('Y-m-d'),
            'status' => 'present',
        ]);
        return redirect()->route('attendance.index')->with('success', 'berhasil!');
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

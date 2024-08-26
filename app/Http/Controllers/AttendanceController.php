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

    
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status'); 
        $sortBy = $request->input('sortBy', 'created_at'); 
        $sortDirection = $request->input('sortDirection', 'desc'); 

        $query = Attendance::query();

        if ($search) {
            $query->where('employee_id', 'like', '%' . $search . '%')
            ->orWhere('date', 'like', '%' . $search . '%');
            // ->orWhere('status', 'like', '%' . $search . '%')
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $attendances = $query->orderBy($sortBy, $sortDirection);
        $attendances = Attendance::paginate(10);

        return view('attendance.index', compact('attendances', 'search', 'sortBy', 'sortDirection','statusFilter'));
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

    public function store()
    {
        $today_attendance = Attendance::where('employee_id', Auth::id())
            ->Where('date', date('Y-m-d'))
            ->exists();

        if ($today_attendance) {
            return redirect()->route('attendance.index')->with('info', 'kamu sudah absen!');
        }

        Attendance::create([
            // for now just user_id
            'employee_id' => Auth::id(),
            'date' => date('Y-m-d'),
            'status' => 'present',
        ]);
        return redirect()->route('attendance.index')->with('success', 'berhasil!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}

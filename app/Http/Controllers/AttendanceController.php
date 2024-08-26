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

    // public function index(Request $request)
    // {
    //     $search = $request->input('search');
    //     $statusFilter = $request->input('status'); 
    //     $sortBy = $request->input('sortBy', 'created_at'); 
    //     $sortDirection = $request->input('sortDirection', 'desc'); 

    //     $query = Attendance::query();

    //     if ($search) {
    //         $query->where('employee_id', 'like', '%' . $search . '%')
    //         ->orWhere('date', 'like', '%' . $search . '%');
    //     }

    //     if ($statusFilter) {
    //         $query->where('status', $statusFilter);
    //     }

    //     $attendances = $query->orderBy($sortBy, $sortDirection);
    //     $attendances = Attendance::paginate(10);

    //     return view('attendance.index', compact('attendances', 'search', 'sortBy', 'sortDirection','statusFilter'));
    // }
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

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function userAttendance(Request $request)
    {
        $employeeId = Auth::user()->employee_detail->id;
        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();
        $limit = Carbon::parse(date('Y-m-d') . ' 08:00:00');

        // Tentukan status berdasarkan waktu
        $status = $now->greaterThan($limit) ? 'late' : 'present';

        // Cek apakah absensi hari ini sudah ada
        $todayAttendance = Attendance::where('employee_id', $employeeId)
            ->where('date', $today)
            ->exists();

        if ($todayAttendance) {
            // Kembalikan respons jika sudah absen
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah absen!',
            ], 400);
        }

        // Simpan absensi baru
        Attendance::create([
            'employee_id' => $employeeId,
            'date' => $today,
            'status' => $status,
        ]);

        // Kembalikan respons sukses
        return response()->json([
            'success' => true,
            'message' => 'Berhasil absen!',
        ], 201);
    }

    public function userIndex(Request $request)
    {
        // Ambil ID karyawan dari pengguna yang sedang login
        $employeeId = Auth::user()->employee_detail->id;

        // Ambil tanggal hari ini
        $today = Carbon::today();

        // Hitung total absensi
        $totalAttendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', '<=', $today)
            ->count();

        // Hitung total kehadiran
        $totalPresent = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', '<=', $today)
            ->where('status', 'present')
            ->count();

        // Hitung Terlambat
        $totalLate = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', '<=', $today)
            ->where('status', 'late')
            ->count();

        // Hitung total ketidakhadiran
        $totalAbsent = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', '<=', $today)
            ->where('status', 'absent')
            ->count();

        // Hitung total alpha
        $totalAlpha = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', '<=', $today)
            ->where('status', 'alpha')
            ->count();

        // Ambil semua data absensi
        $attendances = Attendance::where('employee_id', $employeeId)->get();

        // Kembalikan respons JSON dengan data absensi
        return response()->json([
            'success' => true,
            'data' => [
                'total_attendance' => $totalAttendance,
                'total_present' => $totalPresent,
                'total_late' => $totalLate,
                'total_absent' => $totalAbsent,
                'total_alpha' => $totalAlpha,
                'attendances' => $attendances->map(function ($attendance) {
                    return [
                        'id' => $attendance->id,
                        'date' => $attendance->date,
                        'status' => $attendance->status,
                        'created_at' => $attendance->created_at,
                        'updated_at' => $attendance->updated_at,
                    ];
                }),
            ],
        ], 200);
    }
}

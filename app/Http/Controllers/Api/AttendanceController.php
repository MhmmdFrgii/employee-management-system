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
        $employee = Auth::user()->employee_detail->id;
        $company = Auth::user()->company; // Ambil perusahaan tempat user bekerja

        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();

        // Mengambil waktu check-in yang diatur oleh perusahaan
        $checkin_start = Carbon::parse($company->checkin_start);
        $checkin_end = Carbon::parse($company->checkin_end);

        // Cek apakah sudah absen hari ini
        $today_attendance = Attendance::where('employee_id', $employee)
            ->where('date', $today)
            ->exists();

        if ($today_attendance) {
            return response()->json([
                'status' => 'info',
                'message' => 'Kamu sudah absen hari ini!',
            ], 200);
        }

        // Validasi jika waktu sekarang kurang dari checkin_start
        if ($now->lessThan($checkin_start)) {
            return response()->json([
                'status' => 'danger',
                'message' => 'Belum bisa absen, waktu absen belum dimulai!',
            ], 403);
        }

        // Tentukan status absensi
        if ($now->lessThanOrEqualTo($checkin_end)) {
            $status = 'present';
        } else {
            $status = 'late'; // Jika absen setelah checkin_end, status 'late'
        }

        // Buat entri absensi baru
        Attendance::create([
            'employee_id' => $employee,
            'date' => $today,
            'status' => $status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil absen!',
            'attendance' => [
                'employee_id' => $employee,
                'date' => $today,
                'status' => $status,
            ],
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

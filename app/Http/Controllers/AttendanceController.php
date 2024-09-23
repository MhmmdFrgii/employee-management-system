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

        // Jam absensi check-in dan check-out
        $checkin_start = Carbon::parse($company->checkin_start);
        $checkin_end = Carbon::parse($company->checkin_end);
        $checkout_start = Carbon::parse($company->checkout_start);
        $checkout_end = Carbon::parse($company->checkout_end);

        // Toleransi check-in (checkout_tolerance dihilangkan)
        $checkin_tolerance = $company->checkin_tolerance;

        // Cek apakah ada absensi hari ini
        $today_attendance = Attendance::where('employee_id', $employee)
            ->where('date', $today)
            ->first();

        // Logika Check-in
        if (!$today_attendance) {
            // Tentukan batas toleransi check-in
            $checkin_start_with_tolerance = $checkin_start->copy()->subMinutes($checkin_tolerance);

            if ($now->lessThan($checkin_start_with_tolerance)) {
                return redirect()->route($request->route)->with('danger', 'Belum bisa absen, waktu absen belum dimulai!');
            }

            if ($now->lessThanOrEqualTo($checkin_end)) {
                $status = 'present';
            } elseif ($now->greaterThan($checkin_end->copy()->addMinutes($checkin_tolerance))) {
                $status = 'alpha';
            } else {
                $status = 'late';
            }

            // Buat entri absensi baru
            Attendance::create([
                'employee_id' => $employee,
                'date' => $today,
                'status' => $status,
                'checkin_time' => $now->format('H:i:s'),
            ]);

            return redirect()->route($request->route)->with('success', 'Berhasil check-in!');
        }

        // Logika Check-out
        if ($today_attendance && !$today_attendance->checkout_time) {
            if ($now->lessThan($checkout_start)) {
                return redirect()->route($request->route)->with('danger', 'Belum bisa checkout, waktu checkout belum dimulai!');
            }

            if ($now->greaterThan($checkout_end)) {
                return redirect()->route($request->route)->with('danger', 'Waktu checkout sudah berakhir!');
            }

            // Update waktu checkout
            $today_attendance->update([
                'checkout_time' => $now->format('H:i:s'),
            ]);

            return redirect()->route($request->route)->with('success', 'Berhasil checkout!');
        }

        return redirect()->route($request->route)->with('info', 'Kamu sudah check-in dan check-out hari ini!');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortBy = $request->input('sortBy', 'name'); // Default sort by employee_id
        $sortDirection = $request->input('sortDirection', 'asc'); // Default ascending

        $user = Auth::user();
        $startDate = $request->has('start_date') ? Carbon::parse($request->start_date) : Carbon::today();
        $endDate = $request->has('end_date') ? Carbon::parse($request->end_date) : Carbon::today();
        $searchQuery = $request->input('search');

        $employees = EmployeeDetail::where('company_id', $user->company_id)
            ->where('status', 'approved')
            ->when($searchQuery, function ($query) use ($searchQuery) {
                $query->where('name', 'like', '%' . $searchQuery . '%')
                    ->orWhereHas('department', function ($q) use ($searchQuery) {
                        $q->where('name', 'like', '%' . $searchQuery . '%');
                    });
            })
            ->with(['attendances' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            }])
            ->get();

        $attendanceData = [];

        // Create date range
        $dates = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->copy()->format('Y-m-d');
        }

        foreach ($employees as $employee) {
            $attendances = $employee->attendances->keyBy('date'); // Use date as key

            foreach ($dates as $date) {
                if (isset($attendances[$date])) {
                    $attendance = $attendances[$date];
                    $attendanceData[] = [
                        'employee_id' => $employee->id,
                        'name' => $employee->name,
                        'department_name' => $employee->department->name,
                        'attendance_status' => $attendance->status,
                        'attendance_time' => $attendance->created_at->format('H:i'),
                        'checkout_time' => $attendance->checkout_time ? \Carbon\Carbon::parse($attendance->checkout_time)->format('H:i') : '-',
                        'date' => $date,
                    ];
                } else {
                    $attendanceData[] = [
                        'employee_id' => $employee->id,
                        'name' => $employee->name,
                        'department_name' => $employee->department->name,
                        'attendance_status' => 'alpha',
                        'attendance_time' => '-',
                        'checkout_time' => '-',
                        'date' => $date,
                    ];
                }
            }
        }

        usort($attendanceData, function ($a, $b) use ($sortBy, $sortDirection) {
            if ($sortBy === 'date') {
                return $sortDirection === 'asc' ? $a['date'] <=> $b['date'] : $b['date'] <=> $a['date'];
            } elseif ($sortBy === 'name') {
                return $sortDirection === 'asc' ? $a['name'] <=> $b['name'] : $b['name'] <=> $a['name'];
            }
        });

        return view('attendance.index', compact('attendanceData', 'startDate', 'endDate', 'searchQuery'));
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

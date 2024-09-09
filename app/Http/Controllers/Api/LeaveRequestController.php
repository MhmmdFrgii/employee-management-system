<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest as RequestsLeaveRequest;
use App\Models\EmployeeDetail;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $leaveRequests = LeaveRequest::where('employee_id', $user->employee_detail->id)->get();

        return response()->json([
            'success' => true,
            'leave_requests' => $leaveRequests
        ]);
    }

    public function store(RequestsLeaveRequest $request)
    {
        $user = Auth::guard('sanctum')->user();

        try {
            $employee = $user->employee_detail;
            $companyId = $user->company->id;

            DB::beginTransaction();

            // Temukan manajer berdasarkan company_id
            $manager = User::where('company_id', $companyId)
                ->role('manager')
                ->firstOrFail();

            // Kirim notifikasi ke manajer
            Notification::create([
                'user_id' => $manager->id,
                'title' => 'Pengajuan Izin Karyawan',
                'message' => 'Seorang karyawan telah mengajukan izin. Silakan tinjau dan proses pengajuan izin tersebut di halaman permintaan cuti.',
                'type' => 'info',
                'url' => 'leave-requests.index'
            ]);

            // Ambil data dari request
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Format tanggal
            $start = Carbon::parse($startDate)->format('Y-m-d');
            $end = Carbon::parse($endDate)->format('Y-m-d');

            // Periksa apakah ada izin yang sudah ada pada tanggal yang sama untuk karyawan tersebut
            $existingLeave = LeaveRequest::where('employee_id', $employee->id)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('start_date', [$start, $end])
                        ->orWhereBetween('end_date', [$start, $end])
                        ->orWhere(function ($query) use ($start, $end) {
                            $query->where('start_date', '<=', $start)
                                ->where('end_date', '>=', $end);
                        });
                })
                ->exists();

            if ($existingLeave) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Kamu sudah memiliki izin pada tanggal yang dipilih.'
                ], 400);
            }

            $validatedData = $request->validated();
            if ($request->hasFile('photo')) {
                $leaveRequestPhoto = $request->file('photo')->store('leave-request', 'public');
                $validatedData['photo'] = $leaveRequestPhoto;
            }

            LeaveRequest::create([
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'photo' => $leaveRequestPhoto,
                'type' => $validatedData['type'],
                'description' => $validatedData['description'] ?? null,
                'company_id' => $employee->company->id,
                'employee_id' => $employee->id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengajukan izin.'
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($leaveRequestPhoto)) {
                Storage::disk('public')->delete($leaveRequestPhoto);
            }

            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

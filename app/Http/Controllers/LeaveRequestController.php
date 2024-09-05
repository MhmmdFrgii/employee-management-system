<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveRequest as RequestsLeaveRequest;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\EmployeeDetail;
use App\Models\LeaveRequest;
use App\Models\Notification;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LeaveRequest::query();

        // Pencarian
        $search = $request->input('search');
        if ($search) {
            $query->search($search); // Menggunakan scope search dari model
        }

        // Filter Status
        $statuses = $request->input('status');
        if ($statuses) {
            $query->whereIn('status', $statuses);
        }

        // Menambahkan urutan untuk status 'pending' di atas
        $query->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END");

        // Sorting
        $sortBy = $request->get('sortBy', 'created_at'); // Kolom default yang valid
        $sortDirection = $request->get('sortDirection', 'asc'); // Arah default
        $query->orderBy($sortBy, $sortDirection);

        // Ambil data yang telah disortir dan difilter 
        $employee = EmployeeDetail::all();
        $company = Company::all();
        $leaveRequest = $query->paginate(5);
        $leaveRequest->appends($request->all());

        return view('leave-request.index', compact('employee', 'company', 'leaveRequest', 'sortBy', 'sortDirection', 'search', 'statuses'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestsLeaveRequest $request)
    {
        try {
            $employee = EmployeeDetail::where('id', $request->employee_id)->first();
            $company_id = $employee->company->id;

            DB::beginTransaction();

            // Temukan manajer berdasarkan company_id
            $manager = User::where('company_id', $company_id)
                ->role('manager')
                ->first();

            Notification::create([
                'user_id' => $manager->id,
                'title' => 'Pengajuan Izin Karyawan',
                'message' => 'Seorang karyawan telah mengajukan izin. Silakan tinjau dan proses pengajuan izin tersebut di halaman permintaan cuti.',
                'type' => 'info',
                'url' => 'leave-requests.index'
            ]);

            // Ambil data dari request
            $employeeId = $request->input('employee_id');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Format tanggal
            $start = Carbon::parse($startDate)->format('Y-m-d');
            $end = Carbon::parse($endDate)->format('Y-m-d');

            // Periksa apakah ada izin yang sudah ada pada tanggal yang sama untuk karyawan tersebut
            $existingLeave = LeaveRequest::where('employee_id', $employeeId)
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
                DB::rollBack(); // Jangan lupa rollback jika ada kesalahan
                return redirect()->back()->withErrors(['error' => 'Kamu sudah memiliki izin pada tanggal yang dipilih.']);
            }

            $validatedData = $request->validated();
            $leaveRequestPhoto = $request->file('photo')->store('leave-request', 'public');

            $validatedData['photo'] = $leaveRequestPhoto;
            LeaveRequest::create($validatedData);

            // Commit transaksi sebelum return
            DB::commit();

            return redirect()->route('attendance.user')->with('success', 'Berhasil mengajukan izin.');
        } catch (\Throwable $e) {
            DB::rollBack();
            if (isset($leaveRequestPhoto)) {
                Storage::disk('public')->delete($leaveRequestPhoto);
            }

            return redirect()->back()->with('error', 'Terjadi Kesalahan.')->withInput();
        }
    }


    public function update(RequestsLeaveRequest $request, LeaveRequest $leaveRequest)
    {
        // Perbarui data dengan data yang divalidasi
        $leaveRequest->update($request->validated());

        // Redirect atau kembalikan respons
        return redirect()->route('leave.index')->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();

        return to_route('leave-requests.index')->with('success', 'Berhasil Hapus Leave request!');
    }

    public function approve(LeaveRequest $id)
    {
        DB::beginTransaction(); // Mulai transaksi

        try {
            $employee = EmployeeDetail::where('id', $id->employee_id)->first();

            Notification::create([
                'user_id' => $employee->user->id,
                'title' => 'Pengajuan Izin Diterima',
                'message' => 'Pengajuan izin Anda telah diterima.',
                'type' => 'success',
                'url'
            ]);

            // Temukan permintaan cuti berdasarkan ID
            $leaveRequest = $id;

            // Update status menjadi approved
            $leaveRequest->status = 'approved';
            $leaveRequest->save();

            // Loop untuk setiap hari dalam periode cuti
            $startDate = \Carbon\Carbon::parse($leaveRequest->start_date);
            $endDate = \Carbon\Carbon::parse($leaveRequest->end_date);
            $currentDate = $startDate;

            while ($currentDate->lte($endDate)) {
                // Tambahkan data ke tabel attendance untuk setiap hari
                Attendance::updateOrCreate(
                    [
                        'employee_id' => $leaveRequest->employee_id,
                        'date' => $currentDate->toDateString(),
                    ],
                    [
                        'status' => 'absent', // Gunakan nilai 'leave' jika itu yang diinginkan dan sesuai dengan tipe data kolom
                    ]
                );

                $currentDate->addDay(); // Tambah satu hari
            }
            DB::commit();

            // Redirect kembali dengan pesan sukses
            return redirect()->route('leave-requests.index')->with('success', 'Permintaan cuti telah disetujui dan data absensi telah ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack(); // Rollback jika terjadi kesalahan

            // Redirect kembali dengan pesan error
            return redirect()->route('leave-requests.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(LeaveRequest $id)
    {
        DB::beginTransaction(); // Mulai transaksi

        try {
            $employee = EmployeeDetail::where('id', $id->employee_id)->first();

            $leaveRequest = $id;

            $leaveRequest->status = 'rejected';
            $leaveRequest->save();

            Notification::create([
                'user_id' => $employee->user->id,
                'title' => 'Pengajuan Izin Ditolak',
                'message' => 'Permintaan izin Anda telah ditolak.',
                'type' => 'warning',
                'url' => ''
            ]);

            DB::commit();

            return redirect()->route('leave-requests.index')->with('success', 'Permintaan curi telah ditolak');
        } catch (\Throwable $e) {
            DB::rollBack(); // Rollback jika terjadi kesalahan
            dd($e);

            // Redirect kembali dengan pesan error
            return redirect()->route('leave-requests.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

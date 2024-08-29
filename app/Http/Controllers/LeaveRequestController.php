<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveRequest as RequestsLeaveRequest;
use App\Models\EmployeeDetail;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

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

        // Sorting
        $sortBy = $request->get('sortBy', 'created_at'); // Kolom default yang valid
        $sortDirection = $request->get('sortDirection', 'asc'); // Arah default
        $query->orderBy($sortBy, $sortDirection);

        // Ambil data yang telah disortir dan difilter 
        $employee = EmployeeDetail::all();
        $leaveRequest = $query->paginate(5);
        $leaveRequest->appends($request->all());

        return view('leave-request.index', compact('employee', 'leaveRequest', 'sortBy', 'sortDirection', 'search', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestsLeaveRequest $request)
    { 
        LeaveRequest::create($request->validated());

        return redirect()->route('absensi.index')->with('success', 'Berhasil menambahkan data.');
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

        return to_route('leave.index')->with('success', 'Berhasil Hapus Leave request!');
    }
}
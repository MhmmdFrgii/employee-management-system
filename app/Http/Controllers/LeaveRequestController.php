<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveRequest as RequestsLeaveRequest;
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

        // Sorting
        $sortBy = $request->get('sortBy', 'created_at'); // Kolom default yang valid
        $sortDirection = $request->get('sortDirection', 'asc'); // Arah default
        $query->orderBy($sortBy, $sortDirection);

        // Ambil data yang telah disortir
        $leaveRequest = $query->paginate(10);

        return view('leave-request.index', compact('leaveRequest', 'sortBy', 'sortDirection', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('leave-request.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestsLeaveRequest $request)
    {
        LeaveRequest::create($request->validated());

        return to_route('leave.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveRequest $leaveRequest)
    {
        return view('leave-request.edit', compact('leaveRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestsLeaveRequest $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->update($request->validated());

        return to_route('leave.index')->with('success', 'Berhasil Update leave request!');
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
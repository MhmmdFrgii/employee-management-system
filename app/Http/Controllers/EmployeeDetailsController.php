<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeDetailRequest;
use App\Models\Department;
use App\Models\EmployeeDetail;
use App\Models\EmployeeDetails;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = EmployeeDetail::all();

        return view('employee.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        $users = User::whereDoesntHave('employee')->get();

        return view('employee.create', compact('departments', 'positions', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeDetailRequest $request)
    {
        $validatedData = $request->validated();
        EmployeeDetail::create($validatedData);

        return redirect()->route('employee.index')->with('success', 'Berhasil menambahkan data employee.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeDetail $employeeDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeDetail $employee)
    {
        $departments = Department::all();
        $positions = Position::all();

        return view('employee.edit', compact('departments', 'positions', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeDetailRequest $request, EmployeeDetail $employee)
    {
        $validatedData = $request->validated();
        EmployeeDetail::create($validatedData);

        $employee->update($validatedData);
        return redirect()->route('employee.index')->with('success', 'Berhasil mengubah data employee.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeDetail $employee)
    {
        try {
            $employee->delete();
            return redirect()->route('employee.index')->with('success', 'Hapus Karyawan Success!');
        } catch (\Throwable $e) {
            return redirect()->route('employee.index')->with('success', 'Failed Hapus Karyawan.');
        }
    }
}

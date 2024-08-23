<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $department = Department::all();

        return view('department.index', compact('department'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('department.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request)
    {
        Department::create($request->validated());

        return redirect()->route('department.index')->with('success', 'Departemen berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
   
    public function edit(Department $department)
    {
        return view('department.edit', compact('department'));
        
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentRequest $request, Department $department)
    {
        $department->update($request->validated());

        return redirect()->route('department.index')->with('success', 'Departemen berhasil di edit');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        try {
            $department->delete();

            return redirect()->route('department.index')->with('success', 'Hapus Department Success!');

        } catch (\Throwable $e) {
            
            return redirect()->route('department.index')->with('success', 'Failed Hapus Departmen.');
        }
    }

}

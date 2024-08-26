<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeDetailRequest;
use App\Models\Department;
use App\Models\EmployeeDetail;
use App\Models\EmployeeDetails;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;

use function Laravel\Prompts\search;

class EmployeeDetailsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sortBy = $request->get('sortBy', 'name'); // Default sorting by name
        $sortDirection = $request->get('sortDirection', 'asc'); // Default sorting direction is ascending

        $validSortColumns = ['name', 'phone', 'address', 'department', 'hire_date', 'position'];
        // Validasi sortBy dan sortDirection
        if (!in_array($sortBy, $validSortColumns)) {
            $sortBy = 'name'; // Set default jika kolom tidak valid
        }

        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $employees = EmployeeDetail::select('employee_details.*')
            ->join('users', 'employee_details.user_id', '=', 'users.id')
            ->join('departments', 'employee_details.department_id', '=', 'departments.id')
            ->join('positions', 'employee_details.position_id', '=', 'positions.id')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('users.name', 'like', "%{$search}%")
                        ->orWhere('employee_details.phone', 'like', "%{$search}%")
                        ->orWhere('departments.name', 'like', "%{$search}%")
                        ->orWhere('positions.name', 'like', "%{$search}%");
                });
            })
            ->orderBy(
                $sortBy === 'department' ? 'departments.name' : ($sortBy === 'name' ? 'users.name' : ($sortBy === 'position' ? 'positions.name' : 'employee_details.' . $sortBy)),
                $sortDirection
            )
            ->paginate(10);
        return view('employee.index', compact('employees'));

        $employees = EmployeeDetail::paginate(10);
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

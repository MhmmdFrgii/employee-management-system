<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeDetailRequest;
use App\Models\Department;
use App\Models\EmployeeDetail;
use App\Models\Position;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeDetailController extends Controller
{
    // Display list of employees for user page
    public function user_index()
    {
        $employees = EmployeeDetail::select('employee_details.*', 'users.name as user_name', 'departments.name as department_name')
            ->join('users', 'employee_details.user_id', '=', 'users.id')
            ->join('departments', 'employee_details.department_id', '=', 'departments.id')
            ->paginate(6);

        return view('userKaryawan.index', compact('employees'));
    }

    // Display list of employees with optional search, sorting, and detail view
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sortBy = $request->get('sortBy', 'name'); // Default sorting by name
        $sortDirection = $request->get('sortDirection', 'asc'); // Default sorting direction is ascending
        $employeeId = $request->get('employee_id'); // ID karyawan untuk menampilkan detail

        $validSortColumns = ['name', 'phone', 'address', 'department', 'hire_date', 'position'];
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
            ->where('employee_details.company_id', Auth::user()->company->id)
            ->paginate(10);

        $employeeIds = EmployeeDetail::where('company_id', Auth::user()->company->id)
            ->where('status', 'approved')
            ->pluck('id')->toArray();

        $employee_completed = []; // Initialize an array to store the counts for each employee

        foreach ($employeeIds as $employeeId) {
            $count = Project::where('status', 'completed')
                ->whereHas('employee_details', function ($query) use ($employeeId) {
                    $query->where('employee_id', $employeeId);
                })
                ->count();

            $employee_completed[$employeeId] = $count;
        }

        $employee_active = []; // Initialize an array to store the counts for each employee

        foreach ($employeeIds as $employeeId) {
            $count = Project::where('status', 'active')
                ->whereHas('employee_details', function ($query) use ($employeeId) {
                    $query->where('employee_id', $employeeId);
                })
                ->count();

            $employee_active[$employeeId] = $count;
        }

        return view('employee.index', compact('employees', 'employee_completed', 'employee_active'));
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
        $cv = $request->file('cv') ? $request->file('cv')->store('cv', 'public') : null;
        $photo = $request->file('photo') ? $request->file('photo')->store('photo', 'public') : null;

        $validatedData = $request->validated();
        $validatedData['cv'] = $cv;
        $validatedData['photo'] = $photo;

        EmployeeDetail::create($validatedData);
        return redirect()->route('employee.index')->with('success', 'Berhasil menambahkan data employee.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeDetail $employee)
    {
        try {
            if ($employee->cv) {
                Storage::disk('public')->delete($employee->cv);
            }
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $employee->delete();
            return redirect()->route('employee.index')->with('success', 'Berhasil menghapus data karyawan!');
        } catch (\Throwable $e) {
            return redirect()->route('employee.index')->with('error', 'Gagal menghapus data karyawan.');
        }
    }
}

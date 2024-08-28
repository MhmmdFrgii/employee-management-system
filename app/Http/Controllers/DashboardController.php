<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\EmployeeDetail;
use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $employee_count = EmployeeDetail::count();
        $project_count = Project::count();
        $department_count = Department::count();


        return view('dashboard.index', compact('employee_count', 'project_count', 'department_count'));
    }

    public function userDashboard()
    {
        return view('dashboard.employee.index');
    }
}

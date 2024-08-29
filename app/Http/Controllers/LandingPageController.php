<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\EmployeeDetail;
use App\Models\Project;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $employees = EmployeeDetail::count();
        $departments = Department::count();
        $projects = Project::count();
        return view('welcome', compact('employees', 'departments', 'projects'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Project;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\EmployeeDetail;

class LandingPageController extends Controller
{
    public function index()
    {
        $company = Company::count();
        $departments = Department::count();
        $projects = Project::where('status', 'completed')->count();
        return view('welcome', compact('company', 'departments', 'projects'));
    }
}

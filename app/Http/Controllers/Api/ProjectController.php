<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KanbanBoard;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function getAllProject()
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $projects = Project::where('company_id', $user->company_id)->with(['employee_details.department'])->get();

         $formattedProjects = $projects->map(function ($project) {
        return [
            'id' => $project->id,
            'company_id' => $project->company_id,
            'name' => $project->name,
            'price' => $project->price,
            'description' => $project->description,
            'start_date' => $project->start_date,
            'end_date' => $project->end_date,
            'status' => $project->status,
            'completed_at' => $project->completed_at,
            'employee_details' => $project->employee_details->map(function ($employeeDetail) {
                return [
                    'id' => $employeeDetail->id,
                    'name' => $employeeDetail->name,
                    'department' => $employeeDetail->department ? $employeeDetail->department->name : null,
                ];
            }),
        ];
    });

    return response()->json(['projects' => $formattedProjects]);
    }

    public function createProject(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $rules = [
            'name' => 'required|max:250',
            'description' => 'required|max:250',
            'start_date' => 'required|date',
            'price' => 'required|numeric',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'employee_id' => 'required|array',
            'employee_id.*' => 'exists:employee_details,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) return response()->json(['message' => 'Invalid fields', 'errors' => $validator->errors()], 422);

        $company = Auth::user()->company->id;

        try {
            DB::beginTransaction();

            $project = Project::create([
                'company_id' => $company,
                'employee_id' => $request->employee_id,
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date
            ]);

            KanbanBoard::create([
                'name' => $project->name,
                'project_id' => $project->id
            ]);

            $project->employee_details()->attach($request->employee_id);

            DB::commit();

            return response()->json([
                'message' => 'Berhsil Membuat Project!',
                'project' => $project
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

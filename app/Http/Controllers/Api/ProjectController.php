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

        $projects = Project::get();

        return response()->json(['projects' => $projects]);
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

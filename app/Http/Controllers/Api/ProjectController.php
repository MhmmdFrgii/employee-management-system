<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KanbanBoard;
use App\Models\Project;
use App\Models\ProjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function getAllProject()
    {
        // Get the currently logged-in user
        $user = Auth::guard('sanctum')->user();

        // Check if the user is authenticated
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Get the employee ID from the logged-in user's employee detail
        $employeeId = $user->employee_detail->id;

        // Get the list of projects assigned to the employee associated with the logged-in user
        $assignedProjects = ProjectAssignment::where('employee_id', $employeeId)
            ->with(['project.kanban_board.kanbantasks' => function ($query) use ($employeeId) {
                // Load only the Kanban tasks assigned to the employee
                $query->where('employee_id', $employeeId);
            }])
            ->get();

        // Extract the Kanban boards associated with the assigned projects
        $projects = $assignedProjects->map(function ($assignment) {
            return $assignment->project->kanban_board;
        })->filter()->unique()->values(); // Remove null values and unique Kanban boards

        // Format Kanban boards and tasks for response
        $formattedProjects = $projects->map(function ($kanbanBoard) {
            return [
                'id' => $kanbanBoard->id,
                'name' => $kanbanBoard->name,
                'kanban_tasks' => $kanbanBoard->kanbantasks->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'title' => $task->title,
                        'status' => $task->status,
                        'date' => $task->date,
                        'color' => $task->color,
                    ];
                }),
            ];
        });

        return response()->json(['kanban_boards' => $formattedProjects]);
    }
}

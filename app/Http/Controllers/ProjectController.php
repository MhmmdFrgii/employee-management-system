<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Project;
use App\Models\Department;
use App\Models\KanbanBoard;
use Illuminate\Http\Request;
use App\Models\EmployeeDetail;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProjectRequest;
use App\Models\ProjectAssignment;

class ProjectController extends Controller
{
    // display a list of user project
    public function my_project()
    {
        $kanbanboard = KanbanBoard::with(['project', 'kanbantasks'])->get();
        return view('myproject.index', compact('kanbanboard'));
    }

    // Mark a project as completed
    public function mark_completed($id)
    {
        $project = Project::findOrFail($id);

        Project::where('id', $id)->update([
            'status' => 'Completed',
            'completed_at' => Carbon::now(),
            'name' => $project->name, // Pastikan 'name' diambil dari proyek yang ada
        ]);

        return redirect()->route('projects.index')->with('success', 'Proyek telah berhasil diselesaikan.');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::query()->where('company_id', Auth::user()->company->id);

        // Pencarian
        $search = $request->input('search');
        if ($search) {
            $query->search($search);
        }

        // Filter Status
        $statuses = $request->input('status');
        if ($statuses) {
            $query->whereIn('status', $statuses);
        }

        // Validasi Sort Direction
        $request->validate([
            'sortDirection' => 'in:asc,desc',
        ]);

        // Sorting
        $sortBy = $request->input('sortBy', 'id'); // Default sort by 'id'
        $sortDirection = $request->input('sortDirection', 'asc'); // Default sort direction 'asc'

        $projects = $query->with(['employee_details.user.department'])
            ->orderBy($sortBy, $sortDirection)
            ->paginate(6);

        $employees = EmployeeDetail::whereHas('user', function ($query) {
            $query->where('company_id', Auth::user()->company_id);
        })->get();

        // Ambil semua departemen jika perlu untuk modal edit
        $departments = Department::where('company_id', Auth::user()->company_id)->get();

        return view('projects.index', compact('projects', 'employees', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['company_id'] = Auth::user()->company->id;
        $validatedData['department_id'] = $request->department_id;

        $project = Project::create($validatedData);
        KanbanBoard::create([
            'name' => $project->name,
            'project_id' => $project->id
        ]);

        $project->employee_details()->attach($request->employee_id);
        return redirect()->route('projects.index')->with('success', 'Project berhasil ditambah');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        $project->employee_details()->sync($request->employee_id);

        $project->kanban_board->update([
            "name" => $request->name
        ]);
        return redirect()->route('projects.index')->with('success', 'Project berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Data berhasil dihapus');
    }

    // public function myProjects()
    // {
    //     // Mendapatkan ID user yang sedang login
    //     $userId = auth('web')->id();

    //     // Mendapatkan Kanban Board yang hanya di-assign kepada user yang sedang login
    //     $kanbanboard = KanbanBoard::whereHas('kanbantasks', function ($query) use ($userId) {
    //         $query->where('employee_id', $userId);
    //     })->get();

    //     return view('myproject.index', compact('kanbanboard'));
    // }

    public function myProjects()
    {
        // Mendapatkan ID user yang sedang login
        $userId = auth('web')->id();

        // Mendapatkan daftar proyek yang di-assign ke user yang login
        $assignedProjects = ProjectAssignment::where('employee_id', $userId)
            ->with(['project.kanban_board' => function ($query) use ($userId) {
                // Memuat hanya kanbantasks yang di-assign ke user yang login
                $query->with(['kanbantasks' => function ($query) use ($userId) {
                    $query->where('employee_id', $userId);
                }]);
            }])
            ->get();

        // Mengambil daftar KanbanBoard yang terkait dengan proyek yang di-assign ke user tersebut
        $kanbanboard = $assignedProjects->map(function ($assignment) {
            return $assignment->project->kanban_board;
        })->filter()->flatten(); // Menghilangkan nilai null dan membuat collection menjadi satu tingkat

        return view('myproject.index', compact('kanbanboard'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Requests\KanbanBoardRequest;
use App\Models\Comment;
use App\Models\EmployeeDetail;
use App\Models\KanbanBoard;
use App\Models\KanbanTask;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KanbanBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kanbanboardID = $request->id ? $request->id : 1;

        $comments = Comment::where('project_id', $kanbanboardID)
            ->with(['replies.user']) // Eager loading relasi user di dalam replies
            ->whereNull('parent_id')
            ->latest()
            ->get();

        $commentCount = Comment::where('project_id', $kanbanboardID)->count();

        // Ambil KanbanBoard yang terkait
        $kanbanboard = KanbanBoard::find($kanbanboardID);

        // Pastikan project_id berasal dari KanbanBoard
        $projectID = $kanbanboard ? $kanbanboard->project_id : null;

        if ($projectID) {
            // Ambil tasks yang terkait dengan kanbanboard
            $todo = KanbanTask::where('kanban_boards_id', $kanbanboardID)
                ->where('status', 'todo')
                ->get();
            $progress = KanbanTask::where('kanban_boards_id', $kanbanboardID)
                ->where('status', 'progress')
                ->get();
            $done = KanbanTask::where('kanban_boards_id', $kanbanboardID)
                ->where('status', 'done')
                ->get();

            // Ambil employee details yang diassign ke proyek tersebut
            $users = EmployeeDetail::whereHas('projects', function ($query) use ($projectID) {
                $query->where('project_id', $projectID);
            })->get();
        }


        return view('kanban-board.index', compact('kanbanboard', 'todo', 'progress', 'done', 'users', 'comments', 'commentCount'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function show(KanbanBoardRequest $request)
    {
        if ($request->project->company->id !== Auth::user()->company->id) {
            dd('ad');
        }

        $user = User::find(Auth::id());

        if ($user->hasRole('employee') && !$user->projects()->exists()) {
            return redirect()->route('project.user');
        }

        $kanbanboardID = $request->id ?  $request->id : 1;

        $kanbanboards = KanbanBoard::all(); // Perbaikan nama variabel
        $todo = KanbanTask::where('kanban_boards_id', $kanbanboardID)
            ->where('status', 'todo')
            ->get();
        $progress = KanbanTask::where('kanban_boards_id', $kanbanboardID)
            ->where('status', 'progress')
            ->get();
        $done = KanbanTask::where('kanban_boards_id', $kanbanboardID)
            ->where('status', 'done')
            ->get();
        $users = EmployeeDetail::whereHas('projects')
            ->get();

        $kanbanboard = KanbanBoard::where('id', $kanbanboardID)->first();
        return view('kanban-board.index', compact('kanbanboards', 'kanbanboard', 'todo', 'progress', 'done', 'users'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KanbanBoard $kanbanboard)
    {
        $kanbanboard->delete();
        return redirect()->route('kanban-board.index')->with('status', 'KanbanBoard berhasil dihapus.');
    }
}

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

        // Ambil semua komentar yang terkait dengan KanbanBoard tertentu
        $comments = Comment::where('project_id', $kanbanboardID)->latest()->get();

        $kanbanboards = KanbanBoard::all();
        $todo = KanbanTask::where('kanban_boards_id', $kanbanboardID)
            ->where('status', 'todo')
            ->get();
        $progress = KanbanTask::where('kanban_boards_id', $kanbanboardID)
            ->where('status', 'progress')
            ->get();
        $done = KanbanTask::where('kanban_boards_id', $kanbanboardID)
            ->where('status', 'done')
            ->get();
        $users = EmployeeDetail::whereHas('kanban_tasks', function ($query) use ($kanbanboardID) {
            $query->where('kanban_boards_id', $kanbanboardID);
        })->get();

        $kanbanboard = KanbanBoard::where('id', $kanbanboardID)->first();

        return view('kanban-board.index', compact('kanbanboards', 'kanbanboard', 'todo', 'progress', 'done', 'users', 'comments'));
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

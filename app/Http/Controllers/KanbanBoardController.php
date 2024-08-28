<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKanbanBoardRequest;
use App\Models\KanbanBoard;
use App\Models\KanbanTasks;
use App\Models\Project;
use Illuminate\Http\Request;

class KanbanBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kanbanboardID = $request->id ?  $request->id : 1;

        $kanbanboards = KanbanBoard::all(); // Perbaikan nama variabel 
        $todo = KanbanTasks::where('kanban_boards_id', $kanbanboardID)
            ->where('status', 'todo')
            ->get();
        $progress = KanbanTasks::where('kanban_boards_id', $kanbanboardID)
            ->where('status', 'progress')
            ->get();
        $done = KanbanTasks::where('kanban_boards_id', $kanbanboardID)
            ->where('status', 'done')
            ->get();

        $kanbanboard = KanbanBoard::where('id', $kanbanboardID)->first();
        return view('kanbanboard.index', compact('kanbanboards', 'kanbanboard', 'todo', 'progress', 'done'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKanbanBoardRequest $request)
    {
        KanbanBoard::create($request->validated());
        return redirect()->route('kanbanboard.index')->with('status', 'KanbanBoard berhasil disimpan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreKanbanBoardRequest $request, KanbanBoard $kanbanboard)
    {
        $kanbanboard->update($request->validated());
        return redirect()->route('kanbanboard.index')->with('status', 'KanbanBoard berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KanbanBoard $kanbanboard)
    {
        $kanbanboard->delete();
        return redirect()->route('kanbanboard.index')->with('status', 'KanbanBoard berhasil dihapus.');
    }
}

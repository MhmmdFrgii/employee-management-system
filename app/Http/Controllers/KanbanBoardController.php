<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKanbanBoardRequest;
use App\Models\KanbanBoard;
use App\Models\Project;
use Illuminate\Http\Request;

class KanbanBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        $kanbanboard = KanbanBoard::all(); // Perbaikan nama variabel
        return view('kanbanboard.index', compact('kanbanboard', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tidak diperlukan jika form create ada di halaman index
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
     * Display the specified resource.
     */
    public function show(KanbanBoard $kanbanBoard)
    {
        // Tidak digunakan dalam kasus ini
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KanbanBoard $kanbanboard)
    {
        $projects = Project::all();
        return view('kanbanboard.edit', compact('kanbanboard', 'projects')); // Perbaikan nama variabel
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

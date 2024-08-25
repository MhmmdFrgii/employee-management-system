<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKanbanTasksRequest;
use App\Models\KanbanBoard;
use App\Models\KanbanTasks;
use Illuminate\Http\Request;

class KanbanTasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kanbanboard = KanbanBoard::all();
        $kanbantasks = KanbanTasks::all();

        return view('kanbantasks.index', compact('kanbantasks', 'kanbanboard'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKanbanTasksRequest $request)
    {
        KanbanTasks::create($request->validated());
        return redirect()->route('kanbantasks.index')->with('status', 'Kanban Tasks berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KanbanTasks $kanbantasks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KanbanTasks $kanbantask)
    {
        $kanbanboard = KanbanBoard::all();
        return view('kanbantasks.edit', compact('kanbantask', 'kanbanboard'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreKanbanTasksRequest $request, KanbanTasks $kanbantask)
    {
        $kanbantask->update($request->validated());
        return redirect()->route('kanbantasks.index')->with('status','Kanban Tasks berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kanbantasks = KanbanTasks::findOrFail($id);
        $kanbantasks->delete();
        return redirect()->route('kanbantasks.index')->with('status', 'Kanban Tasks berhasil dihapus.');
    }

}

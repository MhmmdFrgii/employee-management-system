<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKanbanTasksRequest;
use App\Models\KanbanBoard;
use App\Models\KanbanTasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class KanbanTasksController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        KanbanTasks::create($request->all());
        return redirect()->route('kanbanboard.index', ['id' => $request->kanban_boards_id])->with('status', 'Kanban Tasks berhasil disimpan.');
    }

    public function update(Request $request, KanbanTasks $kanbantask)
    {
        $data = $request->all();
        $kanbantask->update($data);
        return redirect()->route('kanbanboard.index')->with('status', 'Kanban Tasks berhasil diupdate');
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

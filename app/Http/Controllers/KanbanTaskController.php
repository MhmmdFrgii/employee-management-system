<?php

namespace App\Http\Controllers;

use App\Http\Requests\KanbanTaskRequest;
use App\Models\KanbanTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class KanbanTaskController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        KanbanTask::create($request->all());
        return redirect()->route('kanbanboard.index', ['id' => $request->kanban_boards_id])->with('status', 'Kanban Tasks berhasil disimpan.');
    }

    public function update(Request $request, KanbanTask $kanbantask)
    {
        $data = $request->all();
        $data['title'] = $data['title'] ?? $kanbantask->title;
        $kanbantask->update($data);
        return redirect()->route('kanbanboard.index', ['id' => $kanbantask->kanban_boards_id])->with('status', 'Kanban Tasks berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kanbantasks = KanbanTask::findOrFail($id);
        $kanbantasks->delete();
        return redirect()->route('kanbantasks.index')->with('status', 'Kanban Tasks berhasil dihapus.');
    }
}

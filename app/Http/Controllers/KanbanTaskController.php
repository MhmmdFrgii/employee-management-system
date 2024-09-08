<?php

namespace App\Http\Controllers;

use App\Http\Requests\KanbanTaskRequest;
use App\Models\EmployeeDetail;
use App\Models\KanbanTask;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class KanbanTaskController extends Controller
{
    public function updateStatus(Request $request)
    {
        $task = KanbanTask::findOrFail($request->task_id);
        $task->status = $request->status;
        $task->save();

        return response()->json(['success' => 'Task status updated successfully.']);
    }

    public function updateOrder(Request $request)
    {
        $task = KanbanTask::find($request->taskId);

        if ($task) {
            // Update status dan urutan
            $task->status = $request->newStatus;
            $task->order = $request->newOrder;
            $task->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KanbanTaskRequest $request)
    {
        KanbanTask::create($request->validated());
        return redirect()->route('kanban-boards.index', ['id' => $request->kanban_boards_id])->with('success', 'Kanban Tasks berhasil disimpan.');
    }

    public function update(KanbanTaskRequest $request, KanbanTask $kanban_task)
    {
        $data = $request->validated();
        $data['title'] = $data['title'] ?? $kanban_task->title;

        try {
            DB::beginTransaction();

            // Simpan ID karyawan yang di-assign sebelum update
            $oldAssigneeId = $kanban_task->employee_id;

            // Update Kanban Task
            $kanban_task->update($data);

            // Simpan ID karyawan yang baru di-assign
            $newAssigneeId = $kanban_task->employee_id;

            // Jika ada perubahan pada assignee
            if ($oldAssigneeId !== $newAssigneeId) {
                // Kirim notifikasi kepada karyawan yang di-unassign
                if ($oldAssigneeId) {
                    $oldAssignee = EmployeeDetail::find($oldAssigneeId);
                    if ($oldAssignee) {
                        Notification::create([
                            'user_id' => $oldAssignee->user->id,
                            'title' => 'Anda telah di-unassign dari tugas pada Kanban Board',
                            'message' => 'Anda telah di-unassign dari tugas "' . $kanban_task->title . '".',
                            'type' => 'info',
                        ]);
                    }
                }

                // Kirim notifikasi kepada karyawan yang baru di-assign
                if ($newAssigneeId) {
                    $newAssignee = EmployeeDetail::find($newAssigneeId);
                    if ($newAssignee) {
                        Notification::create([
                            'user_id' => $newAssignee->user->id,
                            'title' => 'Tugas Baru pada Kanban Board',
                            'message' => 'Anda telah ditugaskan tugas baru "' . $kanban_task->title . '" oleh ' . Auth::user()->name . '. Silakan cek detailnya di portal.',
                            'type' => 'info',
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('kanban-boards.index', ['id' => $kanban_task->kanban_boards_id])
                ->with('success', 'Kanban Tasks berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     $kanbantasks = KanbanTask::findOrFail($id);
    //     $kanbantasks->delete();
    //     return redirect()->route('kanban-tasks.index')->with('status', 'Kanban Tasks berhasil dihapus.');
    // }

    public function destroy($id)
    {
        $task = KanbanTask::findOrFail($id);
        $task->delete();

        return redirect()->back()->with('success', 'Task berhasil dihapus.');
    }
}

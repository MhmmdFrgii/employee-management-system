<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\KanbanTaskRequest;
use App\Models\Comment;
use App\Models\EmployeeDetail;
use App\Models\KanbanBoard;
use App\Models\KanbanTask;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KanbanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        // Cek apakah user sudah terautentikasi
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $kanbanboardID = $request->id;

        // Ambil semua komentar yang terkait dengan KanbanBoard tertentu
        $comments = Comment::where('project_id', $kanbanboardID)->latest()->get();

        $todo = KanbanTask::where('kanban_boards_id', $kanbanboardID)
            ->where('status', 'todo')
            ->get();
        $progress = KanbanTask::where('kanban_boards_id', $kanbanboardID)
            ->where('status', 'progress')
            ->get();
        $done = KanbanTask::where('kanban_boards_id', $kanbanboardID)
            ->where('status', 'done')
            ->get();

        $formattedKanban = [
            'todo' => $todo->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'assigned_to' => $task->employee ? $task->employee->name : null,
                    'due_date' => $task->due_date,
                    'status' => $task->status,
                ];
            }),
            'progress' => $progress->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'assigned_to' => $task->employee ? $task->employee->name : null,
                    'due_date' => $task->due_date,
                    'status' => $task->status,
                ];
            }),
            'done' => $done->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'assigned_to' => $task->employee ? $task->employee->name : null,
                    'due_date' => $task->due_date,
                    'status' => $task->status,
                ];
            }),
            'comment' => $comments->map(function ($comment) {
                return [
                    'comment' => $comment->comment,
                    'comment_time' => $comment->created_at,
                ];
            }),
        ];

        return response()->json(['kanban_tasks' => $formattedKanban]);
    }

    public function storeTask(KanbanTaskRequest $request)
    {
        try {
            // Simpan data KanbanTask
            $task = KanbanTask::create($request->validated());

            // Kembalikan respons sukses dengan data task yang baru dibuat
            return response()->json([
                'success' => true,
                'message' => 'Kanban task berhasil disimpan.',
                'data' => $task
            ], 201);
        } catch (\Exception $e) {
            // Kembalikan respons error jika terjadi kegagalan
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan task.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateTask(KanbanTaskRequest $request, $id)
    {
        try {
            // Temukan task berdasarkan ID
            $task = KanbanTask::findOrFail($id);

            // Update task dengan data dari permintaan
            $task->update($request->validated());

            // Kembalikan respons sukses dengan data task yang telah diperbarui
            return response()->json([
                'success' => true,
                'message' => 'Kanban task berhasil diperbarui.',
                'data' => $task
            ], 200);
        } catch (\Exception $e) {
            // Kembalikan respons error jika terjadi kegagalan
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui task.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyTask($id)
    {
        try {
            // Temukan KanbanTask berdasarkan ID
            $task = KanbanTask::findOrFail($id);

            // Hapus KanbanTask
            $task->delete();

            // Kembalikan respons sukses
            return response()->json([
                'success' => true,
                'message' => 'Kanban task berhasil dihapus.',
            ], 200);
        } catch (\Exception $e) {
            // Kembalikan respons error jika terjadi kegagalan
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus task.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

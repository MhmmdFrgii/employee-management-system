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
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $kanbanboardID = $request->id ?? 1; // Gunakan null coalescing operator

        // Ambil komentar, task, dan informasi pengguna
        $comments = Comment::where('project_id', $kanbanboardID)
            ->with(['replies.user'])
            ->whereNull('parent_id')
            ->latest()
            ->get();

        $commentCount = $comments->count(); // Hitung komentar

        $kanbanboard = KanbanBoard::find($kanbanboardID);
        $projectID = $kanbanboard->project_id ?? null;

        $tasks = KanbanTask::where('kanban_boards_id', $kanbanboardID)
            ->get()
            ->groupBy('status'); // Mengelompokkan task berdasarkan status

        // Ambil pengguna yang terlibat dalam proyek
        $users = EmployeeDetail::whereHas('projects', function ($query) use ($projectID) {
            $query->where('project_id', $projectID);
        })->get();

        // Format data respons
        $formattedKanban = [
            'tasks' => [
                'todo' => $tasks->get('todo', collect())->map(fn($task) => $this->formatTask($task)),
                'progress' => $tasks->get('progress', collect())->map(fn($task) => $this->formatTask($task)),
                'done' => $tasks->get('done', collect())->map(fn($task) => $this->formatTask($task)),
            ],
            'comments' => [
                'data' => $comments,
                'comment_count' => $commentCount,
            ],
            'users' => $users->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
            ]),
        ];

        return response()->json([
            'success' => true,
            'data' => $formattedKanban,
            'message' => 'Kanban board retrieved successfully',
        ]);
    }

    private function formatTask($task)
    {
        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'assigned_to' => $task->employee ? $task->employee->name : null,
            'due_date' => $task->due_date,
            'status' => $task->status,
        ];
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

    public function updateTaskStatus(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'task_id' => 'required|exists:kanban_tasks,id',
            'status' => 'required|in:todo,progress,done',
        ]);

        try {
            $task = KanbanTask::findOrFail($validated['task_id']);

            if ($task->status !== $validated['status']) {
                $task->status = $validated['status'];
                $task->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Status berhasil diperbarui.',
                    'data' => $task
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Status tidak berubah.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status. Silakan coba lagi.',
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

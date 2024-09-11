<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Project;
use App\Models\Department;
use App\Models\KanbanBoard;
use Illuminate\Http\Request;
use App\Models\EmployeeDetail;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProjectRequest;
use App\Models\Notification;
use App\Models\ProjectAssignment;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    // display a list of user project
    public function my_project()
    {
        $kanbanboard = KanbanBoard::with(['project', 'kanbantasks'])->get();
        return view('myproject.index', compact('kanbanboard'));
    }

    // Mark a project as completed
    public function mark_completed($id)
    {
        $project = Project::findOrFail($id);

        DB::beginTransaction();
        try {
            Project::where('id', $id)->update([
                'status' => 'Completed',
                'completed_at' => Carbon::now(),
                'name' => $project->name, // Pastikan 'name' diambil dari proyek yang ada
            ]);

            Transaction::create([
                'type' => 'expense',
                'amount' => $project->price,
                'description' => $project->description,
                'transaction_date' => date('Y-m-d'),
                'company_id' => Auth::user()->company_id
            ]);

            DB::commit();

            return redirect()->route('projects.index')->with('success', 'Proyek telah berhasil diselesaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);

            return redirect()->route('projects.index')->with('success', 'Terjadi kesalahan saat menyimpan menyelesaikan.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::query()->where('company_id', Auth::user()->company->id);

        // Pencarian
        $search = $request->input('search');
        if ($search) {
            $query->search($search);
        }

        // Filter Status
        $statuses = $request->input('status');
        if ($statuses) {
            $query->whereIn('status', $statuses);
        }

        // Validasi Sort Direction
        $request->validate([
            'sortDirection' => 'in:asc,desc',
        ]);

        // Sorting
        $sortBy = $request->input('sortBy', 'id'); // Default sort by 'id'
        $sortDirection = $request->input('sortDirection', 'asc'); // Default sort direction 'asc'

        $projects = $query->with(['employee_details.user.department'])
            ->orderBy($sortBy, $sortDirection)
            ->paginate(6);

        $employees = EmployeeDetail::whereHas('user', function ($query) {
            $query->where('company_id', Auth::user()->company_id);
        })->get();

        // Ambil semua departemen jika perlu untuk modal edit
        $departments = Department::where('company_id', Auth::user()->company_id)->get();

        return view('projects.index', compact('projects', 'employees', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['company_id'] = Auth::user()->company->id;

        DB::beginTransaction();

        try {
            // Membuat project baru
            $project = Project::create($validatedData);

            // Membuat KanbanBoard untuk project baru
            KanbanBoard::create([
                'name' => $project->name,
                'project_id' => $project->id
            ]);

            // Menyambungkan karyawan ke project
            $project->employee_details()->attach($request->employee_id);

            // Mengirim notifikasi ke setiap karyawan yang di-assign
            if ($request->employee_id) {
                foreach ($request->employee_id as $employeeId) {
                    $employee = EmployeeDetail::find($employeeId);
                    Notification::create([
                        'user_id' => $employee->user->id,
                        'title' => 'Anda Ditugaskan ke Proyek Baru',
                        'message' => 'Anda telah ditugaskan ke proyek: ' . $project->name . '. Silakan tinjau detailnya.',
                        'type' => 'info'
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('projects.index')->with('success', 'Project berhasil ditambah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('projects.index')->with('error', 'Gagal menambah project: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        // Ambil proyek dan karyawan terkait
        $project = Project::findOrFail($id);
        $employees = $project->employee_details()->get(['id', 'fullname']);

        // Kembalikan dalam format JSON untuk AJAX
        return response()->json([
            'project' => $project,
            'employees' => $employees
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    // public function update(ProjectRequest $request, Project $project)
    // {
    //     // Simpan daftar karyawan yang terhubung sebelum pembaruan
    //     $oldEmployeeIds = $project->employee_details->pluck('id')->toArray();

    //     try {
    //         DB::beginTransaction();

    //         // Update project dengan data yang tervalidasi
    //         $project->update($request->validated());

    //         // Update karyawan yang terhubung ke project (sync)
    //         $newEmployeeIds = $request->employee_id ?? [];
    //         $project->employee_details()->sync($newEmployeeIds);

    //         // Update KanbanBoard
    //         $project->kanban_board->update([
    //             'name' => $request->name
    //         ]);

    //         $removedEmployeeIds = array_diff($oldEmployeeIds, $newEmployeeIds);
    //         $addedEmployeeIds = array_diff($newEmployeeIds, $oldEmployeeIds);

    //         // Kirim notifikasi kepada karyawan yang di-unassign jika ada
    //         foreach ($removedEmployeeIds as $employeeId) {
    //             $employee = EmployeeDetail::find($employeeId);
    //             if ($employee) {
    //                 Notification::create([
    //                     'user_id' => $employee->user->id,
    //                     'title' => 'Penghapusan dari Proyek',
    //                     'message' => 'Anda telah dibebas tugaskan ke proyek: ' . $project->name . '.',
    //                     'type' => 'info'
    //                 ]);
    //             }
    //         }

    //         foreach ($addedEmployeeIds as $employeeId) {
    //             $employee = EmployeeDetail::find($employeeId);
    //             if ($employee) {
    //                 Notification::create([
    //                     'user_id' => $employee->user->id,
    //                     'title' => 'Penugasan ke Proyek Baru',
    //                     'message' => 'Anda telah ditugaskan ke proyek: ' . $project->name . '. Silakan tinjau detailnya.',
    //                     'type' => 'info'
    //                 ]);
    //             }
    //         }
    //         // Commit transaksi jika semua proses berhasil
    //         DB::commit();

    //         return redirect()->route('projects.index')->with('success', 'Project berhasil diperbarui');
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return redirect()->route('projects.index')->with('error', 'Terjadi kesalahan saat memperbarui project. Silakan coba lagi.');
    //     }
    // }

    public function update(ProjectRequest $request, Project $project)
    {
        $oldEmployeeIds = $project->employee_details->pluck('id')->toArray();

        try {
            DB::beginTransaction();

            // Update proyek
            $project->update($request->validated());

            // Sinkronisasi karyawan
            $newEmployeeIds = $request->employee_id ?? [];
            $project->employee_details()->sync($newEmployeeIds);

            // Update KanbanBoard jika nama proyek berubah
            $project->kanban_board->update([
                'name' => $request->name
            ]);

            // Notifikasi perubahan karyawan
            $removedEmployeeIds = array_diff($oldEmployeeIds, $newEmployeeIds);
            $addedEmployeeIds = array_diff($newEmployeeIds, $oldEmployeeIds);

            foreach ($removedEmployeeIds as $employeeId) {
                $employee = EmployeeDetail::find($employeeId);
                Notification::create([
                    'user_id' => $employee->user->id,
                    'title' => 'Dihapus dari Proyek',
                    'message' => 'Anda dihapus dari proyek: ' . $project->name,
                    'type' => 'info'
                ]);
            }

            foreach ($addedEmployeeIds as $employeeId) {
                $employee = EmployeeDetail::find($employeeId);
                Notification::create([
                    'user_id' => $employee->user->id,
                    'title' => 'Ditugaskan ke Proyek Baru',
                    'message' => 'Anda ditugaskan ke proyek: ' . $project->name,
                    'type' => 'info'
                ]);
            }

            DB::commit();
            return redirect()->route('projects.index')->with('success', 'Proyek berhasil diperbarui');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('projects.index')->with('error', 'Gagal memperbarui proyek: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if ($project->status === 'active') {
            return redirect()->route('projects.index')->with('info', 'Proyek masih aktif dan tidak dapat dihapus.');
        }

        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Data berhasil dihapus.');
    }

    public function myProjects()
    {
        // Get the currently logged-in user
        $user = Auth::user();

        // Get the employee ID from the logged-in user's employee detail
        $employeeId = $user->employee_detail->id;

        // Get the list of projects assigned to the employee associated with the logged-in user
        $assignedProjects = ProjectAssignment::where('employee_id', $employeeId)
            ->with(['project.kanban_board.kanbantasks' => function ($query) use ($employeeId) {
                // Load only the Kanban tasks assigned to the employee
                $query->where('employee_id', $employeeId);
            }])
            ->get();

        // Extract the Kanban boards associated with the assigned projects
        $kanbanBoards = $assignedProjects->map(function ($assignment) {
            return $assignment->project->kanban_board;
        })->filter()->flatten(); // Remove null values and flatten the collection


        // dd($kanbanBoards);
        return view('myproject.index', compact('kanbanBoards'));
    }

    public function show(Project $project)
    {
        // Cek apakah proyek memiliki Kanban Board
        if ($project->kanban_board) {
            // Redirect ke halaman Kanban Board
            return redirect()->route('kanban-boards.index', ['id' => $project->kanban_board->id]);
        } else {
            // Jika tidak ada Kanban Board, Anda bisa redirect atau menampilkan pesan
            return redirect()->route('projects.index')->with('error', 'Proyek ini tidak memiliki Kanban Board.');
        }
    }

    public function getEmployees($department_id)
    {
        // Mengambil data karyawan yang sesuai dengan department_id
        $employees = EmployeeDetail::where('department_id', $department_id)->get(['id', 'name']);

        // Pastikan response dalam bentuk JSON
        return response()->json($employees);
    }
}

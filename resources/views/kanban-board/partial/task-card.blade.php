<!-- resources/views/components/task-card.blade.php -->
<div class="card mb-3 bg-{{ $task->color }} text-white" data-task-id="{{ $task->id }}">
    <div class="p-2 px-3 card-body d-flex justify-content-between align-items-center">
        <p class="m-0">{{ $task->title }}</p>
        <div class="d-flex justify-content-between text-white">
            <!-- Form untuk mengubah status task -->
            @if ($task->status !== 'done')
                <form action="{{ route('kanban-tasks.update', $task->id) }}" method="post" class="m-1">
                    @method('patch')
                    @csrf
                    <input type="hidden" name="status" value="{{ $nextStatus }}">
                    <button class="btn btn-sm btn-{{ $task->color }}">
                        <i class='bx bx-check-circle'></i>
                    </button>
                </form>
            @endif

            <!-- Tombol Edit Task -->
            <button type="button" class="btn btn-sm btn-{{ $task->color }} m-1" data-bs-toggle="modal"
                data-bs-target="#editTaskModal{{ $task->id }}">
                <i class='bx bx-edit-alt'></i>
            </button>

            <!-- Tombol Hapus Task -->
            @if ($task->status === 'done')
                <button class="btn btn-sm m-1 text-white" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $task->id }}">
                    <i class='bx bx-trash'></i>
                </button>
            @endif
        </div>
    </div>

    <div class="p-2 px-3 card-body d-flex justify-content-between align-items-center">
        @if ($task->date)
            <p class="small m-0">{{ $task->date }}</p>
        @endif
        @if ($task->employee_id)
            <p class="small m-0">{{ $task->employee->name }}</p>
        @endif
    </div>
</div>


<!-- Modal Delete -->
<div class="modal fade" id="confirmDeleteModal{{ $task->id }}" tabindex="-1" aria-labelledby="vertical-center-modal"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title" id="myLargeModalLabel">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin ingin menghapus data ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start"
                    data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('kanban-tasks.destroy', $task->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

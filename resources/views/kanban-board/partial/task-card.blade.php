<!-- resources/views/components/task-card.blade.php -->
<div class="card mb-3 bg-{{ $task->color }} text-white">
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
                <button class="btn btn-sm btn-primary m-1" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                    data-action="{{ route('kanban-tasks.destroy', $task->id) }}">
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

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus task ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="post" action="">
                    @method('delete')
                    @csrf
                    <button type="submit" class="btn btn-primary">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk Menangani Modal Konfirmasi -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#confirmDeleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Tombol yang memicu modal
            var actionUrl = button.data('action'); // Ambil URL aksi dari tombol
            var form = $('#deleteForm');
            form.attr('action', actionUrl); // Atur URL aksi untuk form
        });
    });
</script>

@extends('dashboard.layouts.main')

@section('content')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

    <div class="container py-2">
        <div class="d-flex flex-column flex-md-row justify-content-between mb-4">
            <a href="{{ route(Auth::user()->hasRole('manager') ? 'projects.index' : 'project.user') }}"
                class="btn btn-primary">Kembali</a>

            <h1 class="text-center m-0">{{ $kanbanboard->name ?? 'Kanban Board' }}</h1>
            <button type="button" class="btn btn-outline-primary" @if (!isset($kanbanboard)) disabled @endif
                data-bs-toggle="modal" data-bs-target="#createTaskModalTodo{{ $kanbanboard->id ?? '' }}">
                Buat Tugas
            </button>
        </div>
        <div class="row g-4">
            @isset($kanbanboard)
                @foreach (['todo' => 'To Do', 'progress' => 'In Progress', 'done' => 'Done'] as $status => $statusTitle)
                    <div class="col-md-12 col-lg-4">
                        <div class="card">
                            <div
                                class="card-header bg-{{ $status === 'todo' ? 'primary' : ($status === 'progress' ? 'warning' : 'success') }} text-white">
                                {{ $statusTitle }}
                            </div>
                            <div class="card-body kanban-{{ $status }}" data-status="{{ $status }}">
                                @forelse ($$status as $task)
                                    @include('kanban-board.partial.task-card', [
                                        'task' => $task,
                                        'nextStatus' =>
                                            $status === 'todo'
                                                ? 'progress'
                                                : ($status === 'progress'
                                                    ? 'done'
                                                    : ''),
                                    ])
                                    @include('kanban-board.partial.edit-task-modal', [
                                        'modalId' => 'editTaskModal' . $task->id,
                                        'title' => 'Edit Task',
                                        'actionUrl' => route('kanban-tasks.update', $task->id),
                                        'method' => 'patch',
                                        'buttonText' => 'Update Task',
                                        'task' => $task,
                                    ])
                                @empty
                                @endforelse
                            </div>
                            <div class="card-footer text-center">
                                <button data-bs-toggle="modal"
                                    data-bs-target="#createTaskModal{{ ucfirst($status) }}{{ $kanbanboard->id ?? '' }}"
                                    class="btn btn-sm btn-outline-{{ $status === 'todo' ? 'primary' : ($status === 'progress' ? 'warning' : 'success') }}">
                                    + Tambahkan tugas
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center">
                    <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data" class="img-fluid"
                        style="width: clamp(150px, 50vw, 400px);">
                    <p class="mt-2">Tidak ada papan kanban tersedia</p>
                </div>
            @endisset
        </div>
    </div>
    @foreach (['todo' => 'To Do', 'progress' => 'In Progress', 'done' => 'Done'] as $status => $statusTitle)
        @include('kanban-board.partial.task-modal', [
            'modalId' => 'createTaskModal' . ucfirst($status) . ($kanbanboard->id ?? ''),
            'title' => 'Create New Task',
            'actionUrl' => route('kanban-tasks.store'),
            'method' => 'post',
            'buttonText' => 'Create',
            'status' => $status,
            'task' => new \App\Models\KanbanTask(),
        ])
    @endforeach

    <div class="container mt-3">
        <h5>Tambah Komentar</h5>
        @if (Auth::user()->hasRole('manager'))
            <div class="container mt-3">
                <form action="{{ route('comment.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $kanbanboard->id }}">
                    <div class="mb-3">
                        <textarea class="form-control" placeholder="Tulis komentar anda..." id="comment" name="comment" rows="3"
                            required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Kirim Komentar</button>
                </form>
            </div>
        @endif

        <div class="comments-section mt-4">
            <h5 class="mb-3">Komentar</h5>
            @foreach ($comments as $comment)
                <div class="comment-box mb-2 p-2 rounded-1 border border-light shadow-sm">
                    <div class="comment-content ms-2">
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">{{ $comment->user->name }}</h5>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">{{ $comment->comment }}</p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>






@endsection
{{--
<script>
    document.addEventListener('DOMContentLoaded', function() {
        ['todo', 'progress', 'done'].forEach(function(status) {
            let el = document.querySelector(`.kanban-${status}`);

            new Sortable(el, {
                group: 'kanban',
                animation: 150,
                onEnd: function(evt) {
                    // Ambil ID tugas yang dipindahkan dan status baru
                    let taskId = evt.item.dataset.taskId;
                    let newStatus = evt.to.dataset.status;

                    // Kirim perubahan urutan ke server
                    $.ajax({
                        url: '{{ route('kanban-tasks.update-order') }}', // Tambahkan route untuk update order
                        method: 'POST',
                        data: {
                            taskId: taskId,
                            newStatus: newStatus,
                            newOrder: evt.newIndex, // Posisi baru
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Order updated successfully');
                        },
                        error: function(xhr) {
                            console.error('Error updating order:', xhr);
                        }
                    });
                }
            });
        });
    });
</script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        ['todo', 'progress', 'done'].forEach(function(status) {
            let el = document.querySelector(`.kanban-${status}`);

            new Sortable(el, {
                group: 'kanban',
                animation: 150,
                onEnd: function(evt) {
                    // Ambil ID tugas yang dipindahkan dan status baru
                    let taskId = evt.item.dataset.taskId;
                    let newStatus = evt.to.dataset.status;

                    // Kirim perubahan urutan ke server
                    $.ajax({
                        url: '{{ route('kanban-tasks.update-order') }}', // Tambahkan route untuk update order
                        method: 'POST',
                        data: {
                            taskId: taskId,
                            newStatus: newStatus,
                            newOrder: evt.newIndex, // Posisi baru
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Order updated successfully');
                        },
                        error: function(xhr) {
                            console.error('Error updating order:', xhr);
                        }
                    });
                }
            });
        });
    });
</script>

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
                                        'title' => 'Edit Tugas',
                                        'actionUrl' => route('kanban-tasks.update', $task->id),
                                        'method' => 'patch',
                                        'buttonText' => 'Edit',
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
            'title' => 'Buat tugas baru',
            'actionUrl' => route('kanban-tasks.store'),
            'method' => 'post',
            'buttonText' => 'Tambah',
            'status' => $status,
            'task' => new \App\Models\KanbanTask(),
        ])
    @endforeach



    <div class="card">
        <div class="card-body">
            <h4 class="mb-4 fw-semibold">Tambah Komentar</h4>
            <form action="{{ route('comment.store') }}" method="POST">
                @csrf
                <input type="hidden" name="project_id" value="{{ $kanbanboard->id }}">
                <textarea class="form-control mb-4" name="comment" rows="3"></textarea>
                <button class="btn btn-primary" type="submit">Kirim Komentar</button>
            </form>

            <div class="d-flex align-items-center gap-3 mb-4 mt-7 pt-8">
                <h4 class="mb-0 fw-semibold">Komentar</h4>
                <span
                    class="badge bg-light-primary text-primary fs-4 fw-semibold px-6 py-8 rounded">{{ $commentCount }}</span>
            </div>

            <div class="position-relative">
                @if (!empty($comments) && $comments->count())
                    @foreach ($comments as $comment)
                        <div class="p-4 rounded-2 bg-light mb-3">
                            <div class="d-flex align-items-center gap-3">

                                @if ($comment->user->hasRole('manager'))
                                    <img src="{{ asset('dist/images/profile/user-4.jpg') }}" alt=""
                                        class="rounded-circle" width="40" height="40">
                                @else
                                    <div
                                        class="btn btn-primary round rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ti ti-user fs-6"></i>
                                    </div>
                                @endif


                                <h6 class="fw-semibold mb-0 fs-4">{{ $comment->user->name }}</h6>
                                <span class="p-1 bg-light-dark rounded-circle d-inline-block"></span>
                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                            </div>

                            @if (Auth::user()->id == $comment->user_id)
                                <div class="d-flex align-items-end">
                                    <div class="ms-auto">
                                        <div class="dropdown">
                                            <a class="" href="javascript:void(0)" id="m1"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical fs-4"></i>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="m1">
                                                <li>
                                                    <a class="dropdown-item" onclick="showUpdateForm({{ $comment->id }})">
                                                        <i class="ti ti-pencil text-muted me-1 fs-4"></i>Edit </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" role="button" data-bs-toggle="modal"
                                                        data-bs-target="#vertical-center-modal{{ $comment->id }}">
                                                        <i class="ti ti-trash text-muted me-1 fs-4"></i>Hapus </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @include('kanban-board.partial.delate-comment')

                            <p class="my-3">{{ $comment->comment }}</p>

                            <!-- Button for Reply -->
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <button class="btn text-secondary"
                                    onclick="showReplyForm({{ $comment->id }})">Balas</button>
                            </div>



                            <!-- Update Form (Hidden by Default) -->
                            <div id="update-form-{{ $comment->id }}" style="display: none; margin-left: 20px;"
                                class="mb-3">
                                <form action="{{ route('comments.update', $comment->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="project_id" value="{{ $kanbanboard->id }}">
                                    <textarea class="form-control mb-2" name="comment" rows="2">{{ $comment->comment }}</textarea>

                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary btn-sm" type="submit">Simpan</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Reply Form (Hidden by Default) -->
                            <div id="reply-form-{{ $comment->id }}" style="display: none; margin-left: 20px;"
                                class="mb-3">
                                <form action="{{ route('comments.reply', $comment->id) }}" method="POST">
                                    @csrf
                                    <textarea class="form-control mb-2" name="comment" rows="2" placeholder="Tulis balasan..."></textarea>

                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary btn-sm" type="submit">Kirim Balasan</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Display Replies -->
                            @if ($comment->replies && $comment->replies->count() > 0)
                                @foreach ($comment->replies as $reply)
                                    <div class="p-1 rounded-2 bg-light mb-3 ms-7">
                                        <div class="d-flex align-items-center gap-3">

                                            @if ($reply->user->hasRole('manager'))
                                                <img src="{{ asset('dist/images/profile/user-4.jpg') }}" alt=""
                                                    class="rounded-circle" width="40" height="40">
                                            @else
                                                <div
                                                    class="btn btn-primary round rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-user fs-6"></i>
                                                </div>
                                            @endif

                                            <h6 class="fw-semibold mb-0 fs-4">{{ $reply->user->name }}</h6>
                                            <span class="p-1 bg-light-dark rounded-circle d-inline-block"></span>
                                            <span>{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>

                                        @if (Auth::user()->id == $reply->user_id)
                                            <div class="d-flex align-items-end">
                                                <div class="ms-auto">
                                                    <div class="dropdown">
                                                        <a class="" href="javascript:void(0)" id="m1"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ti ti-dots-vertical fs-4"></i>
                                                        </a>
                                                        <ul class="dropdown-menu" aria-labelledby="m1">
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    onclick="showUpdateReply({{ $reply->id }})">
                                                                    <i class="ti ti-pencil text-muted me-1 fs-4"></i>Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" role="button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#reply-delete-{{ $reply->id }}">
                                                                    <i class="ti ti-trash text-muted me-1 fs-4"></i>Hapus
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <p class="my-3">{{ $reply->comment }}</p>

                                        <!-- Update Form (Hidden by Default) -->
                                        <div id="update-reply-{{ $reply->id }}"
                                            style="display: none; margin-left: 20px;" class="mb-3">
                                            <form action="{{ route('comments.update', $reply->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="project_id" value="{{ $kanbanboard->id }}">
                                                <textarea class="form-control mb-2" name="comment" rows="2">{{ $reply->comment }}</textarea>
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn
                                                    btn-primary btn-sm" type="submit">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    @include('kanban-board.partial.reply-delete-modal')
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>




@endsection
<script>
    function hideAllForms() {
        var replyForms = document.querySelectorAll('[id^="reply-form-"]');
        var updateForms = document.querySelectorAll('[id^="update-form-"]');
        var updateReply = document.querySelectorAll('[id^="update-reply-"]');

        replyForms.forEach(function(form) {
            form.style.display = "none";
        });

        updateForms.forEach(function(form) {
            form.style.display = "none";
        });

        updateReply.forEach(function(form) {
            form.style.display = "none";
        });
    }

    function showReplyForm(commentId) {
        hideAllForms();

        var replyForm = document.getElementById('reply-form-' + commentId);
        replyForm.style.display = "block";
    }

    function showUpdateForm(commentId) {
        hideAllForms();

        var updateForm = document.getElementById('update-form-' + commentId);
        updateForm.style.display = "block";
    }


    function showUpdateReply(commentId) {
        hideAllForms();

        var updateReply = document.getElementById('update-reply-' + commentId);
        updateReply.style.display = "block";
    }
</script>


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

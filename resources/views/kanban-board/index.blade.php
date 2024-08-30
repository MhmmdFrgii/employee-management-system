@extends('dashboard.layouts.main')

@section('content')
    <div class="container py-2">
        <div class="d-flex flex-column flex-md-row justify-content-between mb-4">
            <div class="dropdown mb-3 mb-md-0">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Pilih Kanban
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @forelse ($kanbanboards as $board)
                        <li><a class="dropdown-item" href="{{ route('kanban-board.index', ['id' => $board->id]) }}">
                                {{ $board->name }}
                            </a>
                        </li>
                    @empty
                        <li><a class="dropdown-item">Tidak tersedia</a></li>
                    @endforelse
                </ul>
            </div>

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
                            <div class="card-body">
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
                                    <p class="text-center">Tidak ada tugas tersedia</p>
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
@endsection

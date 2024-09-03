<!-- resources/views/components/task-card.blade.php -->
<div class="card mb-3 bg-{{ $task->color }} text-white">
    <div class="p-2 px-3 card-body d-flex justify-content-between align-items-center">
        <p class="m-0">{{ $task->title }}</p>
        <div class="d-flex justify-content-between text-white">
            <form action="{{ route('kanban-tasks.update', $task->id) }}" method="post" class="m-1">
                @method('patch')
                @csrf
                <input type="hidden" name="status" value="{{ $nextStatus }}">
                <button class="btn btn-sm btn-{{ $task->color }}">
                    <i class='bx bx-check-circle'></i>
                </button>
                <button type="button" class="btn btn-sm btn-{{ $task->color }}" data-bs-toggle="modal"
                    data-bs-target="#editTaskModal{{ $task->id }}">
                    <i class='bx bx-edit-alt'></i>
                </button>
            </form>
        </div>
    </div>
    @if ($task->date || $task->employee_id)
        <div class="p-2 px-3 card-body d-flex justify-content-between align-items-center">
            <p class="small m-0">{{ $task->date }}</p>
            <p class="small m-0">{{ $task->employee->name }}</p>
        </div>
    @endif
</div>

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
                        <li><a class="dropdown-item" href="{{ route('kanbanboard.index', ['id' => $board->id]) }}">
                                {{ $board->name }}
                            </a>
                        </li>
                    @empty
                        <li><a class="dropdown-item">
                                Not Available.
                            </a>
                        </li>
                    @endforelse
                </ul>
            </div>
            <h1 class="text-center m-0">{{ $kanbanboard->name ?? 'Kanban Board' }}</h1>
            <button type="button" class="btn btn-outline-primary" @if (!isset($kanbanboard)) disabled @endif
                data-bs-toggle="modal" data-bs-target="#createTaskModal{{ $kanbanboard->id ?? '' }}">
                Create Task
            </button>
        </div>
        <div class="row g-4">
            <!-- To Do Column -->
            @isset($kanbanboard)
                <div class="col-md-12 col-lg-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            To Do
                        </div>
                        <div class="card-body">
                            @forelse ($todo as $task)
                                <div class="card mb-3 bg-{{ $task->color }} text-white">
                                    <div class="p-2 px-3 card-body d-flex justify-content-between align-items-center">
                                        <p class="m-0">{{ $task->title }}</p>
                                        <div class="d-flex justify-content-between text-white">
                                            <form action="{{ route('kanbantasks.update', $task->id) }}" method="post"
                                                class="m-1">
                                                @method('patch')
                                                @csrf
                                                <input type="hidden" name="status" value="progress">
                                                <button class="btn btn-sm btn-{{ $task->color }}">
                                                    <i class='bx bx-check-circle'></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-{{ $task->color }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editTaskModal{{ $kanbanboard->id ?? '' }}">
                                                    <i class='bx bx-edit-alt'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @if ($task->date || $task->employee_id)
                                        <div class="p-2 px-3 card-body d-flex justify-content-between align-items-center">
                                            <p class="small m-0">{{ $task->date }}</p>
                                            <p class="small m-0">{{ $task->employee->fullname ?? 'nobody' }}</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Modal Edit Task --}}
                                <div class="modal fade" id="editTaskModal{{ $kanbanboard->id ?? '' }}" tabindex="-1"
                                    aria-labelledby="editTaskModalLabel{{ $kanbanboard->id ?? '' }}" aria-hidden="true"
                                    data-bs-backdrop="static">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editTaskModalLabel{{ $kanbanboard->id ?? '' }}">
                                                    Edit Task</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('kanbantasks.update', $task->id) }}" method="POST">
                                                    @csrf
                                                    @method('patch')
                                                    <div class="mb-3">
                                                        <label for="title" class="form-label">Title</label>
                                                        <input type="hidden" name="kanban_boards_id"
                                                            value="{{ $kanbanboard->id ?? '' }}">
                                                        <input type="text" name="title"
                                                            class="form-control @error('title') is-invalid @enderror"
                                                            id="title" value="{{ $task->title ?? old('title') }}">
                                                        @error('title')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="description" class="form-label">Description</label>
                                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description">{{ $task->description ?? old('description') }}</textarea>
                                                        @error('description')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="date" class="form-label">Date</label>
                                                        <input type="date" name="date"
                                                            class="form-control @error('date') is-invalid @enderror"
                                                            id="date" value="{{ $task->date ?? old('date') }}">
                                                        @error('date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="color" class="form-label">Color</label>
                                                        <select name="color"
                                                            class="form-select @error('color') is-invalid @enderror"
                                                            id="color">
                                                            <option value="primary"
                                                                {{ old('color') == 'primary' ? 'selected' : '' }}>Primary
                                                            </option>
                                                            <option value="secondary"
                                                                {{ old('color') == 'secondary' ? 'selected' : '' }}>Secondary
                                                            </option>
                                                            <option value="success"
                                                                {{ old('color') == 'success' ? 'selected' : '' }}>Success
                                                            </option>
                                                            <option value="danger"
                                                                {{ old('color') == 'danger' ? 'selected' : '' }}>Danger
                                                            </option>
                                                            <option value="warning"
                                                                {{ old('color') == 'warning' ? 'selected' : '' }}>Warning
                                                            </option>
                                                            <option value="info"
                                                                {{ old('color') == 'info' ? 'selected' : '' }}>Info</option>
                                                        </select>
                                                        @error('color')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <button type="submit" class="btn btn-primary">Create</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center">No tasks available.</p>
                            @endforelse
                        </div>
                        <div class="card-footer text-center">
                            <button class="btn btn-sm btn-outline-primary">+ Add Task</button>
                        </div>
                    </div>
                </div>

                <!-- In Progress Column -->
                <div class="col-md-12 col-lg-4">
                    <div class="card">
                        <div class="card-header bg-warning text-white">
                            In Progress
                        </div>
                        <div class="card-body">
                            @forelse ($progress as $task)
                                <div class="card mb-3 bg-{{ $task->color }} text-white">
                                    <div class="p-2 px-3 card-body d-flex justify-content-between align-items-center">
                                        <p class="m-0">{{ $task->title }}</p>
                                        <div class="d-flex justify-content-between text-white">
                                            <form action="{{ route('kanbantasks.update', $task->id) }}" method="post"
                                                class="m-1">
                                                @method('patch')
                                                @csrf
                                                <input type="hidden" name="status" value="done">
                                                <button class="btn btn-sm btn-{{ $task->color }}">
                                                    <i class='bx bx-check-circle'></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-{{ $task->color }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editTaskModal{{ $kanbanboard->id ?? '' }}">
                                                    <i class='bx bx-edit-alt'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @if ($task->date || $task->employee_id)
                                        <div class="p-2 px-3 card-body d-flex justify-content-between align-items-center">
                                            <p class="small m-0">{{ $task->date }}</p>
                                            <p class="small m-0">{{ $task->employee->fullname ?? 'nobody' }}</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Modal Edit Task --}}
                                <div class="modal fade" id="editTaskModal{{ $kanbanboard->id ?? '' }}" tabindex="-1"
                                    aria-labelledby="editTaskModalLabel{{ $kanbanboard->id ?? '' }}" aria-hidden="true"
                                    data-bs-backdrop="static">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editTaskModalLabel{{ $kanbanboard->id ?? '' }}">
                                                    Edit Task</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('kanbantasks.update', $task->id) }}" method="POST">
                                                    @csrf
                                                    @method('patch')
                                                    <div class="mb-3">
                                                        <label for="title" class="form-label">Title</label>
                                                        <input type="hidden" name="kanban_boards_id"
                                                            value="{{ $kanbanboard->id ?? '' }}">
                                                        <input type="text" name="title"
                                                            class="form-control @error('title') is-invalid @enderror"
                                                            id="title" value="{{ $task->title ?? old('title') }}">
                                                        @error('title')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="description" class="form-label">Description</label>
                                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description">{{ $task->description ?? old('description') }}</textarea>
                                                        @error('description')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="date" class="form-label">Date</label>
                                                        <input type="date" name="date"
                                                            class="form-control @error('date') is-invalid @enderror"
                                                            id="date" value="{{ $task->date ?? old('date') }}">
                                                        @error('date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="color" class="form-label">Color</label>
                                                        <select name="color"
                                                            class="form-select @error('color') is-invalid @enderror"
                                                            id="color">
                                                            <option value="primary"
                                                                {{ old('color') == 'primary' ? 'selected' : '' }}>Primary
                                                            </option>
                                                            <option value="secondary"
                                                                {{ old('color') == 'secondary' ? 'selected' : '' }}>Secondary
                                                            </option>
                                                            <option value="success"
                                                                {{ old('color') == 'success' ? 'selected' : '' }}>Success
                                                            </option>
                                                            <option value="danger"
                                                                {{ old('color') == 'danger' ? 'selected' : '' }}>Danger
                                                            </option>
                                                            <option value="warning"
                                                                {{ old('color') == 'warning' ? 'selected' : '' }}>Warning
                                                            </option>
                                                            <option value="info"
                                                                {{ old('color') == 'info' ? 'selected' : '' }}>Info</option>
                                                        </select>
                                                        @error('color')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <button type="submit" class="btn btn-primary">Create</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center">No tasks available.</p>
                            @endforelse
                        </div>
                        <div class="card-footer text-center">
                            <button class="btn btn-sm btn-outline-warning">+ Add Task</button>
                        </div>
                    </div>
                </div>

                <!-- Done Column -->
                <div class="col-md-12 col-lg-4">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            Done
                        </div>
                        <div class="card-body">
                            @forelse ($done as $task)
                                <div class="card mb-3 bg-{{ $task->color }} text-white">
                                    <div class="p-2 px-3 card-body d-flex justify-content-between align-items-center">
                                        <p class="m-0">{{ $task->title }}</p>
                                        <div class="d-flex justify-content-between text-white">
                                            <form action="{{ route('kanbantasks.update', $task->id) }}" method="post"
                                                class="m-1">
                                                @method('patch')
                                                @csrf
                                                <input type="hidden" name="status" value="todo">
                                                <button class="btn btn-sm btn-{{ $task->color }}">
                                                    <i class='bx bx-check-circle'></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-{{ $task->color }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editTaskModal{{ $kanbanboard->id ?? '' }}">
                                                    <i class='bx bx-edit-alt'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @if ($task->date || $task->employee_id)
                                        <div class="p-2 px-3 card-body d-flex justify-content-between align-items-center">
                                            <p class="small m-0">{{ $task->date }}</p>
                                            <p class="small m-0">{{ $task->employee->fullname ?? 'nobody' }}</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Modal Edit Task --}}
                                <div class="modal fade" id="editTaskModal{{ $kanbanboard->id ?? '' }}" tabindex="-1"
                                    aria-labelledby="editTaskModalLabel{{ $kanbanboard->id ?? '' }}" aria-hidden="true"
                                    data-bs-backdrop="static">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editTaskModalLabel{{ $kanbanboard->id ?? '' }}">
                                                    Edit Task</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('kanbantasks.update', $task->id) }}" method="POST">
                                                    @csrf
                                                    @method('patch')
                                                    <div class="mb-3">
                                                        <label for="title" class="form-label">Title</label>
                                                        <input type="hidden" name="kanban_boards_id"
                                                            value="{{ $kanbanboard->id ?? '' }}">
                                                        <input type="text" name="title"
                                                            class="form-control @error('title') is-invalid @enderror"
                                                            id="title" value="{{ $task->title ?? old('title') }}">
                                                        @error('title')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="description" class="form-label">Description</label>
                                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description">{{ $task->description ?? old('description') }}</textarea>
                                                        @error('description')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="date" class="form-label">Date</label>
                                                        <input type="date" name="date"
                                                            class="form-control @error('date') is-invalid @enderror"
                                                            id="date" value="{{ $task->date ?? old('date') }}">
                                                        @error('date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="color" class="form-label">Color</label>
                                                        <select name="color"
                                                            class="form-select @error('color') is-invalid @enderror"
                                                            id="color">
                                                            <option value="primary"
                                                                {{ old('color') == 'primary' ? 'selected' : '' }}>Primary
                                                            </option>
                                                            <option value="secondary"
                                                                {{ old('color') == 'secondary' ? 'selected' : '' }}>Secondary
                                                            </option>
                                                            <option value="success"
                                                                {{ old('color') == 'success' ? 'selected' : '' }}>Success
                                                            </option>
                                                            <option value="danger"
                                                                {{ old('color') == 'danger' ? 'selected' : '' }}>Danger
                                                            </option>
                                                            <option value="warning"
                                                                {{ old('color') == 'warning' ? 'selected' : '' }}>Warning
                                                            </option>
                                                            <option value="info"
                                                                {{ old('color') == 'info' ? 'selected' : '' }}>Info</option>
                                                        </select>
                                                        @error('color')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <button type="submit" class="btn btn-primary">Create</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center">No tasks available.</p>
                            @endforelse
                        </div>
                        <div class="card-footer text-center">
                            <button class="btn btn-sm btn-outline-success">+ Add Task</button>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data" class="img-fluid"
                        style="width: clamp(150px, 50vw, 400px);">
                    <p class="mt-2">No Kanban Board Available.</p>
                </div>
            @endisset
        </div>
    </div>

    {{-- Modal Create --}}
    <div class="modal fade" id="createTaskModal{{ $kanbanboard->id ?? '' }}" tabindex="-1"
        aria-labelledby="createTaskModalLabel{{ $kanbanboard->id ?? '' }}" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTaskModalLabel{{ $kanbanboard->id ?? '' }}">Create New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('kanbantasks.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="hidden" name="kanban_boards_id" value="{{ $kanbanboard->id ?? '' }}">
                            <input type="hidden" name="color" value="primary">
                            <input type="text" name="title"
                                class="form-control @error('title') is-invalid @enderror" id="title"
                                value="{{ old('title') }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

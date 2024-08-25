<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kanban Task') }}
        </h2>
    </x-slot>

    <div class="container my-5">
        {{-- Form Edit Task --}}
        <div class="card mb-4">
            <div class="card-header">
                <h4>Edit Task</h4>
            </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('kanbantasks.update', $kanbantask->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="title" class="form-label">Task Title</label>
                        <input type="text" name="title" class="form-control" id="title" placeholder="Enter task title" value="{{ old('title', $kanbantask->title) }}">
                        @error('title')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Task Description</label>
                        <textarea name="description" class="form-control" id="description" placeholder="Enter task description">{{ old('description', $kanbantask->description) }}</textarea>
                        @error('description')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kanban_boards_id" class="form-label">Board</label>
                        <select name="kanban_boards_id" id="kanban_boards_id" class="form-select">
                            <option value="">-- Pilih Board --</option>
                            @foreach ($kanbanboard as $board)
                                <option value="{{ $board->id }}" {{ old('kanban_boards_id', $kanbantask->kanban_boards_id) == $board->id ? 'selected' : '' }}>{{ $board->name }}</option>
                            @endforeach
                        </select>
                        @error('kanban_boards_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="To Do" {{ old('status', $kanbantask->status) == 'To Do' ? 'selected' : '' }}>To Do</option>
                            <option value="In Progress" {{ old('status', $kanbantask->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Done" {{ old('status', $kanbantask->status) == 'Done' ? 'selected' : '' }}>Done</option>
                        </select>
                        @error('status')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">Due Date</label>
                        <input type="date" name="date" class="form-control" id="date" value="{{ old('date', $kanbantask->date) }}">
                        @error('date')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Task</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

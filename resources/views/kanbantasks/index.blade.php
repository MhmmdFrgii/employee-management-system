<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kanban Tasks') }}
        </h2>
    </x-slot>

    <div class="container my-5">
        {{-- Form Create Task --}}
        <div class="card mb-4">
            <div class="card-header">
                <h4>Buat Tugas</h4>
            </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('kanbantasks.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Tugas</label>
                        <input type="text" name="title" class="form-control" id="title" placeholder="Enter task title" value="{{ old('title') }}">
                        @error('title')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Tugas</label>
                        <textarea name="description" class="form-control" id="description" placeholder="Enter task description">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kanban_boards_id" class="form-label">Papan</label>
                        <select name="kanban_boards_id" id="kanban_boards_id" class="form-select">
                            <option value="">-- Pilih Papan --</option>
                            @foreach ($kanbanboard as $board)
                                <option value="{{ $board->id }}" {{ old('kanban_boards_id') == $board->id ? 'selected' : '' }}>{{ $board->name }}</option>
                            @endforeach
                        </select>
                        @error('kanban_boards_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="To Do" {{ old('status') == 'To Do' ? 'selected' : '' }}>To Do</option>
                            <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Done" {{ old('status') == 'Done' ? 'selected' : '' }}>Done</option>
                        </select>
                        @error('status')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">Tenggat Waktu</label>
                        <input type="date" name="date" class="form-control" id="date" value="{{ old('date') }}">
                        @error('date')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Buat Tugas</button>
                </form>
            </div>
        </div>

        {{-- Tabel List Tasks --}}
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Papan</th>
                            <th>Status</th>
                            <th>Tenggat Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kanbantasks as $task)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->description }}</td>
                                <td>{{ $task->kanbanboard->name ?? 'Board not found' }}</td>
                                <td>{{ $task->status }}</td>
                                <td>{{ $task->date }}</td>
                                <td>
                                    <a href="{{ route('kanbantasks.edit', $task->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('kanbantasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus task ini?');">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada tugas tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

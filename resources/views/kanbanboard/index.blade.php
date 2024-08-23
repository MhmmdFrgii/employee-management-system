<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
<div class="container">
    {{-- Form Create Board --}}
    <div class="card mb-4 my-5">
        <div class="card-header">
            <h4>Create Board</h4>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                </div>
                @endif

            <form action="{{ route('kanbanboard.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Board Name</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter board name" value="{{ old('name') }}">
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    
                    <label for="name" class="form-label">Project</label>
                    <select name="projects_id" id="projects_id" class="form-select">
                        <option value="">-- pilih projek --</option>
                        @foreach ($projects as $p)
                            <option value="{{ $p->id }}" {{ old('projects') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    @error('projects_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Create Board</button>
            </form>
        </div>
    </div>

    {{-- Tabel List Boards --}}
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Board Name</th>
                        <th>Project</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kanbanboard as $b)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $b->name }}</td>
                            <td>{{ $b->projects->name }}</td> <!-- Akses relasi Project -->
                            <td>
                                <a href="{{ route('kanbanboard.edit', $b->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('kanbanboard.destroy', $b->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus board ini?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-app-layout>

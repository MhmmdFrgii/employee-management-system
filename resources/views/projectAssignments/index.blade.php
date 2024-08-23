<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container py-4">
                    @if (session()->has('success'))
                        <div class="alert alert-success mt-3" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="h3">Project Assignment </h1>

                    </div>
                    <div class="row">
                        <form action="{{ route('projectAssignments.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="project_id" class="form-label">Project</label>
                                <select name="project_id" id="project_id"
                                    class="form-control @error('project_id') is-invalid @enderror">
                                    <option value="">
                                        --Pilih Project_id--
                                    </option>
                                    @foreach ($project as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('project_id' == $item->id ? 'selected' : '') }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">employee_id</label>
                                <input type="text" name="employee_id"
                                    class="form-control @error('employee_id') is-invalid @enderror" id="employee_id">
                                @error('employee_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <input type="text" name="role"
                                    class="form-control @error('role') is-invalid @enderror" id="role">
                                @error('role')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="assigned_at" class="form-label">Ditugaskan pada</label>
                                <input type="date" name="assigned_at"
                                    class="form-control @error('assigned_at') is-invalid @enderror" id="assigned_at">
                                @error('assigned_at')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </form>

                    </div>
                    <div class="row mt-3">
                        <table class="table table-bordered shadow">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Project</th>
                                    <th>Employee</th>
                                    <th>Role</th>
                                    <th>Ditugaskan Pada</th>

                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projectAssignment as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->project->name }}</td>
                                        <td>{{ $data->employee_id }}</td>
                                        <td>{{ $data->role }}</td>
                                        <td>{{ $data->assigned_at }}</td>
                                        <td>
                                            <a href="{{ route('projectAssignments.edit', $data->id) }}"
                                                class="btn btn-warning">Edit</a>
                                            <form action="{{ route('projectAssignments.destroy', $data->id) }}"
                                                style="display: inline" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Apakah anda yakin inggin menghapus data ini')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Konten tambahan dapat ditempatkan di sini -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@extends('dashboard.layouts.main')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container py-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="h3">Penugasan Project</h1>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            Tambah Data
                        </button>

                        <form id="searchForm" action="{{ route('projectAssignments.index') }}" method="GET"
                            class="d-flex align-items-center gap-2">
                            @csrf
                            <div class="form-group mb-0 position-relative">
                                <label for="search" class="sr-only">Search:</label>
                                <input type="text" name="search" class="form-control rounded shadow search-input"
                                    placeholder="Cari Projek..." value="{{ request('search') }}">
                                <a href="{{ route('projectAssignments.index') }}"
                                    class="clear-search btn btn-sm position-absolute top-50 translate-middle-y end-0 me-2"
                                    style="z-index: 10; padding: 0.2rem 0.4rem; line-height: 1; display: none;">
                                    X
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="div">
                        <div class="mt-3">
                            <table class="table border text-nowrap customize-table mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>
                                            <a
                                                href="{{ route('projectAssignments.index', array_merge(request()->query(), ['sortBy' => 'project_id', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Project
                                                @if (request('sortBy') === 'project_id')
                                                    @if (request('sortDirection') === 'asc')
                                                        &#9650; <!-- Unicode character for upward arrow -->
                                                    @else
                                                        &#9660; <!-- Unicode character for downward arrow -->
                                                    @endif
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a
                                                href="{{ route('projectAssignments.index', array_merge(request()->query(), ['sortBy' => 'employee_id', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Employee ID
                                                @if (request('sortBy') === 'employee_id')
                                                    @if (request('sortDirection') === 'asc')
                                                        &#9650;
                                                    @else
                                                        &#9660;
                                                    @endif
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a
                                                href="{{ route('projectAssignments.index', array_merge(request()->query(), ['sortBy' => 'role', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Role
                                                @if (request('sortBy') === 'role')
                                                    @if (request('sortDirection') === 'asc')
                                                        &#9650;
                                                    @else
                                                        &#9660;
                                                    @endif
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a
                                                href="{{ route('projectAssignments.index', array_merge(request()->query(), ['sortBy' => 'assigned_at', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Ditugaskan Pada
                                                @if (request('sortBy') === 'assigned_at')
                                                    @if (request('sortDirection') === 'asc')
                                                        &#9650;
                                                    @else
                                                        &#9660;
                                                    @endif
                                                @endif
                                            </a>
                                        </th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($projectAssignment as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->project->name }}</td>
                                            <td>{{ $data->employee_id }}</td>
                                            <td>{{ $data->role }}</td>
                                            <td>{{ $data->assigned_at }}</td>
                                            <td class="d-flex gap-1">
                                                <button type="button" class="btn btn-warning text-white"
                                                    data-bs-toggle="modal" data-bs-target="#editModal{{ $data->id }}">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $data->id }}">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>

                                        {{-- MODAL EDIT --}}
                                        <div class="modal fade" id="editModal{{ $data->id }}" tabindex="-1"
                                            aria-labelledby="editModalLabel{{ $data->id }}" aria-hidden="true"
                                            data-bs-backdrop="static" data-bs-keyboard="false">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('projectAssignments.update', $data->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('put')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel{{ $data->id }}">
                                                                Edit
                                                                Penugasan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="project_id{{ $data->id }}"
                                                                    class="form-label">Project</label>
                                                                <select name="project_id"
                                                                    id="project_id{{ $data->id }}"
                                                                    class="form-control @error('project_id') is-invalid @enderror">
                                                                    @foreach ($project as $item)
                                                                        <option value="{{ $item->id }}"
                                                                            {{ old('project_id', $data->project_id) == $item->id ? 'selected' : '' }}>
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

                                                                <label for="employee_id{{ $data->id }}"
                                                                    class="form-label">Employee ID</label>
                                                                <select name="employee_id"
                                                                    id="employee_id{{ $data->id }}"
                                                                    class="form-control @error('employee_id') is-invalid @enderror">
                                                                    @foreach ($employee as $item)
                                                                        <option value="{{ $item->id }}"
                                                                            {{ old('employee_id', $data->employee_id) == $item->id ? 'selected' : '' }}>
                                                                            {{ $item->id }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('employee_id')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="role{{ $data->id }}"
                                                                    class="form-label">Role</label>
                                                                <input type="text" name="role"
                                                                    class="form-control @error('role') is-invalid @enderror"
                                                                    id="role{{ $data->id }}"
                                                                    value="{{ old('role', $data->role) }}">
                                                                @error('role')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="assigned_at{{ $data->id }}"
                                                                    class="form-label">Tanggal Penugasan</label>
                                                                <input type="date" name="assigned_at"
                                                                    class="form-control @error('assigned_at') is-invalid @enderror"
                                                                    id="assigned_at{{ $data->id }}"
                                                                    value="{{ old('assigned_at', $data->assigned_at) }}">
                                                                @error('assigned_at')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- MODAL DELETE --}}
                                        <div class="modal fade" id="deleteModal{{ $data->id }}" tabindex="-1"
                                            aria-labelledby="deleteModalLabel{{ $data->id }}" aria-hidden="true"
                                            data-bs-backdrop="static" data-bs-keyboard="false">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('projectAssignments.destroy', $data->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="deleteModalLabel{{ $data->id }}">Hapus Penugasan
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah anda yakin ingin menghapus data ini?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data"
                                                    class="img-fluid" style="width: clamp(150px, 50vw, 300px);">
                                                <p class="mt-3">No data available.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end mt-3">
                                {{ $projectAssignment->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL TAMBAH --}}
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true"
            data-bs-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('projectAssignments.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Tambah Penugasan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="project_id" class="form-label">Project</label>
                                <select name="project_id" id="project_id"
                                    class="form-control @error('project_id') is-invalid @enderror">
                                    <option value="">--Pilih Project--</option>
                                    @foreach ($project as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('project_id') == $item->id ? 'selected' : '' }}>
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
                                <label for="employee_id" class="form-label">Employee ID</label>
                                <input type="text" name="employee_id"
                                    class="form-control @error('employee_id') is-invalid @enderror"
                                    value="{{ old('employee_id') }}" id="employee_id">
                                @error('employee_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <input type="text" name="role"
                                    class="form-control @error('role') is-invalid @enderror" id="role"
                                    value="{{ old('role') }}">
                                @error('role')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="assigned_at" class="form-label">Tanggal Penugasan</label>
                                <input type="date" name="assigned_at" value="{{ old('assigned_at') }}"
                                    class="form-control @error('assigned_at') is-invalid @enderror" id="assigned_at">
                                @error('assigned_at')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

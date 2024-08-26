@extends('dashboard.layouts.main')

@section('content')
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
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                    class="form-control" placeholder="Cari Data...">
                            </div>
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </form>



                    </div>

                    <div class="div">
                        {{-- MODAL TAMBAH --}}
                        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                            aria-hidden="true" data-bs-backdrop="static">
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
                                                <label for="employee_id" class="form-label">employee_id</label>
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
                                                <input type="date" name="assigned_at" value="assigned_at"
                                                    class="form-control @error('assigned_at') is-invalid @enderror"
                                                    id="assigned_at">
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
                    </div>
                    @if (request()->has('search') && $projectAssignment->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            Data tidak ditemukan.
                        </div>
                    @elseif ($projectAssignment->isNotEmpty())
                        <div class="row mt-3">
                            <table class="table table-bordered shadow">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>
                                            <a href="#" class="sort-link" data-sort="project_id"
                                                data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                                Project
                                                @if (request('sortBy') === 'project_id')
                                                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="#" class="sort-link" data-sort="employee_id"
                                                data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                                Employe id
                                                @if (request('sortBy') === 'employee_id')
                                                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="#" class="sort-link" data-sort="role"
                                                data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                                Role
                                                @if (request('sortBy') === 'role')
                                                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="#" class="sort-link" data-sort="assigned_at"
                                                data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                                Ditugaskan Pada
                                                @if (request('sortBy') === 'assigned_at')
                                                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                                @endif
                                            </a>
                                        </th>

                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projectAssignment as $data)
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
                                                            <h5 class="modal-title"
                                                                id="editModalLabel{{ $data->id }}">Edit
                                                                Penugasan</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                                <input type="text" name="employee_id"
                                                                    class="form-control @error('employee_id') is-invalid @enderror"
                                                                    id="employee_id{{ $data->id }}"
                                                                    value="{{ old('employee_id', $data->employee_id) }}">
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
                                                                    class="form-label">Ditugaskan pada</label>
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
                                                            <button type="submit" class="btn btn-primary">Simpan
                                                                Perubahan</button>
                                                        </div>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                        </div>
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->project->name }}</td>
                            <td>{{ $data->employee_id }}</td>
                            <td>{{ $data->role }}</td>
                            <td>{{ $data->assigned_at }}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $data->id }}">
                                    Edit
                                </button>
                                <form action="{{ route('projectAssignments.destroy', $data->id) }}"
                                    style="display: inline" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah anda yakin inggin menghapus data ini')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    </table>
                    @endif
                </div>

            </div>
        </div>
    </div>
    </div>

    <ul class="pagination my-3">
        {{-- Previous Page Link --}}
        @if ($projectAssignment->onFirstPage())
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $projectAssignment->previousPageUrl() }}" rel="prev">Previous</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($projectAssignment->links()->elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $projectAssignment->currentPage())
                        <li class="page-item active" aria-current="page">
                            <a class="page-link" href="#">{{ $page }}</a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($projectAssignment->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $projectAssignment->nextPageUrl() }}" rel="next">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a>
            </li>
        @endif
    </ul>

    <script>
        document.querySelectorAll('.sort-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const sort = this.getAttribute('data-sort');
                const direction = this.getAttribute('data-direction');
                const url = new URL(window.location.href);
                url.searchParams.set('sortBy', sort);
                url.searchParams.set('sortDirection', direction);
                window.location.href = url.toString();
            });
        });
    </script>
@endsection

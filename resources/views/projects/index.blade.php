@extends('dashboard.layouts.main')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-2">
                <h1 class="h3">Project</h1>

                <div class="d-flex justify-content-between align-items-center mb-2 mt-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                        Create
                    </button>
                    <form action="{{ route('projects.index') }}" method="GET">
                        <div class="d-flex gap-2 position-relative">
                            <!-- Dropdown Filter Status -->
                            <div class="dropdown ms-2">
                                <button class="btn btn-secondary dropdown-toggle shadow" type="button" id="statusDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Status
                                </button>
                                <ul class="dropdown-menu mt-2" aria-labelledby="statusDropdown">
                                    <li>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" name="status[]" value="active"
                                                id="statusActive"
                                                {{ in_array('active', request('status', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="statusActive">
                                                Active
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" name="status[]"
                                                value="completed" id="statusCompleted"
                                                {{ in_array('completed', request('status', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="statusCompleted">
                                                Completed
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <input type="text" name="search" class="form-control rounded shadow search-input"
                                placeholder="Cari Projek..." value="{{ request('search') }}">
                            <a href="{{ route('projects.index') }}"
                                class="clear-search btn btn-sm position-absolute top-50 translate-middle-y end-0 me-2"
                                style="z-index: 10; padding: 0.2rem 0.4rem; line-height: 1; display: none;">
                                X
                            </a>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table id="employeeTable" class="table border text-nowrap customize-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>
                                    <a
                                        href="{{ route('projects.index', array_merge(request()->query(), ['sortBy' => 'name', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Name
                                        @if (request('sortBy') === 'name')
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
                                        href="{{ route('projects.index', array_merge(request()->query(), ['sortBy' => 'description', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Description
                                        @if (request('sortBy') === 'description')
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
                                        href="{{ route('projects.index', array_merge(request()->query(), ['sortBy' => 'status', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Status
                                        @if (request('sortBy') === 'status')
                                            @if (request('sortDirection') === 'asc')
                                                &#9650;
                                            @else
                                                &#9660;
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th class="text-center">
                                    <a
                                        href="{{ route('projects.index', array_merge(request()->query(), ['sortBy' => 'completed_at', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Complated
                                        @if (request('sortBy') === 'completed_at')
                                            @if (request('sortDirection') === 'asc')
                                                &#9650;
                                            @else
                                                &#9660;
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($projects as $project)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $project->name }}</td>
                                    <td>{{ Str::limit($project->description, 35) }}</td>
                                    <td>{{ $project->status }}</td>
                                    <td class="text-center">{{ $project->completed_at ? $project->completed_at : '-' }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#completeModal{{ $project->id }}" type="button">Complete</button>
                                        <button data-bs-target="#editModal{{ $project->id }}" data-bs-toggle="modal"
                                            class="btn btn-warning btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#vertical-center-modal{{ $project->id }}"
                                            type="button">Delete</button>
                                    </td>
                                </tr>

                                <!-- Modal Complete -->
                                <div class="modal fade" id="completeModal{{ $project->id }}" tabindex="-1"
                                    aria-labelledby="completeModalLabel" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex align-items-center">
                                                <h5 class="modal-title" id="completeModalLabel">Konfirmasi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah anda yakin proyek ini telah selesai?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button"
                                                    class="btn btn-light-danger text-danger font-medium waves-effect text-start"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('projects.complete', $project->id) }}" method="post">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn btn-success" type="submit">Yes, Mark as Completed</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Modal Delete -->
                                <div class="modal fade" id="vertical-center-modal{{ $project->id }}" tabindex="-1"
                                    aria-labelledby="vertical-center-modal" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex align-items-center">
                                                <h5 class="modal-title" id="myLargeModalLabel">Konfirmasi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah anda yakin ingin menghapus data ini?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button"
                                                    class="btn btn-light-danger text-danger font-medium waves-effect text-start"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('projects.destroy', $project->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger" type="submit">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModal{{ $project->id }}" tabindex="-1"
                                    aria-labelledby="editModalLabel{{ $project->id }}" aria-hidden="true"
                                    data-bs-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $project->id }}">Edit
                                                    project</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('projects.update', $project->id) }}"
                                                    method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Nama projects</label>
                                                        <input type="text" name="name"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            id="name" value="{{ old('name', $project->name) }}">
                                                        @error('name')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description" class="form-label">Deskripsi</label>
                                                        <input type="text" name="description"
                                                            class="form-control @error('description') is-invalid @enderror"
                                                            id="description"
                                                            value="{{ old('description', $project->description) }}">
                                                        @error('description')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                                                        <input type="date" name="start_date"
                                                            class="form-control @error('start_date') is-invalid @enderror"
                                                            id="start_date"
                                                            value="{{ old('start_date', $project->start_date) }}">
                                                        @error('start_date')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                                                        <input type="date" name="end_date"
                                                            class="form-control @error('end_date') is-invalid @enderror"
                                                            id="end_date"
                                                            value="{{ old('end_date', $project->end_date) }}">
                                                        @error('end_date')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data"
                                            class="img-fluid" style="width: clamp(150px, 50vw, 300px);">
                                        <p class="mt-3">No data available.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="mt-3 justify-content-end">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('projects.store') }}" method="POST">
                        @method('POST')
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama projects</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="name" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <input type="text" name="description"
                                class="form-control @error('description') is-invalid @enderror" id="description"
                                value="{{ old('description') }}">
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date"
                                class="form-control @error('start_date') is-invalid @enderror" id="start_date"
                                value="{{ old('start_date') }}">
                            @error('start_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="end_date"
                                class="form-control @error('end_date') is-invalid @enderror" id="end_date"
                                value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

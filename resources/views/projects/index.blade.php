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
                        <h1 class="h3">Project</h1>
                        <!-- Tombol untuk membuka modal tambah project -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                            Tambah Project
                        </button>
                    </div>

                    <!-- Form Search -->
                    <form action="{{ route('projects.index') }}" method="get">
                        <div class="input-group my-3">
                            <input type="text" name="search" class="form-control rounded shadow"
                                placeholder="Cari Projek..." value="{{ request('search') }}">
                                <div class="dropdown ms-2">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Status
                                                </button>
                                                <ul class="dropdown-menu mt-2" aria-labelledby="statusDropdown">
                                                    <li>
                                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                                        <input type="hidden" name="sortBy" value="{{ request('sortBy') }}">
                                                        <input type="hidden" name="sortDirection" value="{{ request('sortDirection') }}">
                                                        <div class="form-check ms-4">
                                                            <input class="form-check-input" type="checkbox" name="status[]" value="active" id="statusActive" {{ in_array('active', request('status', [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="statusActive">
                                                                Active
                                                            </label>
                                                        </div>
                                                        <div class="form-check ms-4">
                                                            <input class="form-check-input" type="checkbox" name="status[]" value="completed" id="statusCompleted" {{ in_array('completed', request('status', [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="statusCompleted">
                                                                Completed
                                                            </label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                            <button class="btn btn-outline-secondary rounded shadow ms-2" type="submit">Cari</button>
                        </div>
                    </form>

                    <!-- Alert ketika data tidak ditemukan -->
                    @if (request()->has('search') && $project->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            Data tidak ditemukan.
                        </div>
                    @elseif ($project->isNotEmpty())
                        <!-- Tabel Daftar Project -->
                        <div class="row mt-3">
                            <table class="table table-bordered shadow">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Project</th>
                                        <th>Deskripsi</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Status</th>
                                        
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($project as $data)
                                        <tr>
                                            <td>{{ $loop->iteration + ($project->currentPage() - 1) * $project->perPage() }}</td>
                                            <td class="col-lg-3">{{ $data->name }}</td>
                                            <td class="col-lg-3">{{ $data->description }}</td>
                                            <td>{{ $data->start_date }}</td>
                                            <td>{{ $data->end_date }}</td>
                                            <td>{{ $data->status }}</td>
                                            <td>
                                                <!-- Tombol untuk membuka modal edit project -->
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProjectModal{{ $data->id }}">
                                                    Edit
                                                </button>

                                                <!-- Tombol untuk membuka modal konfirmasi hapus -->
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteProjectModal{{ $data->id }}">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal Form Edit Project -->
                                        <div class="modal fade" id="editProjectModal{{ $data->id }}" tabindex="-1" aria-labelledby="editProjectModalLabel{{ $data->id }}" aria-hidden="true" data-bs-backdrop="static">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editProjectModalLabel{{ $data->id }}">Edit Project</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('projects.update', $data->id) }}" method="POST">
                                                            @method('PUT')
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">Nama Project</label>
                                                                <input type="text" name="name"
                                                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                                                    value="{{ old('name', $data->name) }}">
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
                                                                    value="{{ old('description', $data->description) }}">
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
                                                                    value="{{ old('start_date', $data->start_date) }}">
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
                                                                    value="{{ old('end_date', $data->end_date) }}">
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

                                        <!-- Modal Konfirmasi Hapus Project -->
                                        <div class="modal fade" id="deleteProjectModal{{ $data->id }}" tabindex="-1" aria-labelledby="deleteProjectModalLabel{{ $data->id }}" aria-hidden="true" data-bs-backdrop="static">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteProjectModalLabel{{ $data->id }}">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus project ini? Tindakan ini tidak dapat dibatalkan.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <form action="{{ route('projects.destroy', $data->id) }}" method="POST" style="display: inline;">
                                                            @method('DELETE')
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Pagination Links -->
                            <div class="mt-3">
                                {{ $project->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form Create Project -->
    <div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProjectModalLabel">Tambah Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('projects.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Project</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" id="description" value="{{ old('description') }}">
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" value="{{ old('start_date') }}">
                            @error('start_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>  
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection

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
                                        <td class="col-lg-4">{{ $data->description }}</td>
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
                                                        <!-- Form fields for editing project -->
                                                        <!-- ... -->
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
                    <!-- Form fields for creating project -->
                    <!-- ... -->
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

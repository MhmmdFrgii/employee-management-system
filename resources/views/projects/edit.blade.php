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
                    <!-- Tombol untuk membuka modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                        Tambah Project
                    </button>
                </div>

                <!-- Form Search -->
                <form action="{{ route('projects.index') }}" method="get">
                    <div class="input-group my-3">
                        <input type="text" name="search" class="form-control mr-2 rounded shadow"
                            placeholder="Cari Projek..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary rounded shadow" type="submit">Cari</button>
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
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="col-lg-3">{{ $data->name }}</td>
                                    <td class="col-lg-4">{{ $data->description }}</td>
                                    <td>{{ $data->start_date }}</td>
                                    <td>{{ $data->end_date }}</td>
                                    <td>{{ $data->status }}</td>
                                    <td>
                                        <a href="{{ route('projects.edit', $data->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('projects.destroy', $data->id) }}" style="display: inline" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin ingin menghapus data ini')">
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
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" id="description">
                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" id="start_date">
                        @error('start_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" id="end_date">
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

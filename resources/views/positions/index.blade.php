@extends('dashboard.layouts.main')

@section('content') 
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3">Jabatan</h1>
                    <!-- Tombol untuk membuka modal tambah positions -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createpositionsModal">
                        Tambah Jabatan
                    </button>
                </div>

                <!-- Form Search -->
                <form action="{{ route('positions.index') }}" method="GET">
                    <div class="input-group my-3">
                        <input type="text" name="search" class="form-control rounded shadow"
                            placeholder="Cari Jabatan..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary rounded shadow ms-2" type="submit">Cari</button>
                    </div>
                </form>

                <!-- Alert ketika data tidak ditemukan -->
                @if (request()->has('search') && $positions->isEmpty())
                    <div class="alert alert-warning" role="alert">
                        Jabatan tidak ditemukan.
                    </div>
                @elseif ($positions->isNotEmpty())
                    <!-- Tabel Daftar positions -->
                    <div class="row mt-3">
                        <table class="table table-bordered shadow">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jabatan</th>
                                    <th>Deskripsi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($positions as $data)
                                    <tr>
                                        <td class="col-lg-1">{{ $loop->iteration + ($positions->currentPage() - 1) * $positions->perPage() }}</td>
                                        <td class="col-lg-2">{{ $data->name }}</td>
                                        <td class="col-lg-3">{{ $data->description }}</td>
                                        <td class="col-lg-1">
                                            <!-- Tombol untuk membuka modal edit positions -->
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editpositionsModal{{ $data->id }}">
                                                Edit
                                            </button>

                                            <!-- Tombol untuk membuka modal konfirmasi hapus -->
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deletepositionsModal{{ $data->id }}">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Form Edit positions -->
                                    <div class="modal fade" id="editpositionsModal{{ $data->id }}" tabindex="-1" aria-labelledby="editpositionsModalLabel{{ $data->id }}" aria-hidden="true" data-bs-backdrop="static">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editpositionsModalLabel{{ $data->id }}">Edit Jabatan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('positions.update', $data->id) }}" method="POST">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="name" class="form-label">Nama Jabatan</label>
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
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Konfirmasi Hapus positions -->
                                    <div class="modal fade" id="deletepositionsModal{{ $data->id }}" tabindex="-1" aria-labelledby="deletepositionsModalLabel{{ $data->id }}" aria-hidden="true" data-bs-backdrop="static">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deletepositionsModalLabel{{ $data->id }}">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus jabatan ini? Tindakan ini tidak dapat dibatalkan.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <form action="{{ route('positions.destroy', $data->id) }}" method="POST" style="display: inline;">
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
                            {{ $positions->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah positions -->
<div class="modal fade" id="createpositionsModal" tabindex="-1" aria-labelledby="createpositionsModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createpositionsModalLabel">Tambah Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('positions.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Jabatan</label>
                        <input type="text" name="name"
                            class="form-control @error('name') is-invalid @enderror" id="name"
                            value="{{ old('name') }}">
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
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

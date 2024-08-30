@extends('dashboard.layouts.main')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container py-2">
                    <h1 class="h3">Jabatan</h1>

                    <div class="d-flex justify-content-between align-items-center mb-2 mt-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#createpositionsModal">
                            Tambah Jabatan
                        </button>
                        <form id="searchForm" action="{{ route('positions.index') }}" method="GET"
                            class="d-flex align-items-center gap-2">
                            @csrf
                            <div class="form-group mb-0 position-relative">
                                <label for="search" class="sr-only">Search:</label>
                                <input type="text" placeholder="Cari data.." id="search" name="search"
                                    value="{{ request('search') }}" class="form-control rounded shadow search-input">
                                <a href="{{ route('positions.index') }}"
                                    class="clear-search btn btn-sm position-absolute top-50 translate-middle-y end-0 me-2"
                                    style="z-index: 10; padding: 0.2rem 0.4rem; line-height: 1; display: none;">
                                    X
                                </a>
                            </div>
                            <button type="submit" class="btn btn-secondary">Cari</button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table border text-nowrap customize-table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jabatan</th>
                                    <th>Deskripsi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($positions as $data)
                                    <tr>
                                        <td class="col-lg-1">
                                            {{ $loop->iteration + ($positions->currentPage() - 1) * $positions->perPage() }}
                                        </td>
                                        <td class="col-lg-2">{{ $data->name }}</td>
                                        <td class="col-lg-3">{{ $data->description }}</td>
                                        <td class="col-lg-1">
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editpositionsModal{{ $data->id }}">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deletepositionsModal{{ $data->id }}">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="editpositionsModal{{ $data->id }}" tabindex="-1"
                                        aria-labelledby="editpositionsModalLabel{{ $data->id }}" aria-hidden="true"
                                        data-bs-backdrop="static">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="editpositionsModalLabel{{ $data->id }}">Edit Jabatan
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('positions.update', $data->id) }}"
                                                        method="POST">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="name" class="form-label">Nama
                                                                Jabatan</label>
                                                            <input type="text" name="name"
                                                                class="form-control @error('name') is-invalid @enderror"
                                                                id="name" value="{{ old('name', $data->name) }}">
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

                                    <div class="modal fade" id="deletepositionsModal{{ $data->id }}" tabindex="-1"
                                        aria-labelledby="deletepositionsModalLabel{{ $data->id }}" aria-hidden="true"
                                        data-bs-backdrop="static">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="deletepositionsModalLabel{{ $data->id }}">Konfirmasi
                                                        Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus jabatan ini? Tindakan ini tidak
                                                        dapat dibatalkan.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <form action="{{ route('positions.destroy', $data->id) }}"
                                                        method="POST" style="display: inline;">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data"
                                                class="img-fluid" style="width: clamp(150px, 50vw, 300px);">
                                            <p class="mt-3">No data available.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3 justify-content-end">
                            {{ $positions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createpositionsModal" tabindex="-1" aria-labelledby="createpositionsModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
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
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

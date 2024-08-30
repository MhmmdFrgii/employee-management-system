@extends('dashboard.layouts.main')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-2">

                <h1 class="h3">Departemen</h1>
                <div class="d-flex justify-content-between mb-3 mt-3">
                    <button data-bs-target="#createModal" data-bs-toggle="modal" class="btn btn-primary">Tambah</button>
                    <form id="searchForm" action="{{ route('departments.index') }}" method="GET"
                        class="d-flex align-items-center gap-2">
                        @csrf
                        <div class="form-group mb-0 position-relative">
                            <label for="search" class="sr-only">Cari:</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                class="form-control shadow search-input" placeholder="Cari data..">

                            <a href="{{ route('departments.index') }}"
                                class="clear-search btn btn-sm position-absolute top-50 translate-middle-y end-0 me-2"
                                style="z-index: 10; padding: 0.2rem 0.4rem; line-height: 1; display: none;">
                                X
                            </a>
                        </div>
                        <button type="submit" class="btn btn-secondary">Cari</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table id="employeeTable" class="table border text-nowrap customize-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>
                                    <a
                                        href="{{ route('departments.index', array_merge(request()->query(), ['sortBy' => 'name', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Nama
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
                                        href="{{ route('departments.index', array_merge(request()->query(), ['sortBy' => 'description', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Deskripsi
                                        @if (request('sortBy') === 'description')
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
                            @forelse ($departments as $department)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $department->name }}</td>
                                    <td>{{ Str::limit($department->description, 35) }}</td>
                                    <td>
                                        <button data-bs-target="#editModal{{ $department->id }}" data-bs-toggle="modal"
                                            class="btn btn-warning btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#vertical-center-modal{{ $department->id }}"
                                            type="button">Hapus</button>
                                    </td>
                                </tr>

                                <!-- Modal Delete -->
                                <div class="modal fade" id="vertical-center-modal{{ $department->id }}" tabindex="-1"
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
                                                    data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('departments.destroy', $department->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger" type="submit">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModal{{ $department->id }}" tabindex="-1"
                                    aria-labelledby="editModalLabel{{ $department->id }}" aria-hidden="true"
                                    data-bs-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $department->id }}">Edit
                                                    Departemen</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('departments.update', $department->id) }}"
                                                    method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Nama Departemen</label>
                                                        <input type="text" name="name"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            id="name" value="{{ old('name') ?? $department->name }}">
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
                                                            value="{{ $department->description ?? old('description') }}">
                                                        @error('description')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Edit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data"
                                            class="img-fluid" style="width: clamp(150px, 50vw, 300px);">
                                        <p class="mt-3">Tidak ada data tersedia</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3 justify-content-end">
                        {{ $departments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModal" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModal">Buat Departemen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('departments.store') }}" method="POST">
                        @method('post')
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Departemen</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="name" value="{{ old('name') ?? '' }}">
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
                                value="{{ old('description') ?? '' }}">
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Buat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

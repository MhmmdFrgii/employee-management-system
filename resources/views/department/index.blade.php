@extends('dashboard.layouts.main')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <button data-bs-target="#createModal" data-bs-toggle="modal" class="btn btn-primary">Create</button>
        <form id="searchForm" action="{{ route('department.index') }}" method="GET" class="d-flex align-items-center gap-2">
            @csrf
            <div class="form-group mb-0 position-relative">
                <label for="search" class="sr-only">Search:</label>
                <input type="text" placeholder="Search" id="search" name="search" value="{{ request('search') }}"
                    class="form-control">
            </div>
            <a href="{{ route('department.index') }}" class="btn btn-primary">X</a>
        </form>
    </div>

    <div class="table-responsive">
        <table id="employeeTable" class="table border text-nowrap customize-table mb-0 align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>
                        <a
                            href="{{ route('department.index', array_merge(request()->query(), ['sortBy' => 'name', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
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
                            href="{{ route('department.index', array_merge(request()->query(), ['sortBy' => 'description', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($departments as $department)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $department->name }}</td>
                        <td>{{ Str::limit($department->description, 35) }}</td>
                        <td>
                            <button data-bs-target="#editModal{{ $department->id }}" data-bs-toggle="modal"
                                class="btn btn-warning">Edit</button>
                            <button class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#vertical-center-modal{{ $department->id }}" type="button">Delete</button>
                        </td>
                    </tr>

                    <!-- Modal Delete -->
                    <div class="modal fade" id="vertical-center-modal{{ $department->id }}" tabindex="-1"
                        aria-labelledby="vertical-center-modal" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header d-flex align-items-center">
                                    <h5 class="modal-title" id="myLargeModalLabel">
                                        Konfirmasi
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah anda yakin ingin menghapus data ini?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"
                                        class="btn btn-light-danger text-danger font-medium waves-effect text-start"
                                        data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                                    <form action="{{ route('department.destroy', $department->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal{{ $department->id }}" tabindex="-1"
                        aria-labelledby="editModalLabel{{ $department->id }}" aria-hidden="true" data-bs-backdrop="static">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $department->id }}">Edit Department</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('department.update', $department->id) }}" method="POST">
                                        @method('PUT')
                                        @csrf
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama Department</label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror" id="name"
                                                value="{{ old('name') ?? $department->name }}">
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
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Create -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModal" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModal">Create Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('department.store') }}" method="POST">
                        @method('post')
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Department</label>
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
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $departments->links() }}
    </div>
@endsection

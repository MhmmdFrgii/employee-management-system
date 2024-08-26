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
                        <h1 class="h3">Permintaan Cuti</h1>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            Tambah Data
                        </button>

                        <form id="searchForm" action="{{ route('leave.index') }}" method="GET"
                            class="d-flex align-items-center gap-2">
                            @csrf
                            <div class="form-group mb-0 position-relative">
                                <label for="search" class="sr-only">Search:</label>
                                <input type="text" id="search" placeholder="Cari data..." name="search"
                                    value="{{ request('search') }}" class="form-control">
                            </div>
                            <div class="form-group mb-0 position-relative">
                                <label class="sr-only">Filter Status:</label>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="statusDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Filter Status
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                                        <li>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="status[]"
                                                    value="pending" id="statusPending"
                                                    {{ in_array('pending', request('status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="statusPending">
                                                    Pending
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="status[]"
                                                    value="approved" id="statusApproved"
                                                    {{ in_array('approved', request('status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="statusApproved">
                                                    Approved
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="status[]"
                                                    value="rejected" id="statusRejected"
                                                    {{ in_array('rejected', request('status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="statusRejected">
                                                    Rejected
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </form>


                    </div>

                    <div>


                        <!-- Modal Tambah Data -->
                        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel"
                            aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('leave.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addModalLabel">Tambah Permintaan Cuti</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="employee_id" class="form-label">Employee ID</label>
                                                <input type="text" class="form-control" id="employee_id"
                                                    name="employee_id" value="{{ old('employee_id') }}">
                                                @error('employee_id')
                                                    <p>{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="start_date" class="form-label">Mulai Ijin</label>
                                                <input type="date" class="form-control" id="start_date" name="start_date"
                                                    value="{{ old('start_date') }}">
                                                @error('start_date')
                                                    <p>{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="end_date" class="form-label">Sampai Tanggal</label>
                                                <input type="date" class="form-control" id="end_date" name="end_date"
                                                    value="{{ old('end_date') }}">
                                                @error('end_date')
                                                    <p>{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="type" class="form-label">Type</label>
                                                <input type="text" class="form-control" id="type" name="type"
                                                    value="{{ old('type') }}">
                                                @error('type')
                                                    <p>{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select class="form-control" id="status" name="status">
                                                    <option value="pending">Pending</option>
                                                    <option value="approved">Approved</option>
                                                    <option value="rejected">Rejected</option>
                                                </select>
                                                @error('status')
                                                    <p>{{ $message }}</p>
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
                    @if (request()->has('search') && $leaveRequest->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            Data tidak ditemukan.
                        </div>
                    @elseif ($leaveRequest->isNotEmpty())
                        <div class="row mt-3">
                            <table class="table table-bordered shadow">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>
                                            <a href="#" class="sort-link" data-sort="employee_id"
                                                data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                                Employee ID
                                                @if (request('sortBy') === 'employee_id')
                                                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="#" class="sort-link" data-sort="start_date"
                                                data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                                Mulai Ijin
                                                @if (request('sortBy') === 'start_date')
                                                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="#" class="sort-link" data-sort="end_date"
                                                data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                                Sampai Tanggal
                                                @if (request('sortBy') === 'end_date')
                                                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="#" class="sort-link" data-sort="type"
                                                data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                                Type
                                                @if (request('sortBy') === 'type')
                                                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="#" class="sort-link" data-sort="status"
                                                data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                                Status
                                                @if (request('sortBy') === 'status')
                                                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($leaveRequest as $data)
                                        <!-- Modal Edit Data -->
                                        <div class="modal fade" id="editModal{{ $data->id }}" tabindex="-1"
                                            aria-labelledby="editModalLabel{{ $data->id }}" aria-hidden="true"
                                            data-bs-backdrop="static" data-bs-keyboard="false">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('leave.update', $data->id) }}" method="POST">
                                                        @csrf
                                                        @method('put')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editModalLabel{{ $data->id }}">Edit
                                                                Permintaan Cuti</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Input fields for Edit -->
                                                            <div class="mb-3">
                                                                <label for="employee_id_{{ $data->id }}"
                                                                    class="form-label">Employee
                                                                    ID</label>
                                                                <input type="text" class="form-control"
                                                                    id="employee_id_{{ $data->id }}"
                                                                    name="employee_id"
                                                                    value="{{ old('employee_id', $data->employee_id) }}">
                                                                @error('employee_id')
                                                                    <p>{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="start_date_{{ $data->id }}"
                                                                    class="form-label">Mulai
                                                                    Ijin</label>
                                                                <input type="date" class="form-control"
                                                                    id="start_date_{{ $data->id }}" name="start_date"
                                                                    value="{{ old('start_date', $data->start_date) }}">
                                                                @error('start_date')
                                                                    <p>{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="end_date_{{ $data->id }}"
                                                                    class="form-label">Sampai
                                                                    Tanggal</label>
                                                                <input type="date" class="form-control"
                                                                    id="end_date_{{ $data->id }}" name="end_date"
                                                                    value="{{ old('end_date', $data->end_date) }}">
                                                                @error('end_date')
                                                                    <p>{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="type_{{ $data->id }}"
                                                                    class="form-label">Type</label>
                                                                <input type="text" class="form-control"
                                                                    id="type_{{ $data->id }}" name="type"
                                                                    value="{{ old('type', $data->type) }}">
                                                                @error('type')
                                                                    <p>{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="status_{{ $data->id }}"
                                                                    class="form-label">Status</label>
                                                                <select class="form-control"
                                                                    id="status_{{ $data->id }}" name="status">
                                                                    <option value="pending"
                                                                        {{ $data->status === 'pending' ? 'selected' : '' }}>
                                                                        Pending
                                                                    </option>
                                                                    <option value="approved"
                                                                        {{ $data->status === 'approved' ? 'selected' : '' }}>
                                                                        Approved
                                                                    </option>
                                                                    <option value="rejected"
                                                                        {{ $data->status === 'rejected' ? 'selected' : '' }}>
                                                                        Rejected
                                                                    </option>
                                                                </select>
                                                                @error('status')
                                                                    <p>{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan
                                                                Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->employee_id }}</td>
                                            <td>{{ $data->start_date }}</td>
                                            <td>{{ $data->end_date }}</td>
                                            <td>{{ $data->type }}</td>
                                            <td>{{ $data->status }}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $data->id }}">
                                                    Edit
                                                </button>

                                                <form action="{{ route('leave.destroy', $data->id) }}"
                                                    style="display: inline" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Apakah anda yakin inggin menghapus data ini')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    @endif

                    {{-- <!-- Pagination Links -->
                    <div class="d-flex justify-content-center">
                        {{ $leaveRequest->appends(request()->query())->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    </div>
    <ul class="pagination my-3">
        {{-- Previous Page Link --}}
        @if ($leaveRequest->onFirstPage())
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $leaveRequest->previousPageUrl() }}" rel="prev">Previous</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($leaveRequest->links()->elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $leaveRequest->currentPage())
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
        @if ($leaveRequest->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $leaveRequest->nextPageUrl() }}" rel="next">Next</a>
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

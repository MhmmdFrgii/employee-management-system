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

                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            Tambah Data
                        </button>

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
                                                    name="employee_id">
                                                @error('employee_id')
                                                    <p>{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="start_date" class="form-label">Mulai Ijin</label>
                                                <input type="date" class="form-control" id="start_date"
                                                    name="start_date">
                                                @error('start_date')
                                                    <p>{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="end_date" class="form-label">Sampai Tanggal</label>
                                                <input type="date" class="form-control" id="end_date" name="end_date">
                                                @error('end_date')
                                                    <p>{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="type" class="form-label">Type</label>
                                                <input type="text" class="form-control" id="type" name="type">
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




                        <form method="GET" action="{{ route('leave.index') }}" class="mt-3">
                            <div class="input-group mb-3">
                                <input type="text" name="search" class="form-control mr-2 rounded shadow"
                                    placeholder="Cari Data..." value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary rounded shadow" type="submit">Cari</button>
                            </div>
                        </form>
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
                                                                    name="employee_id" value="{{ $data->employee_id }}">
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
                                                                    value="{{ $data->start_date }}">
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
                                                                    value="{{ $data->end_date }}">
                                                                @error('end_date')
                                                                    <p>{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="type_{{ $data->id }}"
                                                                    class="form-label">Type</label>
                                                                <input type="text" class="form-control"
                                                                    id="type_{{ $data->id }}" name="type"
                                                                    value="{{ $data->type }}">
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
                                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $data->id }}">
                                                    Edit
                                                </button>

                                                <form action="{{ route('leave.destroy', $data->id) }}"
                                                    style="display: inline" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Apakah anda yakin inggin menghapus data ini')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    @endif

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center">
                        {{ $leaveRequest->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

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

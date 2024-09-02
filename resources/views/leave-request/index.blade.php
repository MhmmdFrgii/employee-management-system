@extends('dashboard.layouts.main')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-2">
                <h1 class="h3">Permintaan Cuti</h1>

                <div class="d-flex justify-content-end mb-3 mt-3">
                    <form id="searchForm" action="{{ route('leave-requests.index') }}" method="GET"
                        class="d-flex align-items-center gap-2">
                        @csrf
                        <div class="form-group mb-0 position-relative">
                            <label class="sr-only">Filter Status:</label>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="statusDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Filter Status
                                </button>
                                <ul class="dropdown-menu p-3" aria-labelledby="statusDropdown">
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="status[]" value="pending"
                                                id="statusPending"
                                                {{ in_array('pending', request('status', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="statusPending">Tertunda/label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="status[]" value="approved"
                                                id="statusApproved"
                                                {{ in_array('approved', request('status', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="statusApproved">Disetujui</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="status[]" value="rejected"
                                                id="statusRejected"
                                                {{ in_array('rejected', request('status', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="statusRejected">Ditolak</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group mb-0 position-relative">
                            <label for="search" class="sr-only">Cari:</label>
                            <input type="text" id="search" placeholder="Cari data..." name="search"
                                value="{{ request('search') }}" class="form-control rounded shadow search-input">
                            <a href="{{ route('leave-requests.index') }}"
                                class="clear-search btn btn-sm position-absolute top-50 translate-middle-y end-0 me-2"
                                style="z-index: 10; padding: 0.2rem 0.4rem; line-height: 1; display: none;">
                                X
                            </a>
                        </div>
                        <button type="submit" class="btn btn-secondary">Cari</button>
                    </form>
                </div>

                <div class="mt-3">
                    <table class="table border text-nowrap customize-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>
                                    <a
                                        href="{{ route('leave-requests.index', array_merge(request()->query(), ['sortBy' => 'employee_id', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Nama Karyawan
                                        @if (request('sortBy') === 'employee_id')
                                            @if (request('sortDirection') === 'asc')
                                                &#9650; <!-- Unicode character for upward arrow -->
                                            @else
                                                &#9660; <!-- Unicode character for downward arrow -->
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a
                                        href="{{ route('leave-requests.index', array_merge(request()->query(), ['sortBy' => 'start_date', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Mulai Ijin
                                        @if (request('sortBy') === 'start_date')
                                            @if (request('sortDirection') === 'asc')
                                                &#9650; <!-- Unicode character for upward arrow -->
                                            @else
                                                &#9660; <!-- Unicode character for downward arrow -->
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a
                                        href="{{ route('leave-requests.index', array_merge(request()->query(), ['sortBy' => 'end_date', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Sampai Tanggal
                                        @if (request('sortBy') === 'end_date')
                                            @if (request('sortDirection') === 'asc')
                                                &#9650; <!-- Unicode character for upward arrow -->
                                            @else
                                                &#9660; <!-- Unicode character for downward arrow -->
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a
                                        href="{{ route('leave-requests.index', array_merge(request()->query(), ['sortBy' => 'type', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Tipe
                                        @if (request('sortBy') === 'type')
                                            @if (request('sortDirection') === 'asc')
                                                &#9650; <!-- Unicode character for upward arrow -->
                                            @else
                                                &#9660; <!-- Unicode character for downward arrow -->
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a
                                        href="{{ route('leave-requests.index', array_merge(request()->query(), ['sortBy' => 'status', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Status
                                        @if (request('sortBy') === 'status')
                                            @if (request('sortDirection') === 'asc')
                                                &#9650; <!-- Unicode character for upward arrow -->
                                            @else
                                                &#9660; <!-- Unicode character for downward arrow -->
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th class="text-center">Aksi</th>
                            </tr>

                        </thead>

                        <tbody>
                            @forelse($leaveRequest as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->employe->name }}</td>
                                    <td>{{ $data->start_date }}</td>
                                    <td>{{ $data->end_date }}</td>
                                    <td>{{ $data->type }}</td>
                                    <td>{{ ucfirst($data->status) }}</td>
                                    <td>
                                        {{-- <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $data->id }}">
                                            Edit
                                        </button> --}}

                                        @if ($data->status !== 'approved' && $data->status !== 'rejected')
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#approveModal{{ $data->id }}">
                                                Approve
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#rejectModal{{ $data->id }}">
                                                Reject
                                            </button>
                                        @endif

                                        <form action="{{ route('leave-requests.destroy', $data->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this item?');">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>

                                        <!-- Approve Modal -->
                                        <div class="modal fade" id="approveModal{{ $data->id }}" tabindex="-1"
                                            aria-labelledby="approveModalLabel{{ $data->id }}" aria-hidden="true"
                                            data-bs-backdrop="static">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('leave-requests.approve', $data->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="approveModalLabel{{ $data->id }}">Approve
                                                                Permintaan Cuti</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menyetujui permintaan cuti ini?</p>
                                                            <input type="hidden" name="status" value="approved">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">Approve</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Reject Modal --}}
                                        <div class="modal fade" id="rejectModal{{ $data->id }}" tabindex="-1"
                                            aria-labelledby="rejectModalLabel{{ $data->id }}" aria-hidden="true"
                                            data-bs-backdrop="static">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('leave-requests.reject', $data->id) }}"
                                                        method="POST">
                                                        @csrf

                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="rejectModalLabel{{ $data->id }}">Tolak
                                                                Permintaan Cuti</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menolak permintaan cuti ini?</p>
                                                            <input type="hidden" name="status" value="rejected">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Tolak</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal{{ $data->id }}" tabindex="-1"
                                    aria-labelledby="editModalLabel{{ $data->id }}" aria-hidden="true"
                                    data-bs-backdrop="static">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('leave-requests.update', $data->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $data->id }}">Edit
                                                        Permintaan Cuti</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="employee_id" class="form-label">Karyawan</label>
                                                        <select name="employee_id" id="employee_id"
                                                            class="form-control @error('employee_id') is-invalid @enderror">
                                                            <option value="">--Karyawan--</option>
                                                            @foreach ($employee as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ old('employee_id', $data->employee_id) == $item->id ? 'selected' : '' }}>
                                                                    {{ $item->fullname }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('employee_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="start_date" class="form-label">Mulai Ijin</label>
                                                        <input type="date"
                                                            class="form-control @error('start_date') is-invalid @enderror"
                                                            id="start_date" name="start_date"
                                                            value="{{ old('start_date', $data->start_date) }}">
                                                        @error('start_date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="end_date" class="form-label">Sampai Tanggal</label>
                                                        <input type="date"
                                                            class="form-control @error('end_date') is-invalid @enderror"
                                                            id="end_date" name="end_date"
                                                            value="{{ old('end_date', $data->end_date) }}">
                                                        @error('end_date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="type" class="form-label">Tipe</label>
                                                        <input type="text"
                                                            class="form-control @error('type') is-invalid @enderror"
                                                            id="type" name="type"
                                                            value="{{ old('type', $data->type) }}">
                                                        @error('type')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="status" class="form-label">Status</label>
                                                        <select class="form-control @error('status') is-invalid @enderror"
                                                            id="status" name="status">
                                                            <option value="pending"
                                                                {{ old('status', $data->status) == 'pending' ? 'selected' : '' }}>
                                                                Pending</option>
                                                            <option value="approved"
                                                                {{ old('status', $data->status) == 'approved' ? 'selected' : '' }}>
                                                                Approved</option>
                                                            <option value="rejected"
                                                                {{ old('status', $data->status) == 'rejected' ? 'selected' : '' }}>
                                                                Rejected</option>
                                                        </select>
                                                        @error('status')
                                                            <div class="invalid-feedback">{{ $message }}</div>
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

                                <!-- Modal Delete -->
                                <div class="modal fade" id="deleteSalariesModal{{ $data->id }}" tabindex="-1"
                                    aria-labelledby="deleteSalariesModalLabel{{ $data->id }}" aria-hidden="true"
                                    data-bs-backdrop="static">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteSalariesModalLabel{{ $data->id }}">
                                                    Konfirmasi Hapus Data</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Yakin untuk menghapus data ini?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('salaries.destroy', $data->id) }}" method="POST">
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
                                    <td colspan="7" class="text-center">
                                        <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data"
                                            class="img-fluid" style="width: clamp(150px, 50vw, 300px);">
                                        <p class="mt-3">Tidak ada data tersedia</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3 justify-content-end">
                        {{ $leaveRequest->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

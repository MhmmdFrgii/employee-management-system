@extends('dashboard.layouts.main')

@section('content')
    <div class="card px-3 pb-4 mb-1 pt-1 rounded-sm">
        <div class="row g-2 mt-3">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="row g-2">
                    <h3 class="mx-1">Permintaan Cuti</h3>
                </div>
            </div>
            @include('leave-request.partial.filter')
        </div>
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-2">
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
                                    <td>{{ $data->employee_detail->name }}</td>
                                    <td>{{ $data->start_date }}</td>
                                    <td>{{ $data->end_date }}</td>
                                    <td>{{ $data->type }}</td>
                                    <td>
                                        {{-- {{ ucfirst($data->status) }} --}}
                                        @switch($data->status)
                                            @case('pending')
                                                Tertunda
                                            @break

                                            @case('approved')
                                                Disetujui
                                            @break

                                            @case('rejected')
                                                Ditolak
                                            @break

                                            @default
                                                {{ ucfirst($data->status) }}
                                        @endswitch
                                    </td>
                                    <td>
                                        {{-- <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $data->id }}">
                                            Edit
                                        </button> --}}

                                        @if ($data->status !== 'approved' && $data->status !== 'rejected')
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#approveModal{{ $data->id }}">
                                                Disetujui
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#rejectModal{{ $data->id }}">
                                                Ditolak
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
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $data->id }}">
                                            <i class="bx bx-info-circle"></i>
                                        </button>

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
                                                            <button type="submit" class="btn btn-primary">Setujui</button>
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
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
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
                                            <form action="{{ route('leave-requests.update', $data->id) }}" method="POST">
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
                                                                Tertunda</option>
                                                            <option value="approved"
                                                                {{ old('status', $data->status) == 'approved' ? 'selected' : '' }}>
                                                                Disetujui</option>
                                                            <option value="rejected"
                                                                {{ old('status', $data->status) == 'rejected' ? 'selected' : '' }}>
                                                                Ditolak</option>
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

                                <!-- Detail Modal -->
                                <div class="modal fade" id="detailModal{{ $data->id }}" tabindex="-1"
                                    aria-labelledby="detailModalLabel{{ $data->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailModalLabel{{ $data->id }}">Detail
                                                    Permintaan Cuti</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="col-md-12">
                                                    <!-- Details Row -->
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <h6 class="text-muted">Nama:</h6>
                                                            <p id="modal-fullname" class="fw-semibold">
                                                                {{ $data->employee_detail->name }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6 class="text-muted">Tanggal Mulai:</h6>
                                                            <p id="modal-start_date" class="fw-semibold">
                                                                {{ $data->start_date }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <h6 class="text-muted">Tipe:</h6>
                                                            <p id="modal-type" class="fw-semibold">{{ $data->type }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6 class="text-muted">Tanggal Selesai:</h6>
                                                            <p id="modal-end_date" class="fw-semibold">
                                                                {{ $data->end_date }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <h6 class="text-muted">Bukti: </h6>
                                                        <a href="{{ asset('storage/' . $data->photo) }}" target="_blank">
                                                            <img id="modal-photo"
                                                                src="{{ asset('storage/' . $data->photo) }}"
                                                                class="img-fluid mb-3 w-100" alt="Employee photo"
                                                                onerror="this.src='{{ asset('assets/images/no-data.png') }}';">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
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

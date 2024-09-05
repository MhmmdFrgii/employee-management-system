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
                        <h1 class="h3">Kehadiran</h1>
                    </div>

                    {{-- SEARCH & FILTER --}}
                    <form method="GET" action="{{ route('attendance.index') }}" class="mb-4">
                        <div class="row g-2">
                            <!-- Input Pencarian -->
                            <div class="col-md-9">
                                <input type="text" name="search" class="form-control shadow-sm"
                                    placeholder="Cari Data..." value="{{ request('search') }}">
                            </div>

                            <!-- Input Filter -->
                            <div class="col-md-2">
                                <div class="dropdown w-100">
                                    <button class="btn btn-secondary dropdown-toggle w-100" type="button"
                                        id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Filter Status
                                    </button>
                                    <ul class="dropdown-menu p-2" aria-labelledby="statusDropdown">
                                        <label for="status_present" class="bg-danger w-100">
                                            <div class="form-check ms-4">
                                                <input class="form-check-input" type="checkbox" name="status[]"
                                                    value="present" id="status_present"
                                                    {{ in_array('present', request('status', [])) ? 'checked' : '' }}>
                                                <span class="form-check-label" for="status_present">
                                                    Hadir
                                                </span>
                                            </div>
                                        </label>
                                        <label for="status_absent" class="bg-danger w-100">
                                            <div class="form-check ms-4">
                                                <input class="form-check-input" type="checkbox" name="status[]"
                                                    value="absent" id="status_absent"
                                                    {{ in_array('absent', request('status', [])) ? 'checked' : '' }}>
                                                <span class="form-check-label" for="status_absent">
                                                    Izin
                                                </span>
                                            </div>
                                        </label>
                                        <label for="status_late" class="bg-danger w-100">
                                            <div class="form-check ms-4">
                                                <input class="form-check-input" type="checkbox" name="status[]"
                                                    value="late" id="status_late"
                                                    {{ in_array('late', request('status', [])) ? 'checked' : '' }}>
                                                <span class="form-check-label" for="status_late">
                                                    Terlambat
                                                </span>
                                            </div>
                                        </label>
                                        <label for="status_alpha" class="bg-danger w-100">
                                            <div class="form-check ms-4">
                                                <input class="form-check-input" type="checkbox" name="status[]"
                                                    value="alpha" id="status_alpha"
                                                    {{ in_array('alpha', request('status', [])) ? 'checked' : '' }}>
                                                <span class="form-check-label" for="status_alpha">
                                                    Alpha
                                                </span>
                                            </div>
                                        </label>
                                    </ul>
                                </div>
                            </div>

                            <!-- Tombol Cari -->
                            <div class="col-md-1">
                                <button class="btn btn-outline-secondary rounded shadow-sm" type="submit">Cari</button>
                            </div>
                        </div>
                    </form>
                    <!-- Tabel Kehadiran -->
                    <div class="table-responsive">
                        <table class="table border text-nowrap customize-table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th class="mr-5">
                                        <a href="#" class="sort-link" data-sort="employee_id"
                                            data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                            Nama Karyawan
                                            @if (request('sortBy') === 'employee_id')
                                                <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="#" class="sort-link" data-sort="date"
                                            data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                            Tanggal
                                            @if (request('sortBy') === 'date')
                                                <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th> Status </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->employee_detail->name }}</td>
                                        <td>{{ $attendance->date }}</td>
                                        <td>{{ $attendance->status }}</td>
                                    </tr>
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

                        <!-- Pagination Links -->
                        <div class="mt-3 justify-content-end">
                            {{ $attendances->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Sorting Links -->
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

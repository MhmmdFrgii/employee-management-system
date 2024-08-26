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
                    <h1 class="h3">Attendance</h1>
                    <form action="{{ route('attendance.store') }}" method="post">
                        @csrf
                        <button button type="button" class="btn btn-primary">Absen</button>
                        </form>
                </div>

                {{-- SEARCH & FILTER --}}
                <form method="GET" action="{{ route('attendance.index') }}" class="mb-4">
                    <div class="row g-2">
                        <!-- Input Pencarian -->
                        <div class="col-md-8">
                            <input type="text" name="search" class="form-control shadow-sm" placeholder="Cari Data..." value="{{ request('search') }}">
                        </div>

                        <!-- Dropdown Filter Status -->
                        <div class="col-md-3">
                            <select name="status" class="form-select shadow-sm">
                                <option value="">-- Pilih Status --</option>
                                <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                                <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                            </select>
                        </div>

                        <!-- Tombol Cari -->
                        <div class="col-md-1">
                            <button class="btn btn-secondary w-100 shadow-sm" type="submit">Cari</button>
                        </div>
                    </div>
                </form>
                <!-- Alert ketika data tidak ditemukan -->
                @if (request()->has('search') && $attendances->isEmpty())
                    <div class="alert alert-warning" role="alert">
                        Data tidak ditemukan.
                    </div>
                @elseif ($attendances->isNotEmpty())

                    <div class="row mt-3">
                        <table class="table table-bordered shadow">
                            <thead>
                                <tr>
                                    <th class="mr-5">
                                        <a href="#" class="sort-link" data-sort="employee_id" 
                                        data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                            Employee
                                            @if (request('sortBy') === 'employee_id')
                                                <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="#" class="sort-link" data-sort="date" 
                                        data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                            Date
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
                                        <td> {{ $attendance->employee_id }}</td>
                                        <td> {{ $attendance->date }}</td>
                                        <td> {{ $attendance->status }}</td>
                                    </tr>
                                @empty
                                    <td>tidak ada data.</td>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <div class="mt-3">
                            {{ $attendances->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
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

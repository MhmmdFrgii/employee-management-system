@extends('dashboard.layouts.main')

@section('content')
    <div class="card px-3 pb-4 mb-1 pt-1 rounded-sm">
        <div class="row g-2 mt-3">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="row g-2">
                    <h3 class="mx-1">Absensi Karyawan</h3>
                </div>
            </div>
            @include('attendance.partials.filter')
        </div>
    </div>
    <div class="card">
        <div class="card-body border-top">
            <div class="table-responsive table-card p-3">
                <div class="tab-pane fade show active">
                    <table class="table align-middle shadow-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 1rem">No.</th>
                                <th scope="col"><a href="#" class="sort-link text-black" data-sort="employee_id"
                                        data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                        Karyawan
                                        @if (request('sortBy') === 'employee_id')
                                            <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col">Departemen</th>
                                <th scope="col">
                                    <a href="#" class="sort-link text-black" data-sort="date"
                                        data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                        Tanggal
                                        @if (request('sortBy') === 'date')
                                            <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                        @endif
                                    </a>

                                </th>
                                <th scope="col" class="text-center">Keterangan</th>
                                <th scope="col" class="text-center">Masuk</th>
                                <th scope="col" class="text-center">Pulang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $employee)
                                <tr>
                                    <td>{{ $loop->iteration }} .</td>
                                    <td class="text-uppercase">{{ $employee->name }}</td>
                                    <td class="text-uppercase">{{ $employee->department->name }}</td>
                                    <td>{{ $selectedDate->format('Y-m-d') }}</td>
                                    @php $attendance = $employee->attendances->first(); @endphp

                                    @if ($employee->attendances->isNotEmpty())
                                        <td class="text-center">
                                            @if ($attendance->status == 'present')
                                                <span class="badge bg-success-subtle text-success py-2 px-3">masuk</span>
                                            @elseif($attendance->status == 'late')
                                                <span class="badge bg-danger-subtle text-danger py-2 px-3">telat</span>
                                            @elseif($attendance->status == 'absent')
                                                <span class="badge bg-warning-subtle text-warning py-2 px-3">izin</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning py-2 px-3">alpha</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($attendance->status == 'present')
                                                <span class="badge bg-success-subtle text-success py-2 px-3">
                                                    {{ $attendance->created_at->format('H:i') }}
                                                </span>
                                            @elseif ($attendance->status == 'late')
                                                <span class="badge bg-danger-subtle text-danger py-2 px-3">
                                                    {{ $attendance->created_at->format('H:i') }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary py-2 px-3">
                                                    Tidak Ada Waktu
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success py-2 px-3">
                                                {{ \Carbon\Carbon::parse($attendance->checkout_time)->format('H:i') }}

                                            </span>
                                        </td>
                                    @else
                                        <td class="text-center">
                                            <span class="badge bg-danger-subtle text-danger py-2 px-3">alpha</span>
                                        </td>
                                        <td></td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data"
                                            class="img-fluid" style="width: clamp(150px, 50vw, 300px);">
                                        <p class="mt-3">Tidak ada data tersedia</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Export Excel Modal -->
        <div class="modal fade" id="exportExcelModal" tabindex="-1" aria-labelledby="exportExcelModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exportExcelModalLabel">Rekap Excel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('attendance.export') }}" method="get">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="yearInput" class="form-label">Tahun</label>
                                <select class="form-select" id="yearInput" name="year">

                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="monthInput" class="form-label">Bulan</label>
                                <select class="form-select" id="monthInput" name="month">

                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Rekap</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Isi pilihan tahun (5 tahun terakhir hingga tahun ini)
            const currentYear = new Date().getFullYear();
            const yearInput = document.getElementById('yearInput');
            for (let year = currentYear; year >= currentYear - 1; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearInput.appendChild(option);
            }

            // Isi pilihan bulan
            const monthInput = document.getElementById('monthInput');
            const months = [{
                    value: 1,
                    name: 'Januari'
                },
                {
                    value: 2,
                    name: 'Februari'
                },
                {
                    value: 3,
                    name: 'Maret'
                },
                {
                    value: 4,
                    name: 'April'
                },
                {
                    value: 5,
                    name: 'Mei'
                },
                {
                    value: 6,
                    name: 'Juni'
                },
                {
                    value: 7,
                    name: 'Juli'
                },
                {
                    value: 8,
                    name: 'Agustus'
                },
                {
                    value: 9,
                    name: 'September'
                },
                {
                    value: 10,
                    name: 'Oktober'
                },
                {
                    value: 11,
                    name: 'November'
                },
                {
                    value: 12,
                    name: 'Desember'
                }
            ];


            months.forEach(function(month) {
                const option = document.createElement('option');
                option.value = month.value;
                option.textContent = month.name;
                monthInput.appendChild(option);
            });
        });
    </script>
@endsection

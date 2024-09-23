@extends('dashboard.layouts.main')

@section('content')

    <div class="card px-3 pb-4 mb-1 pt-1 rounded-sm">
        <div class="row g-2 mt-3">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="row g-2">
                    <h3 class="mx-1">Gaji Karyawan</h3>
                </div>
            </div>
            @include('salaries.partial.search')
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-2">
                <div class="table-responsive">
                    <table class="table border text-nowrap customize-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>
                                    <a
                                        href="{{ route('salaries.index', array_merge(request()->query(), ['sortBy' => 'employee', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Nama Karyawan
                                        @if (request('sortBy') === 'employee')
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
                                        href="{{ route('salaries.index', array_merge(request()->query(), ['sortBy' => 'amount', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Gaji
                                        @if (request('sortBy') === 'amount')
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
                                        href="{{ route('salaries.index', array_merge(request()->query(), ['sortBy' => 'extra', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Bonus
                                        @if (request('sortBy') === 'extra')
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
                                        href="{{ route('salaries.index', array_merge(request()->query(), ['sortBy' => 'payment_date', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Tanggal Pembayaran
                                        @if (request('sortBy') === 'payment_date')
                                            @if (request('sortDirection') === 'asc')
                                                &#9650;
                                            @else
                                                &#9660;
                                            @endif
                                        @endif
                                    </a>
                                </th>


                                <th>Total Gaji</th>


                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($salaries as $salary)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $salary->employee_detail->name ?? 'N/A' }}</td>
                                    <td>Rp {{ number_format($salary->amount, 2, ',', '.') }}</td>
                                    <td>Rp {{ number_format($salary->extra, 2, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($salary->transaction_date)->format('d M Y') }}</td>

                                    <td>Rp {{ number_format($salary->total_amount, 2, ',', '.') }}</td>
                                    <td class="text-center">
                                        <button data-bs-target="#editSalariesModal{{ $salary->id }}"
                                            data-bs-toggle="modal" class="btn btn-warning btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#deleteSalariesModal{{ $salary->id }}"
                                            type="button">Hapus</button>

                                        <!-- Form hapus -->
                                        <form action="{{ route('salaries.destroy', $salary->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" id="delete-button-{{ $salary->id }}"
                                                class="btn btn-sm btn-danger" style="display: none;">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- content modal  --}}
                                @include('salaries.partial.edit-modal')
                                @include('salaries.partial.delete-modal', ['salary' => $salary])
                                {{-- end content modal --}}

                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data"
                                            class="img-fluid" style="width: clamp(150px, 50vw, 300px);">
                                        <p class="mt-3">Tidak ada data tersedia</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="mt-3 justify-content-end">
                    {{ $salaries->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- add modal --}}
    @include('salaries.partial.add-modal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function formatNumber(number) {
            return Math.floor(number).toString();
        }


        document.getElementById('create_employee_id').addEventListener('change', function() {
            const employeeId = this.value;

            if (employeeId) {
                fetch(`{{ route('salary.getEmployeeSalary', '') }}/${employeeId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Received data:', data);
                        if (data.salary !== undefined) {
                            const formattedSalary = formatNumber(data.salary);

                            document.getElementById('create_amount').value = formattedSalary;
                        } else {
                            console.log('Salary data is undefined');
                            document.getElementById('create_amount').value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching salary:', error);
                        document.getElementById('create_amount').value = '';
                    });
            } else {
                document.getElementById('create_amount').value = '';
            }
        });
    </script>

@endsection

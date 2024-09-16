@extends('dashboard.layouts.main')

@section('content')
    <div class="card px-3 pb-4 mb-1 pt-1 rounded-sm">
        <div class="row g-2 mt-3">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="row g-2">
                    <h3 class="mx-1">Catatan Keuangan</h3>
                </div>
            </div>
            @include('finance.partial.filter')
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
                                        href="{{ route('transactions.index', array_merge(request()->query(), ['sortBy' => 'total_amount', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Jumlah
                                        @if (request('sortBy') === 'total_amount')
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
                                        href="{{ route('transactions.index', array_merge(request()->query(), ['sortBy' => 'type', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Jenis Transaksi
                                        @if (request('sortBy') === 'type')
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
                                        href="{{ route('transactions.index', array_merge(request()->query(), ['sortBy' => 'description', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
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
                                <th>
                                    <a
                                        href="{{ route('transactions.index', array_merge(request()->query(), ['sortBy' => 'transaction_date', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Tanggal Transaksi
                                        @if (request('sortBy') === 'transaction_date')
                                            @if (request('sortDirection') === 'asc')
                                                &#9650;
                                            @else
                                                &#9660;
                                            @endif
                                        @endif
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($finance as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>Rp {{ number_format($data->amount, 2, ',', '.') }}</td>
                                    @php
                                        $types = [
                                            'income' => 'Pemasukan',
                                            'expense' => 'Pengeluaran',
                                        ];
                                    @endphp
                                    <td>{{ $types[$data->type] }}</td>
                                    <td>{{ $data->description ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->transaction_date)->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="col-12 text-center">
                                            <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data"
                                                class="img-fluid" style="width: clamp(150px, 50vw, 300px);">
                                            <p class="mt-3">Tidak ada data tersedia</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="mt-3 justify-content-end">
                    {{ $finance->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Adding Transaction -->
    <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransactionLabel">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTransactionForm" action="{{ route('transactions.store') }}" method="POST">
                        @csrf
                        <!-- Type (Income or Expense) -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Jenis Transaksi</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type"
                                required>
                                <option value="income" {{ old('type') === 'income' ? 'selected' : '' }}>Pemasukan</option>
                                <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>Pengeluaran
                                </option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah</label>
                            <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                                id="amount" name="amount" placeholder="Jumlah Transaksi" value="{{ old('amount') }}">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description (Optional) -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi (opsional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3" placeholder="Deskripsi">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Transaction Date -->
                        <div class="mb-3">
                            <label for="transaction_date" class="form-label">Tanggal Transaksi</label>
                            <input type="date" class="form-control @error('transaction_date') is-invalid @enderror"
                                id="transaction_date" name="transaction_date" value="{{ old('transaction_date') }}">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Tambah Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


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

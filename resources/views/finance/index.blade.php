@extends('dashboard.layouts.main')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-2">
                <h1 class="h3">Catatan Keuangan</h1>

                <div class="d-flex justify-content-end mb-4 mt-3">
                    <!-- Button to trigger the Add Transaction Modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                        Add Transaction
                    </button>

                    <form action="{{ route('transactions.index') }}" method="GET" class="d-flex align-items-center">
                        <div class="dropdown me-3">
                            <button class="btn btn-secondary dropdown-toggle shadow" type="button" id="statusDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Status
                            </button>
                            <ul class="dropdown-menu mt-2" aria-labelledby="statusDropdown">
                                <li>
                                    <label for="statusIncome" class="w-100">
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" name="status[]" value="income"
                                                id="statusIncome"
                                                {{ in_array('income', request('status', [])) ? 'checked' : '' }}>
                                            <span class="form-check-label" for="statusIncome">
                                                Pemasukan
                                            </span>
                                        </div>
                                    </label>
                                    <label for="statusExpense" class="w-100">
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" name="status[]" value="expense"
                                                id="statusExpense"
                                                {{ in_array('expense', request('status', [])) ? 'checked' : '' }}>
                                            <span class="form-check-label" for="statusExpense">
                                                Pengeluaran
                                            </span>
                                        </div>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div class="position-relative me-3">
                            <input type="text" name="search" class="form-control rounded shadow search-input"
                                placeholder="Cari Data..." value="{{ request('search') }}">
                            <a href="{{ route('transactions.index') }}"
                                class="clear-search btn btn-sm position-absolute top-50 translate-middle-y end-0 me-2"
                                style="z-index: 10; padding: 0.2rem 0.4rem; line-height: 1; display: {{ request('search') ? 'block' : 'none' }};">
                                X
                            </a>
                        </div>
                        <button type="submit" class="btn btn-secondary">Cari</button>
                    </form>
                </div>

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
                                    <td>{{ $data->description ?? 'N/A' }}</td>
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
                    <h5 class="modal-title" id="addTransactionLabel">Add Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTransactionForm" action="{{ route('transactions.store') }}" method="POST">
                        @csrf
                        <!-- Type (Income or Expense) -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                            </select>
                        </div>

                        <!-- Amount -->
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount"
                                placeholder="Enter Amount" required>
                        </div>

                        <!-- Description (Optional) -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter Description"></textarea>
                        </div>

                        <!-- Transaction Date -->
                        <div class="mb-3">
                            <label for="transaction_date" class="form-label">Transaction Date</label>
                            <input type="date" class="form-control" id="transaction_date" name="transaction_date"
                                required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Transaction</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

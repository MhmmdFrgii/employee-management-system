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
                        <h1 class="h3">Salaries</h1>
                        <form action="{{ route('salaries.store') }}" method="post">
                            @csrf
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSalariesModal">
                                Tambah 
                            </button>
                            </form>
                    </div>

                    {{-- SEARCH --}}
                    <form method="GET" action="{{ route('salaries.index') }}" class="mb-4">
                        <div class="row g-2">
                            <!-- Input Pencarian -->
                            <div class="col-md-9">
                                <input type="text" name="search" class="form-control shadow-sm" placeholder="Cari Data..." value="{{ request('search') }}">
                            </div>

                            
                            <!-- Tombol Cari -->
                            <div class="col-md-1">
                                <button class="btn btn-outline-secondary rounded shadow-sm" type="submit">Cari</button>
                            </div>
                        </div>
                    </form>
                    <!-- Alert ketika data tidak ditemukan -->
                    @if (request()->has('search') && $salarie->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            Data tidak ditemukan.
                        </div>
                    @elseif ($salarie->isNotEmpty())

                        <div class="row mt-3">
                            <table class="table table-bordered shadow">
                                <thead>
                                    <tr>
                                        <th> Nama Karyawan </th>
                                        <th class="mr-5">
                                            <a href="#" class="sort-link" data-sort="amount" 
                                            data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                                Gaji
                                                @if (request('sortBy') === 'amount')
                                                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="#" class="sort-link" data-sort="payment_date" 
                                            data-direction="{{ request('sortDirection') === 'asc' ? 'desc' : 'asc' }}">
                                                Tanggal Pembayaran
                                                @if (request('sortBy') === 'payment_date')
                                                    <span>{{ request('sortDirection') === 'asc' ? '▲' : '▼' }}</span>
                                                @endif
                                            </a>
                                        </th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($salarie as $s)
                                        <tr>
                                            <td> {{ $s->employee }}</td>
                                            <td> Rp {{ number_format($s->amount, 2, ',', '.') }}</td>
                                            <td> {{ \Carbon\Carbon::parse($s->payment_date)->format('d M Y') }}</td>
                                            <td>
                                                <!-- Tombol untuk membuka modal edit project -->
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSalariesModal{{ $s->id }}">
                                                    Edit
                                                </button>

                                                <!-- Tombol untuk membuka modal konfirmasi hapus -->
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteSalariesModal{{ $s->id }}">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal Form Edit Salarie -->
                                        <div class="modal fade" id="editSalariesModal{{ $s->id }}" tabindex="-1" aria-labelledby="editSalariesModalLabel{{ $s->id }}" aria-hidden="true" data-bs-backdrop="static">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editSalariesModalLabel{{ $s->id }}">Edit Salarie</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('salaries.update', $s->id) }}" method="POST">
                                                            @method('PUT')
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="employee" class="form-label">Nama Karyawan</label>
                                                                <input type="text" name="employee"
                                                                    class="form-control @error('employee') is-invalid @enderror" id="employee"
                                                                    value="{{ old('employee', $s->employee) }}">
                                                                @error('employee')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="amount" class="form-label">Gaji</label>
                                                                <input type="text" name="amount"
                                                                    class="form-control @error('amount') is-invalid @enderror" id="amount"
                                                                    value="{{ old('amount', $s->amount) }}">
                                                                @error('amount')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="payment_date" class="form-label">Tanggal Pembayaran</label>
                                                                <input type="date" name="payment_date"
                                                                    class="form-control @error('payment_date') is-invalid @enderror" id="payment_date"
                                                                    value="{{ old('payment_date', $s->payment_date) }}">
                                                                @error('payment_date')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Form Delete Salarie -->        
                                        <div class="modal fade" id="deleteSalariesModal{{ $s->id }}" tabindex="-1" aria-labelledby="deleteSalariesModalLabel{{ $s->id }}" aria-hidden="true" data-bs-backdrop="static">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteSalariesModalLabel{{ $s->id }}">Konfirmasi Hapus Data</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <form action="{{ route('salaries.destroy', $s->id) }}" method="POST" style="display: inline;">
                                                            @method('DELETE')
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        <td>tidak ada data.</td>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- Pagination Links -->
                            <div class="mt-3">
                                {{ $salarie->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Form Create Project -->
    <div class="modal fade" id="createSalariesModal" tabindex="-1" aria-labelledby="createSalariesModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSalariesModalLabel">Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('salaries.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="employee" class="form-label">Nama Karyawan</label>
                            <input type="text" name="employee" class="form-control @error('employee') is-invalid @enderror" id="employee" value="{{ old('employee') }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Gaji</label>
                            <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" value="{{ old('amount') }}">
                            @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Tanggal Pembayaran</label>
                            <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" value="{{ old('payment_date') }}">
                            @error('payment_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
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
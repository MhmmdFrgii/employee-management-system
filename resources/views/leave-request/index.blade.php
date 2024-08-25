{{-- @extends('dashboard.layouts.main')


@section('content')
@endsection --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

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
                        <h1 class="h3">Leave</h1>

                    </div>

                    <div class="">
                        <a href="{{ route('leave.create') }}" class="btn btn-primary">Tambah</a>

                        <form method="GET" action="{{ route('leave.index') }}">
                            <div class="input-group mb-3">
                                <input type="text" name="search" class="form-control mr-2 rounded shadow"
                                    placeholder="Cari Data..." value="">
                                <button class="btn btn-outline-secondary rounded shadow" type="submit">Cari</button>
                            </div>
                        </form>

                    </div>
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
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->employee_id }}</td>
                                        <td>{{ $data->start_date }}</td>
                                        <td>{{ $data->end_date }}</td>
                                        <td>{{ $data->type }}</td>
                                        <td>{{ $data->status }}</td>
                                        <td>
                                            <a href="{{ route('leave.edit', $data->id) }}"
                                                class="btn btn-warning">Edit</a>
                                            <form action="{{ route('leave.destroy', $data->id) }}"
                                                style="display: inline" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Apakah anda yakin inggin menghapus data ini')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center">
                            {{ $leaveRequest->appends(request()->query())->links() }}
                        </div>
                    </div>
                    <!-- Konten tambahan dapat ditempatkan di sini -->
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.sort-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // Ambil nilai sort dan direction dari data attributes
                const sort = this.getAttribute('data-sort');
                const direction = this.getAttribute('data-direction');

                // Redirect ke URL dengan query parameters yang diperbarui
                const url = new URL(window.location.href);
                url.searchParams.set('sortBy', sort);
                url.searchParams.set('sortDirection', direction);

                window.location.href = url.toString();
            });
        });
    </script>
</x-app-layout>

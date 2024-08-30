@extends('dashboard.layouts.main')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container py-2">

                <h1 class="h3">Karyawan</h1>
                <div class="d-flex justify-content-end mb-3 mt-3">
                    <form id="searchForm" action="{{ route('employee.index') }}" method="GET"
                        class="d-flex align-items-center gap-2 position-relative">
                        @csrf
                        <div class="form-group mb-0 position-relative" style="width: 100%;">
                            <label for="search" class="sr-only">Search:</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                class="form-control shadow search-input" placeholder="Cari data..">

                            <a href="{{ route('employee.index') }}"
                                class="clear-search btn btn-sm position-absolute top-50 translate-middle-y end-0 me-2"
                                style="z-index: 10; padding: 0.2rem 0.4rem; line-height: 1; display: none;">
                                X
                            </a>
                        </div>
                        <button type="submit" class="btn btn-secondary">Cari</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table id="employeeTable" class="table border text-nowrap customize-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>
                                    <a
                                        href="{{ route('employee.index', array_merge(request()->query(), ['sortBy' => 'name', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Name
                                        @if (request('sortBy') === 'name')
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
                                        href="{{ route('employee.index', array_merge(request()->query(), ['sortBy' => 'phone', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Phone
                                        @if (request('sortBy') === 'phone')
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
                                        href="{{ route('employee.index', array_merge(request()->query(), ['sortBy' => 'address', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Address
                                        @if (request('sortBy') === 'address')
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
                                        href="{{ route('employee.index', array_merge(request()->query(), ['sortBy' => 'department', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Department
                                        @if (request('sortBy') === 'department')
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
                                        href="{{ route('employee.index', array_merge(request()->query(), ['sortBy' => 'position', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Position
                                        @if (request('sortBy') === 'position')
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
                                        href="{{ route('employee.index', array_merge(request()->query(), ['sortBy' => 'hire_date', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">
                                        Hire Date
                                        @if (request('sortBy') === 'hire_date')
                                            @if (request('sortDirection') === 'asc')
                                                &#9650;
                                            @else
                                                &#9660;
                                            @endif
                                        @endif
                                    </a>
                                </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse  ($employees as $employee)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $employee->user->name }}</td>
                                    <td>{{ $employee->phone }}</td>
                                    <td>{{ $employee->address }}</td>
                                    <td>{{ $employee->department->name }}</td>
                                    <td>{{ $employee->position->name }}</td>
                                    <td>{{ $employee->hire_date }}</td>
                                    <td>
                                        {{-- <a href="{{ route('employee.edit', $employee->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a> --}}
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#vertical-center-modal{{ $employee->id }}"
                                            type="button">Delete</button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="vertical-center-modal{{ $employee->id }}" tabindex="-1"
                                    aria-labelledby="vertical-center-modal" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex align-items-center">
                                                <h5 class="modal-title" id="myLargeModalLabel">
                                                    Konfimasi
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah anda yakin ingin menghapus data ini?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button"
                                                    class="btn btn-light-danger text-danger font-medium waves-effect text-start"
                                                    data-bs-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <form action="{{ route('employee.destroy', $employee->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger" type="submit"
                                                        data-bs-dismiss="modal">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data"
                                            class="img-fluid" style="width: clamp(150px, 50vw, 300px);">
                                        <p class="mt-3">No data available.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3 justify-content-end">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

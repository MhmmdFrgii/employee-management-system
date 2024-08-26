@extends('dashboard.layouts.main')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('employee.create') }}" class="btn btn-primary">Create</a>
        <form id="searchForm" action="{{ route('employee.index') }}" method="GET" class="d-flex align-items-center gap-2">
            @csrf
            <div class="form-group mb-0 position-relative">
                <label for="search" class="sr-only">Search:</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" class="form-control">
            </div>
            <a href="{{ route('employee.index') }}" class="btn btn-primary">X</a>
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
                            Phone
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $employee->user->name }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>{{ $employee->address }}</td>
                        <td>{{ $employee->department->name }}</td>
                        <td>{{ $employee->hire_date }}</td>
                        <td>{{ $employee->position->name }}</td>
                        <td>
                            <a href="{{ route('employee.edit', $employee->id) }}" class="btn btn-warning">Edit</a>
                            <button class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#vertical-center-modal{{ $employee->id }}" type="button">Delete</button>
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
                                    <form action="{{ route('employee.destroy', $employee->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit"
                                            data-bs-dismiss="modal">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <ul class="pagination my-3  ">
        {{-- Previous Page Link --}}
        @if ($employees->onFirstPage())
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $employees->previousPageUrl() }}" rel="prev">Previous</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($employees->links()->elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $employees->currentPage())
                        <li class="page-item active" aria-current="page">
                            <a class="page-link" href="#">{{ $page }}</a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($employees->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $employees->nextPageUrl() }}" rel="next">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a>
            </li>
        @endif
    </ul>
@endsection

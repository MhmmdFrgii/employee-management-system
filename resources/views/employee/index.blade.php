@extends('dashboard.layouts.main')

@section('content')
    <div class="card px-3 pb-4 mb-1 pt-1 rounded-sm">
        <div class="row g-2 mt-3">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="row g-2">
                    <h3 class="mx-1">Karyawan</h3>
                </div>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12">
                <form action="{{ route('employees.index') }}" method="GET">
                    <div class="d-flex flex-column flex-lg-row justify-content-end gap-2">
                        <div class="col-lg-4 col-12">
                            <div class="input-group">
                                <select name="department" class="form-select" id="departmenFilter">
                                    <option disabled selected>Departemen</option>
                                    @foreach ($departments as $departmen)
                                        <option value="{{ $departmen->name }}">
                                            {{ $departmen->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="search-box col-lg-4 col-12">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                    id="searchMemberList" placeholder="Cari Karyawan">
                                <div class="input-group-append ">
                                    <button type="submit" class="input-group-text rounded-end border border-1"><i
                                            class="ri-search-line"></i></button>
                                </div>
                                <button type="submit" class="btn btn-primary d-lg-none mt-2 w-100">Cari</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="container py-2">
            <div class="row">
                @forelse ($employees as $employee)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card hover-img">
                            <div class="card-body p-4 text-center border-bottom">
                                <img src="{{ $employee->photo && file_exists(public_path('storage/' . $employee->photo)) ? asset('storage/' . $employee->photo) : asset('/dist/images/profile/user-1.jpg') }}"
                                    alt="" class="rounded-circle mb-3" width="80" height="80">
                                <h5 class="fw-semibold mb-0">{{ $employee->name }}</h5>
                                <span
                                    class="text-dark fs-2 badge bg-primary text-white mt-1">{{ $employee->department->name }}</span>
                            </div>
                            <ul
                                class="px-2 py-2 bg-light list-unstyled d-flex align-items-center justify-content-center mb-0">
                                <li class="position-relative">
                                    <a class="text-primary d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold"
                                        href="#" data-id="{{ $employee->id }}" data-bs-toggle="modal"
                                        data-bs-target="#detailModal{{ $loop->iteration }}">
                                        <i class='bx bx-info-circle'></i>
                                    </a>
                                </li>
                                <li class="position-relative">
                                    <a class="text-warning d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold"
                                        href="#" data-id="{{ $employee->id }}" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $loop->iteration }}">
                                        <i class='bx bx-edit-alt'></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @include('employee.partial.detail-modal')
                    @include('employee.partial.edit-modal', [
                        'employee' => $employee,
                        'departments' => $departments,
                        'positions' => $positions,
                    ])

                @empty
                    <div class="col-12 text-center">
                        <img src="{{ asset('assets/images/no-data.png') }}" alt="No Data" class="img-fluid"
                            style="width: clamp(150px, 50vw, 300px);">
                        <p class="mt-3">Tidak ada data tersedia</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-3 justify-content-end">
                {{ $employees->links() }}
            </div>
        </div>
    </div>

    <script>
        document.getElementById('departmenFilter').addEventListener('change', function() {
            this.form.submit();
        })
    </script>
@endsection

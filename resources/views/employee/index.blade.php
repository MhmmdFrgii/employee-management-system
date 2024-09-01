@extends('dashboard.layouts.main')

{{-- @section('content')


    <div class="px-4">
        <!-- Owl carousel -->
        <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Daftar Karyawan</h4>
                        <nav aria-label="breadcrumb mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a class="text-muted " href="/employee/dashboard">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Manager</li>
                            </ol>
                        </nav>
                    </div>



                    <div class="col-3">
                        <div class="text-center mb-n5">
                            <img src="" alt="" class="img-fluid mb-n4">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="searchForm" action="{{ route('employee.index') }}" method="GET"
                        class="d-flex align-items-center gap-2">
                        @csrf
                        <div class="form-group mb-0 position-relative">
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

        <div class="row">
            @foreach ($employees as $employee)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card text-center">
                        <div class="card-body">

                            <!-- Tampilkan foto dari storage -->
                            <img src="{{ Storage::exists($employee->photo) ? asset('storage/' . $employee->photo) : asset('assets/images/no-profile.jpeg') }}"
                                alt="avatar" class="rounded-1 img-fluid" width="90px" height="90px">

                            <div class="mt-n2">
                                <!-- Tampilkan departemen -->
                                <span class="badge bg-primary">{{ $employee->department->name }}</span>
                                <!-- Tampilkan nama karyawan -->
                                <h3 class="card-title mt-3">{{ $employee->fullname }}</h3>
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-success btn-sm" data-id="{{ $employee->id }}" data-bs-toggle="modal" data-bs-target="#detailModal">View Detail</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @include('employee.partial.detail-modal');
        </div>
        <div class="mt-3 justify-content-end">
            {{ $employees->links() }}
        </div>
    </div>
@endsection --}}

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="container py-2">

            <h1 class="h3">Karyawan</h1>
            <div class="d-flex justify-content-end mb-3 mt-3">
                <form id="searchForm" action="{{ route('employees.index') }}" method="GET"
                    class="d-flex align-items-center gap-2">
                    @csrf
                    <div class="form-group mb-0 position-relative">
                        <label for="search" class="sr-only">Cari:</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            class="form-control shadow search-input" placeholder="Cari data..">

                        <a href="{{ route('employees.index') }}"
                            class="clear-search btn btn-sm position-absolute top-50 translate-middle-y end-0 me-2"
                            style="z-index: 10; padding: 0.2rem 0.4rem; line-height: 1; display: none;">
                            X
                        </a>
                    </div>
                    <button type="submit" class="btn btn-secondary">Cari</button>
                </form>
            </div>

            <div class="col-3">
                <div class="text-center mb-n5">
                    <img src="" alt="" class="img-fluid mb-n4">
                </div>
            </div>

            <div class="row mt-5">
                @foreach ($employees as $employee)

                <div class="col-sm-6 col-lg-4">
                    <div class="card hover-img">
                      <div class="card-body p-4 text-center border-bottom">
                        <img src="../../dist/images/profile/user-1.jpg" alt="" class="rounded-circle mb-3" width="80" height="80">
                        <h5 class="fw-semibold mb-0">{{ $employee->name }}</h5>
                        <span class="text-dark fs-2">{{ $employee->department->name }}</span>
                      </div>
                      <ul class="px-2 py-2 bg-light list-unstyled d-flex align-items-center justify-content-center mb-0">
                        <li class="position-relative">
                          <a class="text-primary d-flex align-items-center justify-content-center p-2 fs-5 rounded-circle fw-semibold" href="data-id="{{ $employee->id }} data-bs-toggle="modal" data-bs-target="#detailModal">
                            <i class='bx bx-info-circle'></i>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                @endforeach

                @include('employee.partial.detail-modal')
            </div>
            <div class="mt-3 justify-content-end">
                {{ $employees->links() }}
            </div>
@endsection


@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const detailButtons = document.querySelectorAll('.btn-success');

        detailButtons.forEach(button => {
            button.addEventListener('click', function () {
                const employeeId = this.dataset.id;
                const employee = @json($employees).find(emp => emp.id == employeeId);

                document.getElementById('modal-photo').src = employee.photo ? `{{ asset('storage/') }}/${employee.photo}` : `{{ asset('assets/images/no-profile.jpeg') }}`;
                document.getElementById('modal-fullname').textContent = employee.fullname;
                document.getElementById('modal-department').textContent = employee.department.name;
                document.getElementById('modal-position').textContent = employee.position.name;
                document.getElementById('modal-phone').textContent = employee.phone;
                document.getElementById('modal-gender').textContent = employee.gender;
                document.getElementById('modal-address').textContent = employee.address;
                document.getElementById('modal-hire-date').textContent = employee.hire_date;
            });
        });
    });
</script>
@endsection

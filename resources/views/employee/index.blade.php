@extends('dashboard.layouts.main')

@section('content')


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

@extends('dashboard.layouts.main')

@section('content')
    <div class="row gx-3">
        <div class="col-lg-3 col-md-6">
            <div class="card border-start border-primary">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <span class="text-primary display-6"><i class="ti ti-briefcase"></i></span>
                        </div>
                        <div class="ms-auto">
                            <h2 class="fs-7 counter">{{ $project_count }}</h2>
                            <h6 class="fw-medium text-primary mb-0">Total Proyek</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-start border-danger">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <span class="text-danger display-6"><i class="ti ti-user"></i></span>
                        </div>
                        <div class="ms-auto">
                            <h2 class="fs-7 counter">{{ $employee_count }}</h2>
                            <h6 class="fw-medium text-danger mb-0">Total Karyawan</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-start border-info">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <span class="text-info display-6"><i class="ti ti-category"></i></span>
                        </div>
                        <div class="ms-auto">
                            <h2 class="fs-7 counter">{{ $department_count }}</h2>
                            <h6 class="fw-medium text-info mb-0">Total Departemen</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-start border-warning">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <span class="text-warning display-6"><i class="ti ti-user-cog"></i></span>
                        </div>
                        <div class="ms-auto">
                            <h2 class="fs-7 counter">{{ $applicant_count }}</h2>
                            <h6 class="fw-medium text-warning mb-0">Menunggu Konfirmasi</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Proyek Departemen</h5>
                    </div>
                    <div class="card-body">
                        <div id="projectsBarChart"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Departemen</h5>
                    </div>
                    <div class="card-body">
                        <div id="pieChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Pemasukan & Pengeluaran</h5>
                    </div>
                    <div class="card-body">
                        {{-- <div id="transactionChart"></div> --}}
                        <div id="transactionChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Proyek</h5>
                    </div>
                    <div class="card-body">
                        <div id="projectsCompletedChart"></div>
                    </div>
                </div>
            </div>
        </div>

        @include('dashboard.partial.department-project')
        @include('dashboard.partial.department')
        @include('dashboard.partial.project')
        @include('dashboard.partial.pendapatan-pengeluaran')
        @include('dashboard.partial.tenggat-project')

        <style>
            .card-danger {
                border: 2px solid #dc3545;
                /* Red color for urgent deadlines */
                background-color: #f8d7da;
                /* Light red background */
                border-radius: 4px;
                /* Optional: for rounded corners */
                padding: 10px;
                /* Optional: for inner spacing */
                margin-bottom: 10px;
                /* Spacing between cards */
            }

            .card-primary {
                border: 2px solid #007bff;
                /* Blue color for non-urgent deadlines */
                background-color: #cce5ff;
                /* Light blue background */
                border-radius: 4px;
                /* Optional: for rounded corners */
                padding: 10px;
                /* Optional: for inner spacing */
                margin-bottom: 10px;
                /* Spacing between cards */
            }
        </style>

    @endsection

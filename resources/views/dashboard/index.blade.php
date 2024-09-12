@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col d-flex align-items-stretch">
            <div class="card w-100 bg-light-info overflow-hidden shadow-none">
                <div class="card-body position-relative">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="d-flex align-items-center mb-7">
                                <div class="rounded-circle overflow-hidden me-6">
                                    <img src="
                                    @if (Auth::user()->hasRole('manager')) {{ asset('dist/images/profile/user-4.jpg') }}
                                    @else
                                        {{ asset('assets/images/no-profile.jpeg') }} @endif
                                    "
                                        alt="" width="40" height="40">
                                </div>
                                <div>
                                    <h5 class="fw-semibold mb-0 xfs-5">Selamat datang kembali {{ auth()->user()->name }}
                                    </h5>
                                    @if (Auth::user()->hasRole('manager') && Auth::user()->company)
                                        <p class="mb-0 text-dark">Perusahaan: {{ Auth::user()->company->name }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="border-end pe-4 border-muted border-opacity-10">
                                    <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center">{{ $project_done }}<i
                                            class="{{ $project_done
                                                ? 'ti ti-arrow-up-right fs-5 lh-base text-success'
                                                : 'ti ti-arrow-down-right fs-5 lh-base text-danger' }}"></i>
                                    </h3>
                                    <p class="mb-0 text-dark">Project Selesai</p>
                                </div>
                                <div class="ps-4">
                                    <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center">{{ $performance }}%<i
                                            class="{{ $performance
                                                ? 'ti ti-arrow-up-right fs-5 lh-base text-success'
                                                : 'ti ti-arrow-down-right fs-5 lh-base text-danger' }}"></i>
                                    </h3>
                                    <p class="mb-0 text-dark">Kinerja Keseluruhan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="welcome-bg-img mb-n7 text-end">
                                <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/backgrounds/welcome-bg.svg"
                                    alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row gx-3">
        <div class="col-md-6 col-lg-3 col-6">
            <div class="card text-white bg-primary rounded">
                <div class="card-body p-4">
                    <span>
                        <i class="ti ti-layout-grid fs-8"></i>
                    </span>
                    <h3 class="card-title mt-3 mb-0 text-white">{{ $project_count }}</h3>
                    <p class="card-text text-white-50 fs-3 fw-normal">
                        Total Proyek
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-3 col-6">
            <div class="card text-white bg-secondary rounded">
                <div class="card-body p-4">
                    <span>
                        <i class="ti ti-users fs-8"></i>
                    </span>
                    <h3 class="card-title mt-3 mb-0 text-white">{{ $employee_count }}</h3>
                    <p class="card-text text-white-50 fs-3 fw-normal">
                        Total Karyawan
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-3 col-6">
            <div class="card text-white bg-warning rounded">
                <div class="card-body p-4">
                    <span>
                        <i class="ti ti-category-2 fs-8"></i>
                    </span>
                    <h3 class="card-title mt-3 mb-0 text-white">{{ $department_count }}</h3>
                    <p class="card-text text-white-50 fs-3 fw-normal">
                        Total Departemen
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-3 col-6">
            <div class="card text-white bg-danger rounded">
                <div class="card-body p-4">
                    <span>
                        <i class="ti ti-user-cog fs-8"></i>
                    </span>
                    <h3 class="card-title mt-3 mb-0 text-white">{{ $applicant_count }}</h3>
                    <p class="card-text text-white-50 fs-3 fw-normal">
                        Menunggu Konfirmasi
                    </p>
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

            @if (isset($departments) && $department_data)
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
            @endif
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

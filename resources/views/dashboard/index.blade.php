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
                                    <img src="../../dist/images/profile/user-1.jpg" alt="" width="40"
                                        height="40">
                                </div>
                                <h5 class="fw-semibold mb-0 fs-5">Welcome back {{ auth()->user()->name }}</h5>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="border-end pe-4 border-muted border-opacity-10">
                                    <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center">2,340<i
                                            class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
                                    </h3>
                                    <p class="mb-0 text-dark">Today’s Sales</p>
                                </div>
                                <div class="ps-4">
                                    <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center">35%<i
                                            class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
                                    </h3>
                                    <p class="mb-0 text-dark">Overall Performance</p>
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
                        Total Project
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
                    <h3 class="card-title mt-3 mb-0 text-white">0</h3>
                    <p class="card-text text-white-50 fs-3 fw-normal">
                        Menunggu Konfirmasi
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="projectsChart"></div>
            </div>
        </div>

        @if (isset($activeCounts) && isset($completedCounts) && isset($attendanceCounts))
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script>
                var projectOptions = {
                    series: [{
                        name: 'Active Projects',
                        data: @json($activeCounts)
                    }, {
                        name: 'Completed Projects',
                        data: @json($completedCounts)
                    }],
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: @json($months),
                    },
                    yaxis: {
                        title: {
                            text: 'Number of Projects'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " projects";
                            }
                        }
                    }
                };

                var projectChart = new ApexCharts(document.querySelector("#projectsChart"), projectOptions);
                projectChart.render();

                var attendanceOptions = {
                    series: @json($attendanceCounts),
                    chart: {
                        width: 380,
                        type: 'pie',
                    },
                    labels: ['Present', 'Absent', 'Late', 'Alpha'],
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                };

                var attendanceChart = new ApexCharts(document.querySelector("#attendanceChart"), attendanceOptions);
                attendanceChart.render();
            </script>
        @else
            <p>Data tidak ditemukan.</p>
        @endif
    @endsection

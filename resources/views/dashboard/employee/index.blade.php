@extends('dashboard.layouts.main')

{{-- @include('absenUser.partial.add-modal') --}}

@section('content')
    <div class="row">
        <div class="col d-flex align-items-stretch">
            <div class="card w-100 bg-light-info overflow-hidden shadow-none">
                <div class="card-body position-relative">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="d-flex align-items-center mb-7">
                                <div class="rounded-circle overflow-hidden me-6">
                                    <img src="{{ asset('dist/images/profile/user-1.jpg') }}" alt="" width="40"
                                        height="40">
                                </div>
                                <h5 class="fw-semibold mb-0 fs-5">Selamat Datang {{ auth()->user()->name }}</h5>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="welcome-bg-img mb-n7 text-end">
                                <img src="{{ asset('dist/images/backgrounds/welcome-bg2.png') }}" alt=""
                                    class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div id="projectsChart"></div>
        </div>
        <div class="col-md-4">
            <div class="d-flex justify-content-end gap-3 mb-5">
                <form action="{{ route('attendance.mark') }}" method="post">
                    @csrf
                    <button button type="submit" class="btn btn-success">Absen</button>
                </form>
            </div>
            <div id="attendanceChart"></div>
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

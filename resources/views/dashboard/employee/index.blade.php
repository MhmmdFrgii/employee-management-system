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
                                    <img src="
                                        @if (Auth::user()->employee_detail->gender == 'male') {{ asset('dist/images/profile/user-1.jpg') }}

                                        @elseif (Auth::user()->employee_detail->gender == 'female')
                                            {{ asset('dist/images/profile/user-2.jpg') }}
                                        @else
                                            {{ asset('assets/images/no-profile.jpeg') }} @endif"
                                        alt="" width="40" height="40">
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
                <form action="{{ route('attendance.mark', ['route' => 'employee.dashboard']) }}" method="post">
                    @csrf
                    <button button type="submit" class="btn btn-success">Absen</button>
                </form>
            </div>
            <div id="chart"></div>
        </div>
    </div>

    @if (isset($projectCounts))
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            var projectOptions = {
                series: [{
                    name: 'Active Projects',
                    data: @json($projectCounts)
                }],
                chart: {
                    type: 'line',
                    height: 350
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: @json($months),
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Projek Selesai'
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " projects";
                        }
                    }
                },
                markers: {
                    size: 4,
                },
                fill: {
                    opacity: 1
                }
            };

            var projectChart = new ApexCharts(document.querySelector("#projectsChart"), projectOptions);
            projectChart.render();


            var options = {
                series: @json($attendanceCounts),
                chart: {
                    width: 350,
                    type: 'donut',
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

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        </script>
    @else
        <p>Data tidak ditemukan.</p>
    @endif
@endsection

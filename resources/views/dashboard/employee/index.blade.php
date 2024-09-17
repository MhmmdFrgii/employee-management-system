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
            <div class="card">
                <div class="card-header">
                    <h5>Project</h5>
                </div>
                <div class="card-body">
                    <div id="projectsChart"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body d-flex justify-content-end">
                    <form action="{{ route('attendance.mark', ['route' => 'employee.dashboard']) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-success">Absen</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Absen</h5>
                </div>
                <div class="card-body">
                    <div id="attendanceChart"></div>
                </div>
            </div>
        </div>
    </div>


    @if (isset($projectCounts))
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            var projectOptions = {
                series: [{
                    name: 'Proyek Aktif',
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
                            return val + " proyek";
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
                labels: ['Hadir', 'Tidak Hadir', 'Terlambat', 'Alfa'],
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

            var attendanceChart = new ApexCharts(document.querySelector("#attendanceChart"), options);
            attendanceChart.render();
        </script>
    @else
        <p>Data tidak ditemukan.</p>
    @endif
@endsection

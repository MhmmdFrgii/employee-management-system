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
                                    <img src="{{ asset('dist/images/profile/user-1.jpg') }}" alt="" width="40" height="40">
                                </div>
                                <h5 class="fw-semibold mb-0 fs-5">Selamat Datang {{ auth()->user()->name }}</h5>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="welcome-bg-img mb-n7 text-end">
                                <img src="{{ asset('dist/images/backgrounds/welcome-bg2.png') }}" alt="" class="img-fluid">
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
    </div>

@if(isset($activeCounts) && isset($completedCounts))
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var options = {
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
                  formatter: function (val) {
                      return val + " projects";
                  }
              }
          }
        };

        var chart = new ApexCharts(document.querySelector("#projectsChart"), options);
        chart.render();
    </script>
@else
    <p>Data tidak ditemukan.</p>
@endif
@endsection

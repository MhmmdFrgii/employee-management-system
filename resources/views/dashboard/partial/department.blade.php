@if (isset($departments))
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Opsi konfigurasi pie chart
        var pieOptions = {
            series: @json($department_data),
            labels: @json($departments),
            chart: {
                type: 'donut',
                height: 350
            },
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: true,
                formatter: function(val, opts) {
                    // Display the number of people from the series data
                        // return opts.w.config.series[opts.seriesIndex] + " orang";
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " orang";
                    }
                }
            }
        };

        // Membuat dan merender pie chart
        var pieChart = new ApexCharts(document.querySelector("#pieChart"), pieOptions);
        pieChart.render();
    </script>
@else
    <p>Data tidak ditemukan.</p>
@endif

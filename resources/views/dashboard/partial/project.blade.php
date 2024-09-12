@if (isset($project_data))
<script>
    var projectData = @json($project_data);

    // Chart untuk Jumlah Proyek Selesai
    var projectCompletedOptions = {
        series: [{
            name: 'Jumlah Proyek Selesai',
            data: projectData.map(function(data) {
                return {
                    x: data[0],
                    y: data[1]
                };
            })
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
            title: {
                text: 'Bulan'
            }
        },
        yaxis: {
            title: {
                // text: 'Jumlah Proyek Selesai'
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
            size: 4
        },
        fill: {
            opacity: 1
        }
    };

    var projectCompletedChart = new ApexCharts(document.querySelector("#projectsCompletedChart"),
        projectCompletedOptions);
    projectCompletedChart.render();
</script>
@else
    <p>Data tidak ditemukan.</p>
@endif

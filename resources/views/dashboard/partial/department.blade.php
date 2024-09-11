@if (isset($departments))
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Data untuk pie chart
    var pieData = [1, 5, 5,
        2
    ] // Format data: [{ name: 'Kategori A', value: 20 }, { name: 'Kategori B', value: 30 }, ...]

    // Opsi konfigurasi pie chart
    var pieOptions = {
        // series: pieData.map(function(item) {
        //     return item.value;
        // }),
        // labels: pieData.map(function(item) {
        //     return item.name;
        // }),
        series: @json($department_data),
        labels: @json($departments),
        chart: {
            type: 'pie',
            height: 350
        },
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true
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

<script>
    // Bar chart untuk proyek per departemen
var completedProjectsData = @json($completedProjects);
var departmentNames = @json($departmentNames);
var months = @json($months);

var seriesData = [];
departmentNames.forEach(function(department) {
    var departmentData = months.map(function(month) {
        return completedProjectsData[department][month] || 0;
    });
    seriesData.push({
        name: department,
        data: departmentData
    });
});

var projectsBarOptions = {
    series: seriesData,
    chart: {
        type: 'bar',
        height: 350
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
        }
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
        categories: months,
        title: {
            text: 'Bulan'
        }
    },
    yaxis: {
        title: {
            // text: 'Jumlah Proyek Selesai'
        }
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function(val) {
                return val + " proyek";
            }
        }
    }
};

var projectsBarChart = new ApexCharts(document.querySelector("#projectsBarChart"), projectsBarOptions);
projectsBarChart.render();
    </script>

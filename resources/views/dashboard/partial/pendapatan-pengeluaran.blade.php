    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.onload = function() {
            var transactionChart = {
                series: [{
                        name: "Eanings this month",
                        data: @json($incomes),
                    },
                    {
                        name: "Expense this month",
                        data: @json($expenses),
                    },
                ],
                chart: {
                    toolbar: {
                        show: false,
                    },
                    type: "bar",
                    fontFamily: "Plus Jakarta Sans', sans-serif",
                    foreColor: "#adb0bb",
                    height: 320,
                    stacked: true,
                },
                colors: ["var(--bs-primary)", "var(--bs-secondary)"],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        barHeight: "60%",
                        columnWidth: "20%",
                        borderRadius: [6],
                        borderRadiusApplication: 'end',
                        borderRadiusWhenStacked: 'all'
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                legend: {
                    show: false,
                },
                grid: {
                    borderColor: "rgba(0,0,0,0.1)",
                    strokeDashArray: 3,
                    xaxis: {
                        lines: {
                            show: false,
                        },
                    },
                },
                yaxis: {
                    min: -5,
                    max: 5,
                    title: {
                        // text: 'Age',
                    },
                },
                xaxis: {
                    axisBorder: {
                        show: false,
                    },
                    categories: @json($monthtransa),

                },
                yaxis: {
                    tickAmount: 4,
                },
                tooltip: {
                    theme: "dark",
                },
            };

            var transactionChart = new ApexCharts(document.querySelector("#transactionChart"), transactionChart);
            transactionChart.render();
        };
    </script>

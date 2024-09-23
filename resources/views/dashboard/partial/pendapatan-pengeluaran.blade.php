@if (isset($project_data))
    <script>
        var incomes_data = @json($incomes);
        var expenses_data = @json($expenses);

        console.log(incomes_data);
        console.log(expenses_data);

        // Chart untuk Incomes dan Expenses
        var transactionData = {
            series: [{
                name: 'Pendapatan',
                data: incomes_data
            }, {
                name: 'Pengeluaran',
                data: expenses_data
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
                categories: @json($monthtransa), // Nama bulan yang benar
                title: {
                    text: 'Bulan'
                }
            },
            yaxis: {
                title: {
                    text: 'Jumlah (Rp)'
                },
                labels: {
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
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

        var transactionChart = new ApexCharts(document.querySelector("#transactionChart"), transactionData);
        transactionChart.render();
    </script>
@else
    <p>Data tidak ditemukan.</p>
@endif

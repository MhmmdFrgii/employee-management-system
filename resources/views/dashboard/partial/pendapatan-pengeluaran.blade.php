    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.onload = function() {
            var ctx = document.getElementById('transactionChart').getContext('2d');

            // Gradient for Pendapatan (Income)
            var gradientIncome = ctx.createLinearGradient(0, 0, 0, 400);
            gradientIncome.addColorStop(0, 'rgba(0, 115, 255, 1)');
            gradientIncome.addColorStop(1, 'rgba(0, 162, 255, 0.6)');

            // Gradient for Pengeluaran (Expenses)
            var gradientExpense = ctx.createLinearGradient(0, 0, 0, 400);
            gradientExpense.addColorStop(0, 'rgba(255, 99, 132, 1)'); // Red gradient
            gradientExpense.addColorStop(1, 'rgba(255, 159, 64, 0.6)'); // Lighter red-orange gradient

            var transactionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($monthtransa),
                    datasets: [
                    {
                        label: 'Pendapatan', // Income
                        data: @json($incomes),
                        backgroundColor: gradientIncome,
                        borderColor: 'rgba(0, 115, 255, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pengeluaran', // Expenses
                        data: @json($expenses),
                        backgroundColor: gradientExpense,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp' + value.toLocaleString(); // Add 'Rp' prefix and format the numbers
                                }
                            }
                        }
                    },
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return 'Rp' + tooltipItem.raw.toLocaleString(); // Format the tooltip numbers with 'Rp'
                                }
                            }
                        }
                    }
                }
            });
        };
    </script>

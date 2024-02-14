<script>
    $(document).ready(function() {
        var itx = document.getElementById("dIncomes_area");
        var myChart = new Chart(itx, {
            type: 'doughnut',
            data: {
                labels: {!! $dIncomesDataName !!},
                datasets: [{
                    data: {!! $dIncomesDataValue !!},
                    backgroundColor: {!! $dIncomesDataColor !!},
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'right',
                },
            }
        });

        var etx = document.getElementById("dexpense_area");
        var myChart = new Chart(etx, {
            type: 'doughnut',
            data: {
                labels: {!! $dExpenseDataName !!},
                datasets: [{
                    data: {!! $dExpenseDataValue !!},
                    backgroundColor: {!! $dExpenseDataColor !!},
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'right',
                },
            }
        });

        var ctx = document.getElementById("combine-chart")
        var data = {
            labels: {!! $myMonth !!},
            datasets: [
                    {
                        fill: false,
                        label: "Profit",
                        lineTension: 0.3,
                        borderColor: "#6da252",
                        backgroundColor: "#6da252",
                        data: {!! $myProfitsGraph !!},
                    },
                    {
                        fill: false,
                        label: "Income",
                        lineTension: 0.3,
                        borderColor: "#00c0ef",
                        backgroundColor: "#00c0ef",
                        data: {!! $myIncomesGraph !!},
                    },
                    {
                        fill: false,
                        label: "Expense",
                        lineTension: 0.3,
                        borderColor: "#F56954",
                        backgroundColor: "#F56954",
                        data: {!! $myExpenseGraph !!},
                    },
            ]
        };

        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'top'
                },
            }
        });

    });
</script>

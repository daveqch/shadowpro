<script type="text/javascript">
    var ctx = document.getElementById("myExpenseChart")
    var data = {
        labels: {!! $myMonth !!},
        datasets: [
            {
                fill: true,
                label: "Expense",
                lineTension: 0.3, borderColor: "#F56954",
                backgroundColor: "#F56954",
                data: {!! $myExpensesGraph !!},
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
</script>

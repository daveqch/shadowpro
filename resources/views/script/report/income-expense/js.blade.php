<script type="text/javascript">
    var ctx = document.getElementById("income_expense_chart")
    var data = {
        labels: {!! $myMonth !!},
        datasets: [
            {
                fill: true,
                label: "Profit",
                lineTension: 0.3, borderColor: "#6da252",
                backgroundColor: "#6da252",
                data: {!! $myGraph !!},
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

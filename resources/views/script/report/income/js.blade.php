<script type="text/javascript">
    var ctx = document.getElementById("PQFfRtWHpU")
    var data = {
        labels: {!! $myMonth !!},
        datasets: [
            {
                fill: true,
                label: "Income",
                lineTension: 0.3, borderColor: "#00c0ef",
                backgroundColor: "#00c0ef",
                data: {!! $myIncomesGraph !!},
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

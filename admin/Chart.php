<!-- Chart -->
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/Chart.min.js"></script>


<!-- ยอดขายรายวัน เลือกช่วงได้ -->
<script>
    $(document).ready(function() {
        updateSalesChart();
    });

    function updateSalesChart() {
        var salesDates = <?php echo json_encode($sales_dates); ?>;
        var dailySales = <?php echo json_encode($daily_sales); ?>;
        var maxSales = <?php echo json_encode($max_sales); ?>;
        var maxSalesDate = <?php echo json_encode($max_sales_date); ?>;

        // Define an array of colors to use for the bars
        var colorPalette = [
            '#FFE082', // Light Yellow
            '#A5D6A7', // Light Green
            '#FFAB91', // Light Orange
            '#9FA8DA', // Light Blue
            '#F48FB1', // Light Pink
            '#80DEEA', // Light Cyan
            '#CE93D8', // Light Purple
            '#FFCC80', // Light Orange
            '#C5E1A5', // Light Green
            '#80CBC4', // Light Teal
            '#FFF59D', // Light Yellow
            '#E6EE9C' // Light Lime
        ];


        // Assign colors to each bar, using a special color for the max sales date
        var backgroundColors = salesDates.map(function(date, index) {
            return date === maxSalesDate ? '#eda500' : colorPalette[index % colorPalette.length];
        });

        var chartData = {
            labels: salesDates,
            datasets: [{
                label: 'ยอดขายรายวัน',
                backgroundColor: backgroundColors,
                borderColor: '#fff',
                data: dailySales
            }]
        };

        var ctx = document.getElementById("salesChart").getContext("2d");
        new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += new Intl.NumberFormat().format(context.raw);
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
</script>


<!-- ยอดขายรายเดือน -->
<script>
    $(document).ready(function() {
        showGraph1();
    });

    function showGraph1() {
        $.post("Chart_month.php", function(data) {
            console.log(data);
            var name = [];
            var marks = [];

            for (var i in data) {
                name.push(data[i].reg_month);
                marks.push(data[i].sumTotal);
            }

            var chartdata = {
                labels: name,
                datasets: [{
                    label: 'ยอดขายในแต่ละเดือน',
                    backgroundColor: [
                        '#FFECB3', // Light Yellow
                        '#C8E6C9', // Light Green
                        '#FFCCBC', // Light Orange
                        '#C5CAE9', // Light Blue
                        '#F8BBD0', // Light Pink
                        '#B2EBF2', // Light Cyan
                        '#E1BEE7', // Light Purple
                        '#FFE0B2', // Light Orange
                        '#DCEDC8', // Light Green
                        '#B2DFDB', // Light Teal
                        '#FFF9C4', // Light Yellow
                        '#F0F4C3' // Light Lime
                    ],
                    borderColor: '#fff',
                    hoverBackgroundColor: [
                        '#FFD740', // Soft Yellow
                        '#A5D6A7', // Soft Green
                        '#FFAB91', // Soft Orange
                        '#9FA8DA', // Soft Blue
                        '#F48FB1', // Soft Pink
                        '#80DEEA', // Soft Cyan
                        '#CE93D8', // Soft Purple
                        '#FFCC80', // Soft Orange
                        '#C5E1A5', // Soft Green
                        '#80CBC4', // Soft Teal
                        '#FFF59D', // Soft Yellow
                        '#E6EE9C' // Soft Lime
                    ],
                    hoverBorderColor: '#e5e5e5',
                    data: marks
                }]
            };

            var graphTarget = $("#data_sale");

            var barGraph = new Chart(graphTarget, {
                type: 'pie',
                data: chartdata
            });
        });
    }
</script>
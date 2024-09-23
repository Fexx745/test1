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
                name.push(data[i].reg_month); // ชื่อเดือน
                marks.push(data[i].sumTotal);
            }

            var chartdata = {
                labels: name,
                datasets: [{
                    label: 'ยอดขายในแต่ละเดือน',
                    backgroundColor: [
                        '#FFB74D', // ส้มอ่อน
                        '#64B5F6', // น้ำเงินอ่อน
                        '#81C784', // เขียวอ่อน
                        '#FFD54F', // เหลืองอ่อน
                        '#F06292', // ชมพู
                        '#64B5F6', // น้ำเงินอ่อน
                        '#BA68C8', // ม่วงอ่อน
                        '#4DB6AC', // เขียวฟ้าอ่อน
                        '#FF8A65', // ส้ม
                        '#A1887F', // น้ำตาลอ่อน
                        '#7986CB', // น้ำเงินเข้ม
                        '#FFF59D' // เหลืองอ่อน
                    ],
                    borderColor: '#fff',
                    hoverBackgroundColor: [
                        '#FFA726', // ส้มเข้ม
                        '#42A5F5', // น้ำเงินเข้ม
                        '#66BB6A', // เขียวเข้ม
                        '#FFCA28', // เหลืองเข้ม
                        '#EC407A', // ชมพูเข้ม
                        '#42A5F5', // น้ำเงินเข้ม
                        '#AB47BC', // ม่วงเข้ม
                        '#26A69A', // เขียวฟ้าเข้ม
                        '#FF7043', // ส้มเข้ม
                        '#8D6E63', // น้ำตาลเข้ม
                        '#5C6BC0', // น้ำเงินเข้ม
                        '#FBC02D' // เหลืองเข้ม
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
<?php
session_start();
include('condb.php');

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
} elseif ($_SESSION['status'] !== '1') {
    header('Location: ../login.php');
    exit();
}

//รายการสั่งซื้อสินค้าที่ยังไม่ชำระเงิน
$sql = "SELECT COUNT(id) as customer FROM tb_member";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

// ยอดขายวันนี้
$sql2 = "
    SELECT SUM(total_price) as total_price 
    FROM tb_order 
    WHERE DATE(reg) = CURDATE()
";
$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_array($result2);
$total_sales_today = isset($row2['total_price']) ? $row2['total_price'] : 0;


//สั่งซื้อวันนี้
$sql3 = "
    SELECT COUNT(orderID) as order_today 
    FROM tb_order 
    WHERE DATE(reg) = CURDATE()
";
$result3 = mysqli_query($conn, $sql3);
$row3 = mysqli_fetch_array($result3);
$order_today = isset($row3['order_today']) ? $row3['order_today'] : 0;


//ยอดขายเดือนนี้
$sql4 = "
    SELECT SUM(total_price) as total_sales_this_month 
    FROM tb_order 
    WHERE MONTH(reg) = MONTH(CURDATE()) 
    AND YEAR(reg) = YEAR(CURDATE())
";
$result4 = mysqli_query($conn, $sql4);
$row4 = mysqli_fetch_array($result4);
$total_sales_this_month = isset($row4['total_sales_this_month']) ? $row4['total_sales_this_month'] : 0;



// Sales for the last 30 days
$sql7 = "
    SELECT DATE(reg) as sale_date, SUM(total_price) as daily_sales 
    FROM tb_order 
    WHERE reg >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(reg)
    ORDER BY DATE(reg)
";
$result7 = mysqli_query($conn, $sql7);
// Initialize arrays to store data for the chart
$sales_dates = [];
$daily_sales = [];

while ($row7 = mysqli_fetch_array($result7)) {
    $sales_dates[] = $row7['sale_date'];
    $daily_sales[] = $row7['daily_sales'];
}

if (!empty($daily_sales)) {
    // Find the day with the highest sales
    $max_sales = max($daily_sales);
    $max_sales_date = $sales_dates[array_search($max_sales, $daily_sales)];
} else {
    // Handle the case where there are no sales
    $max_sales = 0;
    $max_sales_date = 'No sales data available';
}


// Fetch best-selling products of the month
$sql8 = "
SELECT 
    p.p_name AS product_name, 
    SUM(od.orderQty) AS total_quantity 
FROM tb_order_detail od
JOIN product p ON od.p_id = p.p_id
JOIN tb_order ord ON od.orderID = ord.orderID
WHERE MONTH(ord.reg) = MONTH(CURDATE())
AND YEAR(ord.reg) = YEAR(CURDATE())
GROUP BY p.p_name
ORDER BY total_quantity DESC
LIMIT 5;
";

$result8 = mysqli_query($conn, $sql8);

if (!$result8) {
    die('Error executing query: ' . mysqli_error($conn));
}

$best_selling_products = [];
$product_names = [];
$product_quantities = [];

while ($row8 = mysqli_fetch_assoc($result8)) {
    $product_names[] = $row8['product_name'];
    $product_quantities[] = $row8['total_quantity'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/index-admin.css" rel="stylesheet" />
    <!-- Font -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="assets/dist/sweetalert2.all.min.js"></script>
</head>

<body class="sb-nav-fixed">

    <?php include('menu.php') ?>
    <div id="layoutSidenav_content" style="background: #fff;">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4 dashboard-1">
                            <div class="card-body">ลูกค้า<h5>
                                    <?= $row['customer'] ?> คน
                                </h5>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small><a href="member_List.php">
                                            <i class='bx bx-group'></i>
                                            จำนวนผู้ใช้งานบนเว็บไซต์</a></small></div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4 dashboard-2">
                            <div class="card-body">ยอดขายวันนี้<h5>
                                    <?= number_format($total_sales_today, 2) ?> บาท
                                </h5>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small><a href="#">
                                            <i class='bx bx-chart'></i>
                                            ยอดขายวันนี้</a></small></div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4 dashboard-3">
                            <div class="card-body">ยอดการสั่งซื้อวันนี้<h5>
                                    <?= ($order_today) ?> รายการ</div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small><a href="#"">
                                            <i class='bx bx-cart'></i>
                                            จำนวนการสั่งซื้อสินค้าวันนี้</a></small></div>
                                <div class=" small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4 dashboard-4">
                            <div class="card-body">ยอดขายเดือนนี้<h5>
                                    <?= number_format($total_sales_this_month, 2); ?> บาท
                                </h5>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small><a href="#">
                                            <i class='bx bx-stats'></i>
                                            ยอดขายเดือนนี้</a></small>
                                </div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-xl-6">
                        <div class="card-header">
                            <div class="card-head">
                                <div class="card-icons"><i class='bx bx-menu'></i></div>
                                <div class="card-details"><b>จัดการข้อมูล (เมนูด่วน)</b></div>
                            </div>
                        </div>
                        <div class="card-body-details">
                            <div class="1"><a href="product_List.php"><i class='bx bx-basket'></i>จัดการสินค้า</a>
                            </div>
                            <div class="2"><a href="producttype_List.php"><i
                                        class='bx bx-category-alt'></i>จัดการประเภทสินค้า</a></div>
                            <div class="3"><a href="unit_add.php"><i class='bx bxl-unity'></i>จัดการหน่วยสินค้า</a></div>
                            <div class="4"><a href="brand_add.php"><i class='bx bx-layout'></i>จัดการยี่ห้อสินค้า</a>
                            </div>
                            <div class="5"><a href="member_List.php"><i
                                        class='bx bx-user-pin'></i>จัดการข้อมูลลูกค้า</a></div>
                            <div class="6"><a href="shipping_add.php"><i class='bx bxs-truck'></i>จัดการข้อมูลขนส่ง</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card-header">
                            <div class="card-head-2">
                                <div class="card-icons-2"><i class='bx bxs-cog'></i></div>
                                <div class="card-details-2"><b>รายการตั้งค่าที่สำคัญ</b></div>
                            </div>
                        </div>
                        <div class="card-body-details">
                            <div class="1"><a href="report_order.php"><i
                                        class='bx bx-notepad'></i>ตรวจสอบการสั่งซื้อ</a></div>
                            <div class="2"><a href="summary.php"><i class='bx bxs-calculator'></i>สร้างรายงานสรุปยอดขาย</a></div>
                            <div class="3"><a href="banner_add.php"><i class='bx bxs-image'></i>แก้ไขรูปภาพแบนเนอร์</a>
                            </div>
                            <div class="5"><a href="Line_notify.php"><i class='bx bxs-bell-ring'></i>Line Notify</a></div>
                            <div class="5"><a href="#"><i class='bx bx-cog'></i><b style="color: red;">Coming
                                        Soon...</b></a></div>
                            <div class="6"><a href="#"><i class='bx bx-cog'></i><b style="color: red;">Coming
                                        Soon...</b></a></div>
                        </div>
                    </div>
                </div>


                <div class="row mt-5">

                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-line me-1"></i>
                                <b>รายงานยอดขายในแต่ละเดือน </b><span style="font-size: 14px;">( ฿ )</span>
                            </div>
                            <div class="card-body"><canvas id="data_sale" width="100%" height="40"></canvas></div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-shopping-cart me-1"></i>

                                <b>10 อันดับสินค้าขายดีที่สุดเดือนนี้ </b><span style="font-size: 14px;">( ชิ้น )</span>
                            </div>
                            <div class="card-body">
                                <canvas id="monthpd" width="100%" height="40"></canvas>
                            </div>
                        </div>
                    </div>
    
                    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const ctx = document.getElementById('monthpd').getContext('2d');

                            // Pass PHP data to JavaScript
                            const productNames = <?php echo json_encode($product_names); ?>;
                            const productQuantities = <?php echo json_encode($product_quantities); ?>;

                            console.log('Product Names:', productNames); // For debugging
                            console.log('Product Quantities:', productQuantities); // For debugging

                            new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: productNames,
                                    datasets: [{
                                        label: 'ยอดขายเดือนนี้',
                                        data: productQuantities,
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
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        x: {
                                            beginAtZero: true
                                        },
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        });
                    </script>

                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-calendar me-1"></i>
                                <strong>ยอดขายรายวันเลือกช่วงวันที่ </strong><span style="font-size: 14px;">( ฿ )</span>
                            </div>
                            <div class="card-body">
                                <form id="dateRangeForm" method="GET" action="">
                                    <div class="row" style="margin: 0 0 30px 0">
                                        <div class="col-md-5">
                                            <label for="startDate" style="color: #6c757d; font-size: 13px;">เลือกวันที่เริ่มต้น</label>
                                            <input type="date" id="startDate" name="start_date" class="form-control" required>
                                        </div>
                                        <div class="col-md-5">
                                            <label for="endDate" style="color: #6c757d; font-size: 13px;">เลือกวันที่สิ้นสุด</label>
                                            <input type="date" id="endDate" name="end_date" class="form-control" required>
                                        </div>
                                        <div class="col-md-2 align-self-end">
                                            <button type="submit" class="btn btn-primary">แสดง</button>
                                        </div>
                                    </div>
                                </form>
                                <canvas id="salesChart" width="100%" height="40"></canvas>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
                        $startDate = $_GET['start_date'];
                        $endDate = $_GET['end_date'];

                        $sql = "
        SELECT DATE(reg) as sale_date, SUM(total_price) as daily_sales 
        FROM tb_order 
        WHERE DATE(reg) BETWEEN '$startDate' AND '$endDate'
        GROUP BY DATE(reg)
        ORDER BY DATE(reg)
    ";
                        $result = mysqli_query($conn, $sql);

                        $sales_dates = [];
                        $daily_sales = [];

                        while ($row = mysqli_fetch_assoc($result)) {
                            $sales_dates[] = $row['sale_date'];
                            $daily_sales[] = $row['daily_sales'];
                        }

                        if (!empty($daily_sales)) {
                            $max_sales = max($daily_sales);
                            $max_sales_date = $sales_dates[array_search($max_sales, $daily_sales)];
                        } else {
                            $max_sales = 0;
                            $max_sales_date = null;
                        }
                    }

                    ?>


                </div>
        </main>

        <?php include('footer.php') ?>
    </div>
    </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>


<!-- Chart -->
<?php include('Chart.php'); ?>

<!-- Your PHP code -->
<?php
if (isset($_SESSION['delete_product'])) {
?>
    <script>
        Swal.fire({
            icon: "success",
            title: "ลบสำเร็จ!",
            text: "Successfully",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php
    unset($_SESSION['delete_product']);
}
?>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "คุณต้องการลบหรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `prd_delete.php?id=${id}`;
            }
        });
    }
</script>

<?php
if (isset($_SESSION['edit_product'])) {
?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "แก้ไขสำเร็จ!",
            text: "Successfully",
            showConfirmButton: false,
            timer: 1500
        }).then(function() {
            window.location.href = 'index.php';
        });
    </script>

<?php
    unset($_SESSION['edit_product']);
}
?>

<?php
if (isset($_SESSION['editprice'])) {
?>
    <script>
        Swal.fire({
            icon: "success",
            title: "อัพเดทราคาสำเร็จ!",
            text: "Successfully",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php
    unset($_SESSION['editprice']);
}
?>

<?php
if (isset($_SESSION['addstock'])) {
?>
    <script>
        Swal.fire({
            icon: "success",
            title: "เพิ่มสต็อกสำเร็จ!",
            text: "Successfully",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php
    unset($_SESSION['addstock']);
}
?>
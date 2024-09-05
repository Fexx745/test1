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

// Retrieve form values
$dt1 = isset($_POST['dt1']) ? $_POST['dt1'] : null;
$dt2 = isset($_POST['dt2']) ? $_POST['dt2'] : null;

if (!empty($dt1) && !empty($dt2)) {
    $sql = "SELECT p.p_name, u.unit_name, p.image, SUM(od.orderQty) AS total_quantity, SUM(od.Total) AS total_price
            FROM tb_order o
            INNER JOIN tb_order_detail od ON o.orderID = od.orderID
            INNER JOIN product p ON od.p_id = p.p_id
            INNER JOIN unit_type u ON p.unit_id = u.unit_id
            WHERE o.reg BETWEEN '$dt1' AND '$dt2'
            GROUP BY p.p_name, u.unit_name
            ORDER BY total_price DESC"; // เรียงลำดับตามยอดขายสุทธิมากที่สุด

    $result = mysqli_query($conn, $sql);
}
//รายการสั่งซื้อสินค้าที่ยังไม่ชำระเงิน
$sql1 = "SELECT COUNT(id) as customer FROM tb_member";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_array($result1);

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
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="assets/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
                                    <?= $row1['customer'] ?> คน
                                </h5>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small><a href="show_account.php">
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
                                <div><small><a href="#">
                                            <i class='bx bx-cart'></i>
                                            จำนวนการสั่งซื้อสินค้าวันนี้</a></small></div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
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

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bx bx-line-chart me-1"></i>
                        สรุปยอดขายรายเดือน
                    </div>
                    <div class="card-body">
                        <form name="form2" action="sales_summary.php" method="POST">
                            <div class="row">
                                <div class="col-sm-3">
                                    <input type="text" name="dt1" id="datepicker1" class="form-control"
                                        placeholder="ค้นหาตั้งแต่วันที่" readonly>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="dt2" id="datepicker2" class="form-control"
                                        placeholder="ถึงวันที่ ..." readonly>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-block"
                                        style="background: linear-gradient(195deg, #0a58ca 0%, #0d6efd 100%); color: #fff;">
                                        <i class='bx bx-search-alt'></i> ค้นหา
                                    </button>
                                </div>
                            </div>
                        </form>

                        <?php if (!empty($dt1) && !empty($dt2)) { ?>
                            <div class="card-body">
                                <?php if (mysqli_num_rows($result) > 0) {
                                    $grand_total = 0; // Total sum variable
                                    ?>
                                    <div class="mb-3">
                                        <h4>สรุปยอดขายระหว่างวันที่ <?php echo $dt1; ?> ถึงวันที่ <?php echo $dt2; ?></h4>
                                    </div>
                                    <table id="datatablesSimple" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ชื่อสินค้า</th>
                                                <th>จำนวนที่ขายได้</th>
                                                <th>ยอดขายสุทธิ</th>
                                            </tr>
                                        </thead>
                                        <?php while ($row = mysqli_fetch_assoc($result)) {
                                            $grand_total += $row['total_price']; // Calculate total sum
                                            ?>
                                            <tr>
                                                <td><img src="../assets/images/product/<?= htmlspecialchars($row['image']); ?>"
                                                        alt="Product Image" style="width: 50px; height: 70px; object-fit: cover;">
                                                </td>
                                                <td><?php echo $row['p_name']; ?></td>
                                                <td><?php echo $row['total_quantity'] . ' ' . $row['unit_name']; ?></td>
                                                <td><?php echo number_format($row['total_price'], 2); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                    <div class="alert mt-4"
                                        style="background: linear-gradient(195deg, #f7fffe 0%, #f7fffe 100%); color: #333; border: 1px solid #00c300; outline: none;">
                                        <p>ตั้งแต่วันที่ <?php echo $dt1; ?> ถึงวันที่ <?php echo $dt2; ?> ยอดรวมสุทธิ</p>
                                        <img src="../assets/images/other/pngegg.png" alt="pngegg"
                                            style="height: 80px; margin-right: 10px;">
                                        <strong style="font-size: 1.5rem;"><?php echo number_format($grand_total, 2); ?>
                                            บาท</strong>
                                    </div>
                                <?php } else { ?>
                                    <div class="mt-3" class="mb-2">
                                        <p>ไม่พบข้อมูลการขายในช่วงวันที่ที่กำหนด</p>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="mt-3" class="mb-2">
                                <p>กรุณาระบุช่วงวันที่ที่ต้องการค้นหา</p>
                            </div>
                        <?php } ?>
                        <div class="mt-3" class="mb-2">
                            <a href="summary.php" class="btn btn-dark">ย้อนกลับ</a>
                        </div>
                    </div>
                </div>

                <script>
                    $(function () {
                        $("#datepicker1").datepicker({
                            dateFormat: 'yy-mm-dd'
                        });
                        $("#datepicker2").datepicker({
                            dateFormat: 'yy-mm-dd'
                        });
                    });
                </script>
            </div>
        </main>
    </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
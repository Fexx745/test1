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

// รายการสั่งซื้อสินค้าที่ยังไม่ชำระเงิน
$sql = "SELECT COUNT(id) as customer FROM tb_member";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

// รายการสั่งซื้อที่ถูกยกเลิก
$sql2 = "SELECT SUM(total_price) as total_price FROM tb_order";
$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_array($result2);

// รายการสั่งซื้อสินค้าที่ชำระเงินแล้ว
$sql3 = "SELECT COUNT(orderID) as order_yes FROM tb_order";
$result3 = mysqli_query($conn, $sql3);
$row3 = mysqli_fetch_array($result3);

// จำนวนสินค้าทั้งหมด
$sql4 = "SELECT COUNT(p_id) as all_pd FROM product";
$result4 = mysqli_query($conn, $sql4);
$row4 = mysqli_fetch_array($result4);
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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');

        * {
            font-size: 16px;
            font-family: 'K2D', sans-serif;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* ปรับตามความต้องการ */
        }
    </style>
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
                <ol class="breadcrumb mb=4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4" style="background: #dc3545;">
                            <div class="card-body">ลูกค้า
                                <h4>
                                    <?= $row['customer'] ?> คน
                                </h4>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small style="font-size: 12px;"><a href="#" style="text-decoration: none; color: white;">
                                            <i class='bx bx-user-circle'></i> จำนวนผู้ใช้งานบนเว็บไซต์</a></small></div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb=4" style="background: #198754;">
                            <div class="card-body">ยอดขายทั้งหมด
                                <h4>
                                    <?= number_format($row2['total_price'], 2, '.', ',') ?> บาท
                                </h4>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small style="font-size: 12px;"><a href="#" style="text-decoration: none; color: white;">
                                            <i class='bx bx-money'></i> ยอดรวมรายได้ทั้งหมด</a></small></div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4" style="background: #6f42c1;">
                            <div class="card-body">สั่งซื้อทั้งหมด
                                <h4>
                                    <?= $row3['order_yes'] ?> รายการ
                                </h4>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small style="font-size: 12px;"><a href="#" style="text-decoration: none; color: white;">
                                            <i class='bx bx-list-ul'></i> จำนวนรายการสั่งซื้อทั้งหมด</a></small></div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4" style="background: #0d6efd;">
                            <div class="card-body">จำนวนสินค้าทั้งหมด
                                <h4>
                                    <?= $row4['all_pd'] ?> รายการ
                                </h4>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small style="font-size: 12px;"><a href="#" style="text-decoration: none; color: white;">
                                            <i class='bx bx-box'></i> จำนวนสินค้าทั้งหมด</a></small>
                                </div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- สรุปยอดขายรายเดือน -->
                <div>
                    <form name="form2" action="sales_summary.php" method="POST">
                        <label for="">สรุปยอดขายรายเดือน</label>
                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" name="dt1" id="datepicker1" class="form-control" placeholder="ค้าหาตั้งแต่วันที่" readonly>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="dt2" id="datepicker2" class="form-control" placeholder="ถึงวันที่ ..." readonly>
                            </div>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary"><i class='bx bx-search-alt'></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                <script>
                    $(function() {
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
    </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

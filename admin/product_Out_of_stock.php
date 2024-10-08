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

// ตรวจสอบสินค้าที่หมดสต็อก (เหลือ 0 ชิ้น)
$sql_out_of_stock = "SELECT p_name, amount FROM product WHERE amount = 0";
$result_out_of_stock = mysqli_query($conn, $sql_out_of_stock);
$out_of_stock_count = mysqli_num_rows($result_out_of_stock); // จำนวนสินค้าที่หมดสต็อก

// สินค้าทั้งหมด
$sql4 = "SELECT COUNT(p_id) as all_pd FROM product";
$result4 = mysqli_query($conn, $sql4);
$row4 = mysqli_fetch_array($result4);

// ตรวจสอบจำนวนสินค้าที่น้อยกว่า 10 ชิ้น
$sql_low_stock = "SELECT p_name, amount FROM product WHERE amount < 10";
$result_low_stock = mysqli_query($conn, $sql_low_stock);
$low_stock_count = mysqli_num_rows($result_low_stock); // จำนวนสินค้าที่น้อยกว่า 10 ชิ้น

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
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 mt-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4 dashboard-1">
                            <div class="card-body">
                                สินค้าทั้งหมด<h5><?= $row4['all_pd'] ?> รายการ</h5>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div>
                                    <small>
                                        <a href="product_List.php">
                                            <i class='bx bxs-store'></i>
                                            สินค้าทั้งหมด
                                        </a>
                                    </small>
                                </div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4 dashboard-2">
                            <div class="card-body">
                                สินค้าต่ำกว่า 10 ชิ้น<h5><?= $low_stock_count ?> รายการ</h5>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div>
                                    <small>
                                        <a href="product_Lower10.php">
                                            <i class='bx bx-error'></i>
                                            สินค้าต่ำกว่าเกณฑ์
                                        </a>
                                    </small>
                                </div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4 dashboard-3">
                            <div class="card-body">
                                สินค้าหมดสต็อก<h5><?= $out_of_stock_count ?> รายการ</h5>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div>
                                    <small>
                                        <a href="product_Out_of_stock.php">
                                            <i class='bx bx-error-alt'></i>
                                            สินค้าหมดสต็อก
                                        </a>
                                    </small>
                                </div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4 dashboard-4">
                            <div class="card-body">
                                Comming Soon ... <h5> # </h5>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div>
                                    <small>
                                        <a href="#">
                                            <i class='bx bx-error'></i>
                                            Comming Soon ...
                                        </a>
                                    </small>
                                </div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        จำนวนสินค้าต่ำกว่าเกณฑ์
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>รูปภาพ</th>
                                    <th>ชื่อสินค้า</th>
                                    <th>ประเภท</th>
                                    <th>ยี่ห้อ</th>
                                    <th>ราคา</th>
                                    <th>จำนวน</th>
                                    <th>จัดการสินค้า</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $sql = "SELECT * FROM product as p
                                        INNER JOIN product_type as t ON p.type_id = t.type_id 
                                        INNER JOIN price_history as ph ON p.p_id = ph.p_id
                                        LEFT JOIN unit_type as u ON p.unit_id = u.unit_id
                                        LEFT JOIN brand_type as b ON p.brand_id = b.brand_id
                                        WHERE p.amount = 0
                                        ORDER BY p.p_id";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                ?>
                                    <tr>
                                        <td>
                                            <img style="width: 100px; height: 100px; object-fit: cover;"
                                                src="../assets/images/product/<?= $row['image'] ?>" alt="รูปภาพ"
                                                onerror="this.src='../assets/images/other/no_img.png';">
                                        </td>
                                        <td>
                                            <?= $row['p_name'] ?>
                                        </td>
                                        <td>
                                            <?= $row['type_name'] ?>
                                        </td>
                                        <td>
                                            <?= $row['brand_name'] ?>
                                        </td>
                                        <td>
                                            <?= number_format($row['price'], 2) ?>
                                        </td>
                                        <td>
                                            <?= $row['amount'] ?>
                                            <?= $row['unit_name'] ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-warning" href="product_edit.php?id=<?= $row['p_id'] ?>"><i class='bx bx-pencil'></i></a>
                                            <a class="btn btn-success" href="product_addStock.php?id=<?= $row['p_id'] ?>"><i class='bx bx-plus-circle'></i></a>
                                        </td>
                                    </tr>

                                <?php
                                }
                                mysqli_close($conn);
                                ?>
                            </tbody>

                        </table>
                        <div class="mt-4" class="mb-2">
                            <a href="index.php" class="btn btn-dark">ย้อนกลับ</a>
                        </div>
                    </div> <!-- card-body -->

                </div>
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

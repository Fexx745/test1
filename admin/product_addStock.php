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

$idpd = $_GET['id'];
$sql = "SELECT * FROM product WHERE p_id='$idpd'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

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
                <div class="card mb-4">
                    <div class="card-header">
                        <i class='bx bxs-plus-circle'></i>
                        เพิ่มสต็อกสินค้า
                    </div>
                    <div class="card-body">
                        <div class="alert" style="background: linear-gradient(195deg, #f7fffe 0%, #f7fffe 100%); color: #333; border: 1px solid #30b566; outline: none;">
                            <div class="d-flex"><img src="../assets/images/other/cart.png" style="height: 50px; margin-right: 10px;">
                                <h3 style="font-weight: 1000; margin-top: 10px;">
                                    เพิ่มสต็อกสินค้า
                                </h3>
                            </div>
                        </div>
                        <form method="POST" action="product_update_Stock.php">
                            <div class="mb-3 mt-3">
                                <input type="hidden" class="form-control-plaintext alert alert-warning" name="pid" value="<?= $row['p_id']; ?>" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-cube'></i></span>
                                <input type="text" class="form-control" name="pname" value="<?= $row['p_name']; ?>" readonly>
                            </div>
                            <h5 class="alert alert-danger"><i class='bx bx-error-circle'></i> สินค้าที่เพิ่มเข้าไปจะ + เพิ่มจากที่มีอยู่</h5>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-plus-circle'></i></span>
                                <input type="text" class="form-control" name="amount" placeholder="เพิ่มสต็อกสินค้า" required>
                            </div>
                            <div class="mt-3">
                                <a href="product_List.php" class="btn btn-dark">ย้อนกลับ</a>
                                <button class="btn btn-danger" type="submit">บันทึก</button>
                            </div>
                        </form>
                    </div> <!-- card-body -->

                </div>
            </div>
        </main>

        <?php include('footer.php') ?>
    </div>
    </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
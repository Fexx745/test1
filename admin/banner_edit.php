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

$id_banner = $_GET['id'];
$sql = "SELECT *
        FROM banners
        WHERE banner_id = '$id_banner'";
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
                        <i class='bx bx-image-alt'></i>
                        แก้ไขรูปภาพแบนเนอร์
                    </div>
                    <div class="card-body">
                        <div class="alert" style="background: linear-gradient(195deg, #fff8e4 0%, #fff8e4 100%); color: #333; border: 1px solid #eda500; outline: none;">
                            <div class="d-flex">
                                <img src="../assets/images/other/edit.png" style="height: 50px; margin-right: 5px;">
                                <h3 style="font-weight: 1000; margin-top: 10px;">
                                    แก้ไขรูปภาพแบนเนอร์
                                </h3>
                            </div>
                        </div>
                        <form method="POST" action="banner_update.php" enctype="multipart/form-data">
                            <div class="mb-3 mt-3">
                                <input type="hidden" class="form-control alert alert-success" name="pid"
                                    value="<?= $row['banner_id']; ?>" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-image-alt'></i></span>
                                <input type="text" class="form-control" name="b_name" value="<?= $row['banner_name']; ?>" placeholder="ชื่อรูปภาพ">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-book-reader'></i></span>
                                <input type="text" class="form-control" name="b_detail" value="<?= $row['banner_detail']; ?>" placeholder="รายละเอียดรูปภาพ">
                            </div>
                            <div class="mb-3 mt-3">
                                <p style="margin-bottom: -40px;">รูปภาพปัจจุบัน:</p>
                                <img src="../assets/images/banner/<?= $row['image'] ?>" alt="รูปภาพปัจจุบัน"
                                    style="margin: 0 0 0; width: 1000px; height: 300px; object-fit: contain;">
                                <p>อัพโหลดรูปภาพ:</ห>
                                    <input type="file" class="form-control" name="b_image">
                            </div>
                            <div class="mt-4">
                                <a href="banner_add.php" class="btn btn-dark">ย้อนกลับ</a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
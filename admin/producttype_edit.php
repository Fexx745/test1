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

$type_id = $_GET['id'];
$sql = "SELECT * FROM product_type WHERE type_id='$type_id'";
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

</head>

<body class="sb-nav-fixed">

    <?php include('menu.php') ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 mt-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class='bx bxs-grid'></i>
                        แก้ไขประเภทสินค้า
                    </div>
                    <div class="card-body">
                        <div class="alert" style="background: linear-gradient(195deg, #fff8e4 0%, #fff8e4 100%); color: #333; border: 1px solid #eda500; outline: none;">
                            <div class="d-flex"><img src="../assets/images/other/edit.png" alt="Line Notify Logo" style="height: 50px; margin-right: 10px;">
                                <h3 style="font-weight: 1000; margin-top: 10px;">
                                    แก้ไขประเภทสินค้า
                                </h3>
                            </div>
                            <strong style="color: #ee2c4a;">*รองรับรูปภาพ .png .jpg และ .jpeg</strong>
                        </div>
                        <form method="POST" action="producttype_update.php" enctype="multipart/form-data">
                            <div class="mb-3 mt-3">
                                <input type="hidden" class="form-control alert alert-success" name="typeid"
                                    value="<?= $row['type_id']; ?>" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-grid'></i></span>
                                <input type="text" class="form-control" name="typename"
                                    value="<?= $row['type_name']; ?>">
                            </div>
                            <?php
                            $imageURL = !empty($row['type_image']) ? "../assets/images/type_product/{$row['type_image']}" : "../assets/images/other/no_img.png";
                            ?>
                            <img src="<?= $imageURL ?>"
                                style="margin: 0 0 10px 0; width: 100px; height: 150px; object-fit: contain;">

                            <input type="file" class="form-control" name="image">
                            <div class="mt-4">
                                <a href="producttype_List.php" class="btn btn-dark">ย้อนกลับ</a>
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
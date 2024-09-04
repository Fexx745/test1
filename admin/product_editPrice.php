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

$p_id = mysqli_real_escape_string($conn, $_GET['id']); // ใช้ mysqli_real_escape_string เพื่อป้องกัน SQL Injection
$sql = "SELECT * FROM price_history as ph INNER JOIN product as p ON ph.p_id = p.p_id WHERE ph.p_id='$p_id'";
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

    <!-- Jquery datepicker -->
    <link href="https://cdn.jsdelivr.net/npm/mc-datepicker/dist/mc-calendar.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/mc-datepicker/dist/mc-calendar.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <!-- Font -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>ฃ
    <script src="assets/dist/sweetalert2.all.min.js"></script>

</head>

<body class="sb-nav-fixed">

    <?php include('menu.php') ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 mt-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        แก้ไขราคาสินค้า
                    </div>
                    <div class="card-body">
                        <form method="POST" action="product_update_Price.php">
                            <!-- get p_id ส่งค่าไป -->
                            <input type="text" name="price_id" value="<?= $row['p_id']; ?>" hidden>
                            <div class="input-group mb-3">
                                <img style="width: 100px; height: 150px; object-fit: cover;" src="../assets/images/product/<?= $row['image'] ?>" alt="รูปภาพ" onerror="this.src='../assets/images/other/no_img.png';">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-calendar-minus'></i>&nbsp;วันที่เริ่มใช้ราคา</span>
                                <input type="text" class="form-control" name="fromdate" value="<?= date('d/m/Y', strtotime($row['from_date'])); ?>" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-basket'></i></span>
                                <input type="text" class="form-control" name="pname" value="<?= $row['p_name']; ?>" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-wallet' ></i></span>
                                <input type="text" class="form-control" name="price" value="<?= number_format($row['price'], 2); ?>">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-calendar-x' ></i>&nbsp;วันที่สิ้นสุดการใช้ราคา</span>
                                <input type="text" class="form-control" name="showto_date" value="<?= date('d/m/Y', strtotime($row['to_date'])); ?>" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-calendar-minus'></i>&nbsp;แก้ไขวันที่เริ่มใช้ราคา</span>
                                <input type="date" class="form-control" name="from_date" id="from_date">
                                <span class="input-group-text"><i class='bx bx-calendar-x' ></i>&nbsp;แก้ไขวันที่สิ้นสุดใช้ราคา</span>
                                <input type="date" class="form-control" name="to_date">
                            </div>
                            <div class="mt-4">
                                <a href="product_List.php" class="btn btn-dark">ย้อนกลับ</a>
                                <button class="btn btn-danger" type="submit">ยืนยัน</button>
                            </div>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var today = new Date().toISOString().split('T')[0];
                                document.getElementById('from_date').setAttribute('min', today);
                            });
                        </script>
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

<?php
if (isset($_SESSION['editprice'])) {
?>
    <script>
        Swal.fire({
            icon: "success",
            title: "อัพเดทราคาสำเร็จ",
            footer: '<span style="color: #00c300;">อัพเดทราคาเรียบร้อยแล้ว!</span>',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location.href = "product_List.php";
        });
    </script>
<?php
    unset($_SESSION['editprice']);
}
?>

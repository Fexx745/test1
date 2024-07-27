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

$sql = "SELECT * FROM tb_member";
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

</head>

<body class="sb-nav-fixed">

    <?php include('menu.php') ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 mt-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        เพิ่มบัญชีผู้ใช้งาน
                    </div>
                    <div class="card-body">
                        <h3 class="alert alert-primary">สมัครสมาชิก</h3>
                        <h1 class="text-center text-muted lead mb-3">
                            <?php
                            if (session_status() == PHP_SESSION_NONE) {
                                session_start();
                            }

                            if (!empty($_SESSION["Error"])) {
                                echo "<h4 id='errorMessage' class='alert alert-danger'>" . $_SESSION["Error"] . "</h4>";
                                echo "<script>
                                        setTimeout(function() {
                                            var errorMessage = document.getElementById('errorMessage');
                                            if (errorMessage) {
                                                errorMessage.style.display = 'none';
                                            }
                                        }, 5000); // นับเวลา 5 วินาทีแล้วซ่อนข้อความ
                                    </script>";
                                unset($_SESSION["Error"]); // ลบค่า $_SESSION["Error"] ออกจาก session
                            }
                            ?>

                        </h1>
                        <form method="POST" action="reg_insert.php">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-user'></i></span>
                                <select class="form-control" name="prefix">
                                    <option value="" disabled selected hidden class="text-muted">** คำนำหน้าชื่อ **
                                    </option>
                                    <?php
                                    $options = array('นาย', 'นาง', 'นางสาว');
                                    foreach ($options as $option) {
                                        echo "<option value='$option'>$option</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-user-account'></i></span>
                                <input type="text" class="form-control" name="fname" placeholder="ชื่อ" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-user-account'></i></span>
                                <input type="text" class="form-control" name="lname" placeholder="นามสกุล" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-phone-call'></i></span>
                                <input type="text" class="form-control" name="telephone" placeholder="เบอร์โทรศัพท์"
                                    required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-location-plus'></i></span>
                                <input type="text" class="form-control" name="address" placeholder="ที่อยู่" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-envelope'></i></span>
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-user-circle'></i></span>
                                <input type="text" class="form-control" name="username" placeholder="Username" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-lock'></i></span>
                                <input type="password" class="form-control" name="psw" placeholder="Password" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-lock'></i></span>
                                <input type="password" class="form-control" name="conpsw" placeholder="Confirm Password"
                                    required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-stats'></i></span>
                                <select class="form-control" name="status">
                                    <option value="" disabled selected hidden class="text-muted">** สถานะ **</option>
                                    <?php
                                    $options = array(
                                        "ลูกค้า" => 0,
                                        "ผู้ดูแลระบบ" => 1,
                                    );

                                    foreach ($options as $label => $value) {
                                        echo "<option value='$value'>$label</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mt-4" class="mb-2">
                                <a href="show_account.php" class="btn btn-primary">ย้อนกลับ</a>
                                <button class="btn btn-success" type="submit">ยืนยันการสมัคร</button>
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

<?php
if (isset($_SESSION['addaccount'])) {
    ?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "เพิ่มผู้ใช้สำเร็จ!",
            text: "Successfully",
            showConfirmButton: false,
            timer: 1500
        }).then(function () {
            window.location.href = 'show_account.php';
        });
    </script>

    <?php
    unset($_SESSION['addaccount']);
}
?>
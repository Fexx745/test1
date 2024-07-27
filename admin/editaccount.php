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

$id = $_GET['id'];
$sql = "SELECT * FROM tb_member WHERE id='$id'";
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
                        แก้ไขข้อมูลลูกค้า
                    </div>
                    <div class="card-body">
                        <h3 class="alert alert-primary">แก้ไขข้อมูลลูกค้า</h3>
                        <form method="POST" action="update_account.php" enctype="multipart/form-data">
                            <div class="mb-3 mt-3">
                                <input type="hidden" class="form-control alert alert-success" name="id"
                                    value="<?= $row['id']; ?>" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-user'></i></span>
                                <select class="form-select" name="prefix">
                                    <option value="นาย" <?= ($row['prefix'] == 'นาย') ? 'selected' : ''; ?>>นาย</option>
                                    <option value="นาง" <?= ($row['prefix'] == 'นาง') ? 'selected' : ''; ?>>นาง</option>
                                    <option value="นางสาว" <?= ($row['prefix'] == 'นางสาว') ? 'selected' : ''; ?>>นางสาว</option>
                                </select>
                            </div>


                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-user-account'></i></span>
                                <input type="text" class="form-control" name="fname" value="<?= $row['firstname']; ?>">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-user-account'></i></span>
                                <input type="text" class="form-control" name="lname" value="<?= $row['lastname']; ?>">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-phone-call'></i></span>
                                <input type="text" class="form-control" name="phone" value="<?= $row['telephone']; ?>">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-location-plus'></i></span>
                                <input type="text" class="form-control" name="address" value="<?= $row['address']; ?>">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-envelope'></i></span>
                                <input type="text" class="form-control" name="email" value="<?= $row['email']; ?>" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-user-circle'></i></span>
                                <input type="text" class="form-control" name="username"
                                    value="<?= $row['username']; ?>" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-stats'></i></span>
                                <select class="form-select" name="status">
                                    <option value="0" <?= ($row['status'] == 0) ? 'selected' : ''; ?>>ลูกค้า</option>
                                    <option value="1" <?= ($row['status'] == 1) ? 'selected' : ''; ?>>ผู้ดูแลระบบ</option>
                                </select>
                            </div>

                            <!-- <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-lock'></i></span>
                                <input type="text" class="form-control" name="psw" value="<?= $row['password']; ?>">
                            </div> -->


                            <div class="mt-4">
                                <a href="show_account.php" class="btn btn-primary">ย้อนกลับ</a>
                                <button class="btn btn-success" type="submit">ตกลง</button>
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
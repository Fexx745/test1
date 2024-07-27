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
                        จัดการข้อมูลลูกค้า
                    </div>
                    <div class="card-body">
                    <h3 class="alert alert-primary mt-3 mb-3">จัดการข้อมูลลูกค้า</h3>
                    <a href="addaccount.php" class="btn btn-success mb-3"><i class='bx bxs-plus-circle'></i> เพิ่มผู้ใช้งาน</a>
                        <table id="datatablesSimple" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ชื่อ-สกุล</th>
                                    <th>เบอร์โทรศัพท์</th>
                                    <th>อีเมลล์</th>
                                    <th>ชื่อผู้ใช้</th>
                                    <th>สถานะ</th>
                                    <th>วันเวลาที่สมัคร</th>
                                    <th>จัดการข้อมูล</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM tb_member";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['prefix'] ?> <?= $row['firstname'] ?> <?= $row['lastname'] ?></td>
                                        <td><?= $row['telephone'] ?></td>
                                        <td><?= $row['email'] ?></td>
                                        <td><?= $row['username'] ?></td>
                                        <td><?= $row['status'] == 1 ? 'ผู้ดูแลระบบ' : 'ลูกค้า' ?></td>
                                        <td><?= $row['reg_date'] ?></td>
                                        <td>
                                            <a class="btn btn-warning" href="editaccount.php?id=<?= $row['id'] ?>"><i class='bx bx-edit'></i>แก้ไข</a>
                                            <a class="btn btn-danger" href="javascript:void(0);"
                                                onclick="confirmDelete('<?= $row['id'] ?>')"><i
                                                    class='bx bx-trash'></i>ลบ</a>
                                        </td>
                                    </tr>

                                <?php
                                }
                                mysqli_close($conn);
                                ?>
                            </tbody>

                        </table>
                        <div class="mt-4" class="mb-2">
                                <a href="index.php" class="btn btn-primary">ย้อนกลับ</a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>

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
                window.location.href = `accountdelete.php?id=${id}`;
            }
        });
    }
</script>

<?php
if (isset($_SESSION['deleteaccount'])) {
    ?>
    <script>
        Swal.fire({
            icon: "success",
            title: "ลบผู้ใช้สำเร็จ!",
            text: "Successfully",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    <?php
    unset($_SESSION['deleteaccount']);
}
?>

<?php
if (isset($_SESSION['editaccount'])) {
    ?>
    <script>
        Swal.fire({
            icon: "success",
            title: "แก้ไขผู้ใช้สำเร็จ!",
            text: "Successfully",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    <?php
    unset($_SESSION['editaccount']);
}
?>

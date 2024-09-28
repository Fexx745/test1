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
                        <i class="fas fa-table me-1"></i>
                        ข้อมูลลูกค้าที่ถูกซ่อน
                    </div>
                    <div class="card-body">
                        <div class="alert" style="background: linear-gradient(195deg, #f8f9fa 0%, #f8f9fa 100%); color: #333; border: none; outline: none;">
                            <div class="d-flex">
                                <img src="../assets/images/other/customer3.png" alt="Line Notify Logo" style="height: 60px; margin-right: 5px;">
                                <h3 style="font-weight: 1000; margin-top: 10px;">
                                    ข้อมูลลูกค้าที่ถูกซ่อน
                                </h3>
                            </div>
                        </div>
                        <a href="member_add.php" class="btn btn-success mb-3"><i class='bx bxs-plus-circle'></i> เพิ่มผู้ใช้งาน</a>
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
                            <script>
                                function confirmHide(id) {
                                    Swal.fire({
                                        title: "คุณแน่ใจหรือไม่?",
                                        text: "คุณต้องการแสดงข้อมูลสมาชิกนี้หรือไม่?",
                                        icon: "warning",
                                        showCancelButton: true,
                                        confirmButtonColor: "#0d6efd",
                                        cancelButtonColor: "#d33",
                                        confirmButtonText: "แสดง",
                                        cancelButtonText: "ยกเลิก"
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = `member_show.php?id=${id}`;
                                        }
                                    });
                                }
                            </script>

                            <tbody>
                                <?php
                                $sql = "SELECT * FROM tb_member WHERE status = 3";
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
                                            <a class="btn btn-primary" href="javascript:void(0);"
                                                onclick="confirmHide('<?= $row['id'] ?>')"><i class='bx bxs-show'></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                mysqli_close($conn);
                                ?>
                            </tbody>


                        </table>
                        <div class="mt-4" class="mb-2">
                            <a href="member_List.php" class="btn btn-dark">ย้อนกลับ</a>
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

<?php
if (isset($_SESSION['hideaccount'])) {
?>
    <script>
        Swal.fire({
            icon: "success",
            title: "แสดงผู้ใช้สำเร็จ!",
            footer: "<span style='color: #30b566'>แสดงผู้ใช้งาน</span>",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php
    unset($_SESSION['hideaccount']);
}
?>
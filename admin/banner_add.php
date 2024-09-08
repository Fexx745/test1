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

$sql = "SELECT * FROM unit_type";
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
                        <i class="fas fa-table me-1"></i>
                        ตั้งค่าแบนเนอร์
                    </div>
                    <div class="card-body">
                        <div class="alert" style="background: linear-gradient(195deg, #fef6f5 0%, #fef6f5 100%); color: #333; border: none; outline: none;">
                            <h3 style="font-weight: 1000;">
                                <img src="../assets/images/other/tutorial.png" alt="Tutorial Icon" style="height: 30px; margin-right: 10px;">
                                คำแนะนำ (Tutorial)
                            </h3>
                            <p>
                                <i class='bx bx-image-alt'></i> <strong>ขนาดภาพที่แนะนำ</strong> <i class='bx bx-right-arrow-alt'></i> 1920px x 500px
                                <strong style="color: #ee2c4a;">*รองรับไฟล์ .png .jpg .jpeg และ .gif เท่านั้น</strong>
                            </p>
                        </div>

                        <form method="POST" action="banner_insert.php" enctype="multipart/form-data">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-image-alt'></i></span>
                                <input type="text" class="form-control" name="banner_name" placeholder="ชื่อรูปภาพ"
                                    required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-detail'></i></span>
                                <input type="text" class="form-control" name="banner_detail" placeholder="รายละเอียดรูปภาพ">
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="" class="mb-1">อัพโหลดรูปภาพ:</label>
                                <input type="file" class="form-control" name="fileimage">
                            </div>
                            <div class="mt-4" class="mb-2">
                                <a href="index.php" class="btn btn-dark">ย้อนกลับ</a>
                                <button class="btn btn-danger" type="submit">ยืนยัน</button>
                            </div>
                        </form>
                        <table id="datatablesSimple" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>รูปภาพ</th>
                                    <th>ชื่อรูปภาพ</th>
                                    <th>คำอธิบายรูปภาพ</th>
                                    <th>จัดการข้อมูล</th>
                                </tr>
                            </thead>
                            <h3 class="alert alert-secondary mt-3 mb-3">รูปภาพแสดงทั้งหมด</h3>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM banners";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                ?>
                                    <tr>
                                        <td><?= $row['banner_id'] ?></td>
                                        <td>
                                            <img style="width: 700px; height: 300px; object-fit: contain;"
                                                src="../assets/images/banner/<?= $row['image'] ?>" alt="รูปภาพ"
                                                onerror="this.src='../assets/images/other/no_img.png';">
                                        </td>
                                        <td><?= $row['banner_name'] ?></td>
                                        <td><?= $row['banner_detail'] ?></td>
                                        <td>
                                            <a class="btn btn-warning" href="banner_edit.php?id=<?= $row['banner_id'] ?>"><i class='bx bx-pencil'></i></a>
                                            <a class="btn btn-danger" href="javascript:void(0);"
                                                onclick="confirmDelete('<?= $row['banner_id'] ?>')"><i
                                                    class='bx bx-trash-alt'></i></a>
                                        </td>
                                    </tr>

                                <?php
                                }
                                mysqli_close($conn);
                                ?>
                            </tbody>

                        </table>
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
if (isset($_SESSION['success'])) {
?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "เพิ่มรูปภาพเรียบร้อย!",
            footer: "<span style='color: #30b566'>เพิ่มรูปภาพเรียบร้อย</span>",
            showConfirmButton: false,
            timer: 1500
        });
    </script>

<?php
    unset($_SESSION['success']);
}
?>


<script>
    function confirmDelete(id) {
        Swal.fire({
            title: "คุณแน่ใจหรือไม่?",
            text: "คุณต้องการลบรูปภาพนี้หรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#30b566",
            cancelButtonColor: "#d33",
            confirmButtonText: "ลบ",
            cancelButtonText: "ยกเลิก"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `banner_delete.php?id=${id}`;
            }
        });
    }
</script>

<?php
if (isset($_SESSION['banner_update'])) {
?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "แก้ไขสำเร็จ!",
            footer: "<span style='color: #30b566'>แก้ไขรูปภาพสำเร็จ</span>",
            showConfirmButton: false,
            timer: 1500
        });
    </script>

<?php
    unset($_SESSION['banner_update']);
}
?>

<?php
if (isset($_SESSION['deletebanner'])) {
?>
    <script>
        Swal.fire({
            icon: "success",
            title: "ลบสำเร็จ!",
            footer: "<span style='color: #30b566'>ลบรูปภาพสำเร็จ</span>",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php
    unset($_SESSION['deletebanner']);
}
?>
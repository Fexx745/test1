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
                        <i class="bx bxs-package"></i>
                        หน่วยสินค้า
                    </div>
                    <div class="card-body">
                    <div class="alert" style="background: linear-gradient(195deg, #f8f9fa 0%, #f8f9fa 100%); color: #333; border: none; outline: none;">
                            <div class="d-flex"><img src="../assets/images/other/unit.png" alt="Line Notify Logo" style="height: 50px; margin-right: 10px;">
                                <h3 style="font-weight: 1000; margin-top: 10px;">
                                    เพิ่มหน่วยสินค้า
                                </h3>
                            </div>
                        </div>
                        <form method="POST" action="unit_insert.php" enctype="multipart/form-data">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-package'></i></span>
                                <input type="text" class="form-control" name="unitname" placeholder="หน่วยสินค้า" required>
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
                                    <th>หน่วยสินค้า</th>
                                    <th>จัดการหน่วยสินค้า</th>
                                </tr>
                            </thead>
                            <h3 class="alert alert-secondary mt-3 mb-3">หน่วยสินค้าทั้งหมด</h3>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM unit_type";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                ?>
                                    <tr>
                                        <td><?= $row['unit_id'] ?></td>
                                        <td><?= $row['unit_name'] ?></td>
                                        <td>
                                            <a class="btn btn-warning" href="javascript:void(0);" onclick="editUnit('<?= $row['unit_id'] ?>', '<?= $row['unit_name'] ?>')"><i class='bx bx-pencil'></i></a>
                                            <a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete('<?= $row['unit_id'] ?>')"><i class='bx bx-trash-alt'></i></a>
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
if (isset($_SESSION['addunit'])) {
?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "เพิ่มหน่วยสำเร็จ!",
            footer: "<span style='color: #30b566'>เพิ่มหน่วยสำเร็จ</span>",
            showConfirmButton: false,
            timer: 1500
        });
    </script>

<?php
    unset($_SESSION['addunit']);
}
?>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: "คุณแน่ใจหรือไม่?",
            text: "คุณต้องการลบหน่วยสินค้านี้หรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#0d6efd",
            cancelButtonColor: "#d33",
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `unit_delete.php?id=${id}`;
            }
        });
    }
</script>

<?php
if (isset($_SESSION['editunit'])) {
?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "แก้ไขสำเร็จ!",
            footer: "<span style='color: #30b566'>แก้ไขหน่วยสินค้าสำเร็จ</span>",
            showConfirmButton: false,
            timer: 1500
        });
    </script>

<?php
    unset($_SESSION['editunit']);
}
?>

<?php
if (isset($_SESSION['deleteunit'])) {
?>
    <script>
        Swal.fire({
            icon: "success",
            title: "ลบสำเร็จ!",
            footer: "<span style='color: #30b566'>ลบหน่วยสินค้าสำเร็จ</span>",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php
    unset($_SESSION['deleteunit']);
}
?>

<?php
if (isset($_SESSION['error_unit_delete'])) {
?>
    <script>
        Swal.fire({
            icon: "warning",
            title: "ลบไม่สำเร็จ!",
            footer: "<span style='color: #ee2c4a'>มีหน่วยสินค้านี้ในสินค้าอยู่</span>",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php
    unset($_SESSION['error_unit_delete']);
}
?>

<script>
    function editUnit(id, currentName) {
        Swal.fire({
            title: 'แก้ไขชื่อหน่วย',
            input: 'text',
            inputValue: currentName,
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'บันทึก',
            showLoaderOnConfirm: true,
            preConfirm: (newName) => {
                return fetch('unit_edit.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            unitid: id,
                            unitname: newName
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'แก้ไขเรียบร้อย!',
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            }
        });
    }
</script>
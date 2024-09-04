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

$sql = "SELECT * FROM shipping_type";
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
                        <i class='bx bxs-truck'></i>
                        ข้อมูลการส่งสินค้า
                    </div>
                    <div class="card-body">
                        <h3 class="alert alert-primary">เพิ่มขนส่ง</h3>
                        <form method="POST" action="shipping_insert.php" enctype="multipart/form-data">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-truck'></i></span>
                                <input type="text" class="form-control" name="shipping_name" placeholder="ชื่อขนส่ง" required>
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
                                    <th>ชื่อขนส่ง</th>
                                    <th>จัดการข้อมูล</th>
                                </tr>
                            </thead>
                            <h3 class="alert alert-secondary mt-3 mb-3">ข้อมูลขนส่งทั้งหมด</h3>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM shipping_type";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                ?>
                                    <tr>
                                        <td><?= $row['shipping_type_id'] ?></td>
                                        <td><?= $row['shipping_type_name'] ?></td>
                                        <td>
                                            <a class="btn btn-warning" href="javascript:void(0);" onclick="editShipping('<?= $row['shipping_type_id'] ?>', '<?= $row['shipping_type_name'] ?>')"><i class='bx bx-pencil'></i></a>
                                            <a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete('<?= $row['shipping_type_id'] ?>')"><i class='bx bx-trash-alt'></i></a>
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
if (isset($_SESSION['addshipping'])) {
?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "เพิ่มขนส่งสำเร็จ!",
            footer: "<span style='color: #30b566'>เพิ่มขนส่งสำเร็จ</span>",
            showConfirmButton: false,
            timer: 1500
        });
    </script>

<?php
    unset($_SESSION['addshipping']);
}
?>

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
                window.location.href = `shipping_delete.php?id=${id}`;
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
            footer: "<span style='color: #30b566'>แก้ไขสำเร็จ</span>",
            showConfirmButton: false,
            timer: 1500
        });
    </script>

<?php
    unset($_SESSION['editunit']);
}
?>

<?php
if (isset($_SESSION['deleteshipping'])) {
?>
    <script>
        Swal.fire({
            icon: "success",
            title: "ลบสำเร็จ!",
            footer: "<span style='color: #30b566'>ลบสำเร็จ</span>",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php
    unset($_SESSION['deleteshipping']);
}
?>

<?php
if (isset($_SESSION['error_shipping_delete'])) {
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
    unset($_SESSION['error_shipping_delete']);
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function editShipping(id, currentName) {
    Swal.fire({
        title: 'แก้ไขข้อมูลการขนส่ง',
        input: 'text',
        inputValue: currentName,
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'บันทึก',
        showLoaderOnConfirm: true,
        preConfirm: (newName) => {
            return fetch(`shipping_edit.php?id=${id}&name=${newName}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error}`)
                })
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

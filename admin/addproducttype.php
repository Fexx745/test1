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

$sql = "SELECT * FROM product_type";
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
                        ประเภทสินค้า
                    </div>
                    <div class="card-body">
                        <h3 class="alert alert-primary">เพิ่มประเภทสินค้า</h3>
                        <form method="POST" action="insert_producttype.php" enctype="multipart/form-data">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-grid'></i></span>
                                <input type="text" class="form-control" name="typename" placeholder="ชื่อประเภทสินค้า" required>
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="" class="mb-1">เลือกรูปภาพ:</label>
                                <input type="file" class="form-control" name="fileimage">
                            </div>
                            <div class="mt-4" class="mb-2">
                                <a href="index.php" class="btn btn-primary">ย้อนกลับ</a>
                                <button class="btn btn-success" type="submit">ยืนยัน</button>
                            </div>
                        </form>
                        <table id="datatablesSimple" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>รูปภาพ</th>
                                    <th>ชื่อประเภทสินค้า</th>
                                    <th>แก้ไข</th>
                                    <th>ลบ</th>
                                </tr>
                            </thead>
                            <h3 class="alert alert-secondary mt-3 mb-3">ประเภทสินค้าทั้งหมด</h3>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM product_type";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                ?>
                                    <tr>
                                        <td><?= $row['type_id'] ?></td>
                                        <td>
                                            <img style="width: 100px; height: 100px; object-fit: cover;" src="../assets/images/type_product/<?= $row['type_image'] ?>" alt="รูปภาพ" onerror="this.src='../assets/images/other/no_img.png';">
                                        </td>
                                        <td><?= $row['type_name'] ?></td>
                                        <!-- <td>
                                            <a class="btn btn-warning" href="javascript:void(0);" onclick="editTypeProduct('<?= $row['type_id'] ?>', '<?= $row['type_name'] ?>')"><i class='bx bx-edit'></i>แก้ไข</a>
                                        </td> -->
                                        <td>
                                            <a class="btn btn-warning" href="prd_edit_typeproduct.php?id=<?= $row['type_id'] ?>"><i class='bx bx-edit'></i>แก้ไข</a>
                                        </td>
                                        <td>
                                            <a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete('<?= $row['type_id'] ?>')"><i class='bx bx-trash'></i>ลบ</a>
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
if (isset($_SESSION['edit_producttype'])) {
?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "แก้ไขสินค้าสำเร็จ!",
            text: "Successfully",
            showConfirmButton: false,
            timer: 1500
        });
    </script>

<?php
    unset($_SESSION['edit_producttype']);
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
                window.location.href = `prd_typedelete.php?id=${id}`;
            }
        });
    }
</script>

<?php
if (isset($_SESSION['delete_typeproduct'])) {
?>
    <script>
        Swal.fire({
            icon: "success",
            title: "ลบสำเร็จ!",
            text: "Successfully",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php
    unset($_SESSION['delete_typeproduct']);
}
?>

<?php
if (isset($_SESSION['addproducttype'])) {
?>
    <script>
        Swal.fire({
            icon: "success",
            title: "เพิ่มประเภทสินค้าสำเร็จ!",
            text: "Successfully",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php
    unset($_SESSION['addproducttype']);
}
?>

<?php
if (isset($_SESSION['delete_error'])) {
?>
    <script>
        Swal.fire({
            icon: "warning",
            title: "ลบไม่สำเร็จ!",
            text: "มีประเภทสินค้านี้ในสินค้าอยู่",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php
    unset($_SESSION['delete_error']);
}
?>

<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function editTypeProduct(id, currentName, currentImage) {
    Swal.fire({
        title: 'แก้ไขประเภทสินค้า',
        html: `
            <form id="editTypeProductForm" enctype="multipart/form-data">
                <label for="typeName">ชื่อประเภทสินค้า:</label>
                <input type="text" id="typeName" name="typeName" class="swal2-input" value="${currentName}">
                <label for="currentImage">รูปภาพปัจจุบัน:</label>
                <img id="currentImage" src="../assets/images/type_product/${currentImage}" alt="รูปภาพ" style="width: 100px; height: 100px; object-fit: cover;" onerror="this.src='../assets/images/other/no_img.png';">
                <label for="typeImage">อัปโหลดรูปภาพใหม่:</label>
                <input type="file" id="typeImage" name="typeImage" class="swal2-file">
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'บันทึก',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const form = document.getElementById('editTypeProductForm');
            const formData = new FormData(form);
            formData.append('typeid', id);
            return fetch('edittype.php', {
                method: 'POST',
                body: formData
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
</script> -->
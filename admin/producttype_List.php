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

</head>

<body class="sb-nav-fixed">

    <?php include('menu.php') ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 mt-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class='bx bx-grid'></i>
                        ประเภทสินค้า
                    </div>
                    <div class="card-body">
                        <div class="alert"
                            style="background: linear-gradient(195deg, #f8f9fa 0%, #f8f9fa 100%); color: #333; border: none; outline: none;">
                            <div class="d-flex"><img src="../assets/images/other/type_product.png"
                                    alt="Line Notify Logo" style="height: 50px; margin-right: 10px;">
                                <h3 style="font-weight: 1000; margin-top: 10px;">
                                    เพิ่มประเภทสินค้า
                                </h3>
                            </div>
                        </div>
                        <form method="POST" action="producttype_insert.php" enctype="multipart/form-data">
                            <div id="typeFeedback" class="form-text" style="display: none;"></div>
                            <span id="checkIcon" style="display: none;"><i class='bx bx-check'
                                    style="color: green; font-size: 1.5rem;"></i></span>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-grid'></i></span>
                                <input type="text" class="form-control" name="typename" id="typename"
                                    placeholder="ชื่อประเภทสินค้า" required>
                            </div>


                            <div class="mb-3 mt-3">
                                <label for="" class="mb-1">เลือกรูปภาพ:</label>
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
                                    <th>ชื่อประเภทสินค้า</th>
                                    <th>จัดการประเภทสินค้า</th>
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
                                            <img style="width: 100px; height: 100px; object-fit: cover;"
                                                src="../assets/images/type_product/<?= $row['type_image'] ?>" alt="รูปภาพ"
                                                onerror="this.src='../assets/images/other/no_img.png';">
                                        </td>
                                        <td><?= $row['type_name'] ?></td>
                                        <td>
                                            <a class="btn btn-warning"
                                                href="producttype_edit.php?id=<?= $row['type_id'] ?>"><i
                                                    class='bx bx-pencil'></i></a>
                                            <a class="btn btn-danger" href="javascript:void(0);"
                                                onclick="confirmDelete('<?= $row['type_id'] ?>')"><i
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
            title: "คุณแน่ใจหรือไม่?",
            text: "คุณต้องการลบประเภทสินค้าหรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#0d6efd",
            cancelButtonColor: "#d33",
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `producttype_delete.php?id=${id}`;
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
            footer: "<span style='color: #30b566'>ลบประเภทสินค้าสำเร็จ</span>",
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
            footer: "<span style='color: #30b566'>เพิ่มประเภทสินค้าสำเร็จ</span>",
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
            footer: "<span style='color: #ee2c4a'>**มีประเภทสินค้านี้ในสินค้าอยู่</span>",
            showConfirmButton: false,
            timer: 2500
        });
    </script>
    <?php
    unset($_SESSION['delete_error']);
}
?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('#typename').on('input', function () {
            var typename = $(this).val();

            if (typename.length > 0) {
                $.ajax({
                    url: 'producttype_check_name.php', // Path to the server-side script to check for duplicates
                    type: 'POST',
                    data: { typename: typename },
                    success: function (response) {
                        if (response == "available") {
                            // Name is available, show green border and the checkmark icon
                            $('#typename').css('border', '1px solid green');
                            $('#typeFeedback').hide(); // Hide the error message
                            $('#checkIcon').show(); // Show the checkmark icon
                        } else {
                            // Name is taken, show red border and error message
                            $('#typename').css('border', '1px solid red');
                            $('#typeFeedback').html('ชื่อประเภทสินค้านี้มีอยู่แล้ว').show(); // Show error message
                            $('#checkIcon').hide(); // Hide the checkmark icon
                        }
                    }
                });
            } else {
                // Reset the input if empty
                $('#typename').css('border', '');
                $('#typeFeedback').hide();
                $('#checkIcon').hide();
            }
        });
    });
</script>
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

$sql = "SELECT * FROM product";
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
                        <i class='bx bxs-plus-circle'></i>
                        เพิ่มสินค้า
                    </div>
                    <div class="card-body">
                        <div class="alert"
                            style="background: linear-gradient(195deg, #f8f9fa 0%, #f8f9fa 100%); color: #333; border: none; outline: none;">
                            <div class="d-flex"><img src="../assets/images/other/addStock.png"
                                    style="height: 50px; margin-right: 10px;">
                                <h3 style="font-weight: 1000; margin-top: 20px;">
                                    เพิ่มสินค้า
                                </h3>
                            </div>
                        </div>

                        <form method="POST" action="product_insert.php" enctype="multipart/form-data">
                            <div id="nameFeedback" class="my-2" style="display: none;"></div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-package'></i></span>
                                <input type="text" class="form-control" name="p_name" id="p_name"
                                    placeholder="ชื่อสินค้า" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-detail'></i></span>
                                <input type="text" class="form-control" name="detail" placeholder="รายละเอียดสินค้า"
                                    required>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">ประเภท&nbsp;&gt;</label>
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" for="typeprd"><i
                                                class='bx bx-category'></i></label>
                                        <select name="typeprd" id="typeprd" class="form-select">
                                            <?php
                                            include('condb.php');
                                            $sql = "SELECT DISTINCT t.type_id, t.type_name FROM product as p INNER JOIN product_type as t ON p.type_id = t.type_id ORDER BY t.type_id";
                                            $result = mysqli_query($conn, $sql);

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($rows = mysqli_fetch_array($result)) {
                                                    ?>
                                                    <option value='<?php echo $rows['type_id']; ?>'>
                                                        <?php echo $rows['type_name']; ?>
                                                    </option>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <option value=''>No types found</option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="">หน่วย&nbsp;&gt;</label>
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" for="unittype"><i
                                                class='bx bx-cube'></i></label>
                                        <select name="unittype" id="unittype" class="form-select">
                                            <?php
                                            include('condb.php');
                                            $sql2 = "SELECT DISTINCT u.unit_id, u.unit_name FROM unit_type as u ORDER BY u.unit_id";
                                            $result2 = mysqli_query($conn, $sql2);

                                            if (mysqli_num_rows($result2) > 0) {
                                                while ($row2 = mysqli_fetch_array($result2)) {
                                                    ?>
                                                    <option value='<?php echo $row2['unit_id']; ?>'>
                                                        <?php echo $row2['unit_name']; ?>
                                                    </option>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <option value=''>No types found</option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="">ยี่ห้อ&nbsp;&gt;</label>
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" for="brandprd"><i class='bx bx-tag'></i></label>
                                        <select name="brandprd" id="brandprd" class="form-select">
                                            <?php
                                            include('condb.php');
                                            $sql3 = "SELECT DISTINCT b.brand_id, b.brand_name FROM brand_type as b ORDER BY b.brand_id";
                                            $result3 = mysqli_query($conn, $sql3);

                                            if (mysqli_num_rows($result3) > 0) {
                                                while ($row3 = mysqli_fetch_array($result3)) {
                                                    ?>
                                                    <option value='<?php echo $row3['brand_id']; ?>'>
                                                        <?php echo $row3['brand_name']; ?>
                                                    </option>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <option value=''>No types found</option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>



                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-money'></i></span>
                                <input type="text" class="form-control" name="price" placeholder="ราคาสินค้า" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-package'></i></span>
                                <input type="text" class="form-control" name="amount" placeholder="จำนวนสินค้า"
                                    required>
                            </div>

                            <div class="mb-3 mt-3">
                                <label for="" class="mb-1">อัพโหลดรูปภาพ:</label>
                                <input type="file" class="form-control" name="fileimage">
                            </div>

                            <div class="mt-4" class="mb-2">
                                <a href="product_List.php" class="btn btn-dark">ย้อนกลับ</a>
                                <button class="btn btn-danger" type="submit">ยืนยัน</button>
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
if (isset($_SESSION['addproduct'])) {
    ?>
    <script>
        Swal.fire({
            icon: "success",
            title: "เพิ่มสินค้าสำเร็จ!",
            footer: '<span style="color: #00c300;">เพิ่มข้อมูลสินค้าเรียบร้อยแล้ว</span>',
            showConfirmButton: false,
            timer: 1500
        }).then(function () {
            window.location.href = 'product_List.php';
        });
    </script>

    <?php
    unset($_SESSION['addproduct']);
}
?>
<?php
if (isset($_SESSION['check_name'])) {
    ?>
    <script>
        Swal.fire({
            icon: "warning",
            title: "ชื่อสินค้าซ้ำกับในระบบ!",
            footer: '<span style="color: #ee2c4a;">**กรุณาเปลี่ยนชื่อสินค้าใหม่</span>',
            showConfirmButton: false,
            timer: 1500
        }).then(function () {
            window.location.href = 'product_add.php';
        });
    </script>

    <?php
    unset($_SESSION['check_name']);
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('#p_name').on('input', function () {
            var p_name = $(this).val();

            if (p_name.length > 0) {
                $.ajax({
                    url: 'product_check_name.php',
                    type: 'POST',
                    data: { p_name: p_name },
                    success: function (response) {
                        if (response == "available") {
                            // Name is available, show green border
                            $('#p_name').css('border', '1px solid green');
                            $('#nameFeedback').html('<span style="color: green;">ชื่อสินค้านี้ใช้ได้</span>');
                            $('#nameFeedback').show();
                        } else {
                            // Name is already taken, show red border
                            $('#p_name').css('border', '1px solid red');
                            $('#nameFeedback').html('<span style="color: red;">ชื่อสินค้านี้มีอยู่แล้วในระบบ</span>');
                            $('#nameFeedback').show();
                        }
                    }
                });
            } else {
                // Reset the input if the field is empty
                $('#p_name').css('border', '');
                $('#nameFeedback').hide();
            }
        });
    });
</script>
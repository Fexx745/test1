<?php
session_start();
include ('condb.php');

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
} elseif ($_SESSION['status'] !== '1') {
    header('Location: ../login.php');
    exit();
}

// ตรวจสอบสินค้าที่หมดสต็อก (เหลือ 0 ชิ้น)
$sql_out_of_stock = "SELECT p_name, amount FROM product WHERE amount = 0";
$result_out_of_stock = mysqli_query($conn, $sql_out_of_stock);
$out_of_stock_count = mysqli_num_rows($result_out_of_stock); // จำนวนสินค้าที่หมดสต็อก

// สินค้าทั้งหมด
$sql4 = "SELECT COUNT(p_id) as all_pd FROM product";
$result4 = mysqli_query($conn, $sql4);
$row4 = mysqli_fetch_array($result4);

// ตรวจสอบจำนวนสินค้าที่น้อยกว่า 10 ชิ้น
$sql_low_stock = "SELECT p_name, amount FROM product WHERE amount < 10";
$result_low_stock = mysqli_query($conn, $sql_low_stock);
$low_stock_count = mysqli_num_rows($result_low_stock); // จำนวนสินค้าที่น้อยกว่า 10 ชิ้น



// เงื่อนไขสำหรับการแจ้งเตือนผ่าน LINE
// if ($low_stock_count > 0) {
//     $sToken = "oUuMDZ2et5SgODlfhImzTIQ6rGAkybpRc4Bp1n63TY7";
//     $sMessage = "มีสินค้าในคลังที่เหลือน้อยกว่า 10 ชิ้น!\n";
//     $sMessage .= "จำนวนสินค้า: " . $low_stock_count . " รายการ\n\n";

//     while ($row = mysqli_fetch_assoc($result_low_stock)) {
//         $sMessage .= "ชื่อสินค้า: " . $row['p_name'] . "\n";
//         $sMessage .= "จำนวนคงเหลือ: " . $row['amount'] . " ชิ้น\n\n";
//     }

//     $chOne = curl_init();
//     curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
//     curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
//     curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
//     curl_setopt($chOne, CURLOPT_POST, 1);
//     curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . urlencode($sMessage));
//     $headers = array(
//         'Content-type: application/x-www-form-urlencoded',
//         'Authorization: Bearer ' . $sToken,
//     );
//     curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
//     $result = curl_exec($chOne);

//     $logStatus = 'unknown'; // ตั้งค่าเริ่มต้นให้กับ $logStatus
//     $logMessage = '';
//     if (curl_error($chOne)) {
//         $logStatus = 'fail';
//         $logMessage = 'cURL error: ' . curl_error($chOne);
//         echo $logMessage;
//     } else {
//         $result_ = json_decode($result, true);
//         $logStatus = $result_['status'] == 200 ? 'success' : 'fail';
//         $logMessage = $result_['message'];
//         if ($result_['status'] == 200) {
//             echo "Notification sent successfully.";
//         } else {
//             echo "Error sending notification: " . $result_['message'];
//         }
//         echo "Status: " . $result_['status'] . "<br>";
//         echo "Message: " . $result_['message'];
//     }
//     curl_close($chOne);
// }
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

    <?php include ('menu.php') ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 mt-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4"
                            style="background: linear-gradient(195deg, #42424a 0%, #191919 100%);">
                            <div class="card-body">
                                สินค้าทั้งหมด<h5><?= $row4['all_pd'] ?> รายการ</h5>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div>
                                    <small>
                                        <a href="#" style="text-decoration: none; color: white; font-size: 13px;">
                                            <i class='bx bxs-store'
                                                style="color: #fff; background: rgba(255, 255, 255, 0.3); padding: 7px; border-radius: 50%; font-size: 20px;"></i>
                                            สินค้าทั้งหมด
                                        </a>
                                    </small>
                                </div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4"
                            style="background: linear-gradient(195deg, #fb8be9 0%, #D81B60 100%);">
                            <div class="card-body">
                                สินค้าต่ำกว่า 10 ชิ้น<h5><?= $low_stock_count ?> รายการ</h5>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div>
                                    <small>
                                        <a href="#"
                                            style="text-decoration: none; color: white; font-size: 13px;">
                                            <i class='bx bx-error'
                                                style="color: #fff; background: rgba(255, 255, 255, 0.3); padding: 7px; border-radius: 50%; font-size: 20px;"></i>
                                            สินค้าต่ำกว่าเกณฑ์
                                        </a>
                                    </small>
                                </div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4"
                            style="background: linear-gradient(195deg, #58d68d 0%, #43A047 100%);">
                            <div class="card-body">
                                สินค้าหมดสต็อก<h5><?= $out_of_stock_count ?> รายการ</h5>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div>
                                    <small>
                                        <a href="#"
                                            style="text-decoration: none; color: white; font-size: 13px;">
                                            <i class='bx bx-error-alt'
                                                style="color: #fff; background: rgba(255, 255, 255, 0.3); padding: 7px; border-radius: 50%; font-size: 20px;"></i>
                                            สินค้าหมดสต็อก
                                        </a>
                                    </small>
                                </div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <!-- <div class="card text-white mb-4"
                            style="background: linear-gradient(195deg, #FFC107 0%, #FF9800 100%);"> -->
                        <div class="card text-white mb-4"
                            style="background: linear-gradient(195deg, #71cdf5 0%, #1A73E8 100%);">
                            <div class="card-body">
                                Comming Soon ... <h5> # </h5>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div>
                                    <small>
                                        <a href="#"
                                            style="text-decoration: none; color: white; font-size: 13px;">
                                            <i class='bx bx-error'
                                                style="color: #fff; background: rgba(255, 255, 255, 0.3); padding: 7px; border-radius: 50%; font-size: 20px;"></i>
                                            Comming Soon ...
                                        </a>
                                    </small>
                                </div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        สินค้าทั้งหมด
                    </div>
                    <div class="card-body">
                        <a href="addproduct.php" class="btn btn-success mb-3"><i class='bx bxs-plus-circle'></i>
                            เพิ่มสินค้า</a>
                        <table id="datatablesSimple" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>รูปภาพ</th>
                                    <!-- <th>รหัสสินค้า</th> -->
                                    <th>ชื่อสินค้า</th>
                                    <!-- <th>รายละเอียด</th> -->
                                    <th>ประเภท</th>
                                    <th>ยี่ห้อ</th>
                                    <th>ราคา</th>
                                    <th>จำนวน</th>
                                    <th>แก้ไขข้อมูลสินค้า</th>
                                    <th>ปรับราคาสินค้า</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $sql = "SELECT * FROM product as p
                                        INNER JOIN product_type as t ON p.type_id = t.type_id 
                                        INNER JOIN price_history as ph ON p.p_id = ph.p_id
                                        LEFT JOIN unit_type as u ON p.unit_id = u.unit_id
                                        LEFT JOIN brand_type as b ON p.brand_id = b.brand_id
                                        ORDER BY p.p_id";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <tr>
                                        <td>
                                            <img style="width: 100px; height: 100px; object-fit: cover;"
                                                src="../assets/images/product/<?= $row['image'] ?>" alt="รูปภาพ"
                                                onerror="this.src='../assets/images/other/no_img.png';">
                                        </td>
                                        <!-- <td><?= $row['p_id'] ?></td> -->
                                        <td>
                                            <?= $row['p_name'] ?>
                                        </td>
                                        <!-- <td style="max-width: 100px;"><?= $row['detail'] ?></td> -->
                                        <td>
                                            <?= $row['type_name'] ?>
                                        </td>
                                        <td>
                                            <?= $row['brand_name'] ?>
                                        </td>
                                        <td>
                                            <?= number_format($row['price'], 2) ?>
                                        </td>
                                        <td>
                                            <?= $row['amount'] ?>
                                            <?= $row['unit_name'] ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-success" href="addstock.php?id=<?= $row['p_id'] ?>"><i
                                                    class='bx bx-plus'></i>เพิ่มจำนวนสินค้า</a>
                                            <a class="btn btn-warning" href="prd_edit_product.php?id=<?= $row['p_id'] ?>"><i
                                                    class='bx bx-edit'></i>แก้ไข</a>
                                            <a class="btn btn-danger" href="javascript:void(0);"
                                                onclick="confirmDelete('<?= $row['p_id'] ?>')"><i
                                                    class='bx bx-trash'></i>ลบ</a>
                                        </td>
                                        <td>
                                            <a class="btn btn-info" href="prd_editprice.php?id=<?= $row['p_id'] ?>"><i
                                                    class='bx bx-money'></i></a>
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

        <?php include ('footer.php') ?>
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

<!-- Your PHP code -->
<?php
if (isset($_SESSION['delete_product'])) {
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
    unset($_SESSION['delete_product']);
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
                window.location.href = `prd_delete.php?id=${id}`;
            }
        });
    }
</script>

<?php
if (isset($_SESSION['edit_product'])) {
    ?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "แก้ไขสำเร็จ!",
            text: "Successfully",
            showConfirmButton: false,
            timer: 1500
        }).then(function () {
            window.location.href = 'prd_show_product.php';
        });
    </script>

    <?php
    unset($_SESSION['edit_product']);
}
?>

<?php
if (isset($_SESSION['addstock'])) {
    ?>
    <script>
        Swal.fire({
            icon: "success",
            title: "เพิ่มสต็อกสำเร็จ!",
            text: "Successfully",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    <?php
    unset($_SESSION['addstock']);
}
?>
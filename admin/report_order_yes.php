<?php
include('condb.php');
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit(); // คำสั่งออกจากการทำงานทันทีหลังจาก redirect
}

//รายการสั่งซื้อสินค้าที่ยังไม่ชำระเงิน
$sql = "SELECT COUNT(orderID) as order_no FROM tb_order WHERE order_status='1' ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

// //รายการสั่งซื้อที่ถูกยกเลิก
$sql2 = "SELECT COUNT(orderID) as order_cancel FROM tb_order WHERE order_status='0' ";
$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_array($result2);

// //รายการสั่งซื้อสินค้าที่ชำระเงินแล้ว
$sql3 = "SELECT COUNT(orderID) as order_yes FROM tb_order WHERE order_status='2' ";
$result3 = mysqli_query($conn, $sql3);
$row3 = mysqli_fetch_array($result3);

// //รายการสินค้าที่เหลือต่ำกว่า 10 ชิ้น
$sql4 = "SELECT COUNT(p_id) as all_pd FROM product";
$result4 = mysqli_query($conn, $sql4);
$row4 = mysqli_fetch_array($result4);
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
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4" style="background: #dc3545;">
                            <div class="card-body">รายการสินค้าทั้งหมด<h4>
                                    <?= $row4['all_pd'] ?>
                                </h4>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small style="font-size: 12px;"><a href="index.php" style="text-decoration: none; color: white;">
                                            <i class='bx bxs-store'></i> สินค้าทั้งหมด</a></small></div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4" style="background: #6f42c1;">
                            <div class="card-body">รายการสั่งซื้อสินค้า (ยังไม่ชำระเงิน)<h4>
                                    <?= $row['order_no'] ?>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small style="font-size: 12px;"><a href="report_order.php" style="text-decoration: none; color: white;">
                                            <i class='bx bxs-error-alt'></i> ยังไม่ชำระเงิน</a></small></div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4" style="background: #198754;">
                            <div class="card-body">รายการสั่งซื้อสินค้า (ชำระเงินแล้ว)<h4>
                                    <?= $row3['order_yes'] ?>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small style="font-size: 12px;"><a href="report_order_yes.php" style="text-decoration: none; color: white;">
                                            <i class='bx bxs-wallet'></i> ชำระเงินแล้ว</a></small></div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4" style="background: #0d6efd;">
                            <div class="card-body">รายการสั่งซื้อสินค้า (ยกเลิก) 10 ชิ้น<h4>
                                    <?= $row2['order_cancel'] ?>
                                </h4>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small style="font-size: 12px;"><a href="report_order_no.php" style="text-decoration: none; color: white;">
                                            <i class='bx bxs-error-alt'></i> ยกเลิกคำสั่งซื้อ</a></small>
                                </div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        แสดงข้อมูลการสั่งซื้อสินค้า (ชำระเงินแล้ว)

                        <div class="mt-3 mb-3">
                            <a href="report_order_yes.php"><button type="button" class="btn" style="background: #198754; color: #fff;"><i class='bx bx-check-circle'></i>&nbsp;ชำระเงินแล้ว</button></a>
                            <a href="report_order.php"><button type="button" class="btn" style="background: #6f42c1; color: #fff;"><i class='bx bxs-hourglass'></i>&nbsp;ยังไม่ชำระเงิน</button></a>
                            <a href="report_order_no.php"><button type="button" class="btn" style="background: #dc3545; color: #fff;"><i class='bx bx-rotate-right'></i>&nbsp;ยกเลิกการสั่งซื้อ</button></a>
                        </div>

                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>เลขที่ใบสั่งซื้อ</th>
                                    <th>ลูกค้า</th>
                                    <th>ที่อยู่จัดส่งสินค้า</th>
                                    <th>เบอร์โทรศัพท์</th>
                                    <th>ราคารวมสุทธิ</th>
                                    <th>วันที่สั่งซื้อ</th>
                                    <th>สถานะการสั่งซื้อ</th>
                                    <th>รายละเอียด</th>
                                    <th>ลบคำสั่งซื้อ</th>
                                    <th>ออกใบเสร็จ</th>
                                </tr>
                            </thead>
                            <!-- search fill -->
                            <tfoot>
                                <tr>
                                    <th>orderID</th>
                                    <th>cus_name</th>
                                    <th>address</th>
                                    <th>telephone</th>
                                    <th>telephone</th>
                                    <th>telephone</th>
                                    <th>telephone</th>
                                </tr>
                            </tfoot>

                            <tbody>
                                <?php
                                $sql = "SELECT *
                                FROM tb_order as t
                                JOIN tb_member as tm ON t.member_id = tm.id
                                WHERE t.order_status = 2
                                ORDER BY t.reg DESC;";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                    $status = $row['order_status'];
                                ?>
                                    <tr>
                                        <td>
                                            <?= $row['orderID'] ?>
                                        </td>
                                        <td>
                                            <?= $row['prefix'] ?>
                                            <?= $row['firstname'] ?>
                                            <?= $row['lastname'] ?>
                                        </td>
                                        <td>
                                            <?= $row['address'] ?>
                                        </td>
                                        <td>
                                            <?= $row['telephone'] ?>
                                        </td>
                                        <td>
                                            <?= $row['total_price'] ?>
                                        </td>
                                        <td>
                                            <?= $row['reg'] ?>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <?php
                                                if ($status == 1) {
                                                    echo "<b style='color: blue;'>ยังไม่ชำระเงิน</b>";
                                                } else if ($status == 2) {
                                                    echo "<span style='color: white; border-radius: 20px; background: #198754; padding: 5px;'><i class='bx bx-check' ></i>ชำระเงินแล้ว</span>";
                                                } else if ($status == 0) {
                                                    echo "<b style='color: red;'>ยกเลิกการสั่งซื้อ</b>";
                                                }
                                                ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <a href="report_order_detail.php?id=<?= $row['orderID'] ?>" class="btn btn-warning"><i class='bx bx-message-detail'></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete('<?= $row['orderID'] ?>')"><i class='bx bx-trash'></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <a class="btn btn-primary" href="generate_receipt.php?id=<?= $row['orderID'] ?>"><i class='bx bx-printer'></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                                mysqli_close($conn);
                                ?>
                            </tbody>

                        </table>
                        <div class="mb-2">
                            <a href="report_order.php" class="btn btn-primary">ย้อนกลับ</a>
                        </div>
                    </div>
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
if (isset($_SESSION['confirmOrder'])) {
?>
    <script>
        Swal.fire({
            icon: "success",
            title: "ยืนยันคำสั่งซื้อสำเร็จ เตรียมจัดส่ง...!",
            text: "Order confirm successfully",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php
    unset($_SESSION['confirmOrder']);
}
?>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "คุณต้องการลบคำสั่งซื้อหรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `delete_order.php?id=${id}`;
            }
        });
    }
</script>
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
$sql4 = "SELECT COUNT(orderID) as order_wait FROM tb_order WHERE order_status='3' ";
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
    <link href="css/index-admin.css" rel="stylesheet" />
    <!-- Font -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="assets/dist/sweetalert2.all.min.js"></script>

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
                        <div class="card text-white mb-4 dashboard-1">
                            <div class="card-body">รายการคำสั่งซื้อที่รอตรวจสอบ<h4>
                                    <?= $row['order_no'] ?>
                                </h4>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small><a href="report_order.php">
                                            <i class='bx bxs-store'></i> รอตรวจสอบ</a></small></div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4 dashboard-2">
                            <div class="card-body">รายการคำสั่งซื้อที่รอจัดส่ง<h4>
                                    <?= $row4['order_wait'] ?>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small><a href="report_order_wait.php">
                                            <i class='bx bxs-car'></i> รอจัดส่งสินค้า</a></small></div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4 dashboard-3">
                            <div class="card-body">รายการคำสั่งซื้อที่จัดส่งสำเร็จ<h4>
                                    <?= $row3['order_yes'] ?>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small><a href="report_order_yes.php">
                                            <i class='bx bxs-wallet'></i> จัดส่งสำเร็จ</a></small></div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4 dashboard-4">
                            <div class="card-body">รายการที่ยกเลิกคำสั่งซื้อ<h4>
                                    <?= $row2['order_cancel'] ?>
                                </h4>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small><a href="report_order_no.php">
                                            <i class='bx bxs-error-alt'></i> ยกเลิกคำสั่งซื้อ</a></small>
                                </div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <?php include('report_tutorial.php'); ?>
                        <i class='bx bxs-x-circle'></i>
                        แสดงข้อมูลการสั่งซื้อสินค้า (ยกเลิกคำสั่งซื้อ)

                        <?php include('report_button.php'); ?>

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
                                <th>สถานะ</th>
                                <th>จัดการข้อมูล</th>
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
                            </tr>
                        </tfoot>

                        <tbody>
                            <?php
                            $sql = "SELECT *
                                 FROM tb_order as t
                                 JOIN tb_member as tm ON t.member_id = tm.id
                                 WHERE t.order_status = 0
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
                                                echo "<b style='color: green;'>ชำระเงินแล้ว</b>";
                                            } else if ($status == 0) {
                                                echo "<strong style='color: #ee2c4a;'><i class='bx bxs-x-circle'></i>&nbsp;ยกเลิกคำสั่งซื้อ</strong>";
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <button class="btn" style="background: linear-gradient(195deg, #30b566 0%, #30b566 100%); color: #fff; border: none; border-radius: 0.25rem;"
                                                onclick="showOrderDetail('<?= $row['orderID'] ?>')">
                                                <i class='bx bx-message-detail'></i>
                                            </button>
                                            <a class="btn" href="javascript:void(0);" style="background: linear-gradient(195deg, #dc3545 0%, #e35866 100%); color: #fff;"
                                                onclick="confirmDelete('<?= $row['orderID'] ?>')"><i
                                                    class='bx bx-trash'></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            mysqli_close($conn);
                            ?>
                        </tbody>

                    </table>
                    <div class="my-5">
                        <a href="report_order.php" class="btn btn-dark">ย้อนกลับ</a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function showOrderDetail(orderID) {
            $.ajax({
                url: 'fetch_order_detail.php',
                type: 'GET',
                data: {
                    id: orderID
                },
                success: function(data) {
                    $('#modalContent').html(data);
                    // Show the modal
                    $('#orderDetailModal').modal('show');
                },
                error: function() {
                    alert('เกิดข้อผิดพลาดในการดึงข้อมูลคำสั่งซื้อ');
                }
            });
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Modal Structure -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailModalLabel">รายละเอียดคำสั่งซื้อ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalContent">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
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

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: "คุณแน่ใจหรือไม่?",
            text: "คุณต้องการลบคำสั่งซื้อนี้หรือไม่?",
            footer: '<span style="color: #ee2c4a;">**โปรดตรวจสอบข้อมูลให้ถูกต้องก่อนทำการลบ</span>',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#0d6efd",
            cancelButtonColor: "#d33",
            confirmButtonText: "ลบ",
            cancelButtonText: "ยกเลิก"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `delete_order.php?id=${id}`;
            }
        });
    }
</script>


<?php
if (isset($_SESSION['cancelOrder'])) {
?>
    <script>
        Swal.fire({
            icon: "success",
            title: "ยกเลิกคำสั่งซื้อสำเร็จ",
            footer: '<span style="color: #00c300;">ยกเลิกคำสั่งซื้อสำเร็จ!</span>',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
<?php
    unset($_SESSION['cancelOrder']);
}
?>
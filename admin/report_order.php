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

    <!-- Jquery datepicker -->
    <link href="https://cdn.jsdelivr.net/npm/mc-datepicker/dist/mc-calendar.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/mc-datepicker/dist/mc-calendar.min.js"></script>

    <!-- css style -->
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
                        <i class='bx bxs-time-five'></i>
                        แสดงข้อมูลการสั่งซื้อสินค้าที่รอตรวจสอบการชำระเงิน

                        <div class="mt-3 mb-3">
                            <a href="report_order.php"><button type="button"
                                    class="btn" style="background: linear-gradient(195deg, #eda500 0%, #f69113 100%); color: #fff;"><i class='bx bxs-time-five'></i>&nbsp;ยังไม่ชำระเงิน</button></a>
                            <a href="report_order_wait.php"><button type="button"
                                    class="btn" style="background: linear-gradient(195deg, #ee4d2d 0%, #ff7337 100%); color: #fff;"><i class='bx bxs-car'></i>&nbsp;รอจัดส่ง</button></a>
                            <a href="report_order_yes.php"><button type="button"
                                    class="btn" style="background: linear-gradient(195deg, #20c997 0%, #198754 100%); color: #fff;"><i class='bx bxs-check-circle'></i>&nbsp;จัดส่งเรียบร้อย</button></a>
                            <a href="report_order_no.php"><button type="button"
                                    class="btn" style="background: linear-gradient(195deg, #dc3545 0%, #e35866 100%); color: #fff;"><i class='bx bxs-x-circle'></i>&nbsp;ยกเลิกการสั่งซื้อ</button></a>
                        </div>

                        <form name="form1" action="report_order.php" method="POST">
                            <div class="row">
                                <div class="col-sm-3">
                                    <input type="text" name="dt1" id="datepicker" class="form-control"
                                        placeholder="ค้นหาตั้งแต่วันที่" readonly>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="dt2" id="datepicker2" class="form-control"
                                        placeholder="ถึงวันที่ ..." readonly>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-block" style="background: linear-gradient(195deg, #212529 0%, #212529 100%); color: #fff;">
                                        <i class='bx bx-search-alt'></i> ค้นหา
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-striped">
                        <thead>
                            <tr>
                                <th>เลขที่ใบสั่งซื้อ</th>
                                <th>ชื่อลูกค้า</th>
                                <th>รวมสุทธิ</th>
                                <th>วันที่สั่งซื้อ</th>
                                <th>สถานะ</th>
                                <th>รายละเอียด</th>
                                <th>ปรับสถานะ</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $ddt1 = @$_POST['dt1'];
                            $ddt2 = @$_POST['dt2'];

                            if ((!empty($ddt1)) && (!empty($ddt2))) {
                                echo '<div class="alert" style="background: linear-gradient(195deg, #dee2e6 0%, #dee2e6 100%); color: #333; border: none; outline: none;">';
                                echo "<strong>ค้นหาตั้งแต่วันที่</strong> <h4>" . $ddt1 . " ถึงวันที่ " . $ddt2 . "</h4>";
                                echo '</div>';

                                $date1 = date('Y-m-d', strtotime($ddt1));
                                $date2 = date('Y-m-d', strtotime($ddt2));

                                $sql = "SELECT *
                                    FROM tb_order o
                                    JOIN tb_member m ON o.member_id = m.id
                                    WHERE o.order_status = '1' AND o.reg BETWEEN '$date1' AND '$date2'
                                    ORDER BY o.reg DESC";
                            } else {
                                $sql = "SELECT *
                                    FROM tb_order o
                                    JOIN tb_member m ON o.member_id = m.id
                                    WHERE o.order_status = '1'
                                    ORDER BY o.reg DESC";
                            }

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
                                        <span>
                                            <?php echo number_format($row['total_price'], 2); ?>
                                        </span>


                                    </td>
                                    <td>
                                        <?= $row['reg'] ?>
                                    </td>
                                    <td>

                                        <?php
                                        if ($status == 1) {
                                            echo "<span><i class='bx bxs-time'></i>&nbsp;รอตรวจสอบ</span>";
                                        } else if ($status == 2) {
                                            echo "<b style='color: #28a745;'>ชำระเงินแล้ว</b>";
                                        } else if ($status == 0) {
                                            echo "<b style='color: #dc3545;'>ยกเลิกการสั่งซื้อ</b>";
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <button class="btn" style="background: linear-gradient(195deg, #0d6efd 0%, #0a58ca 100%); color: #fff; border: none; border-radius: 0.25rem;"
                                            onclick="showOrderDetail('<?= $row['orderID'] ?>')">
                                            <i class='bx bx-message-detail'></i>
                                        </button>
                                    </td>

                                    <td>

                                        <a class="btn" style="background: linear-gradient(195deg, #20c997 0%, #198754 100%); color: #fff; border: none; border-radius: 0.25rem; text-decoration: none;" href="javascript:void(0);"
                                            onclick="confirmOrder('<?= $row['orderID'] ?>')"><i class='bx bxs-car'></i></a>
                                        <a class="btn" style="background: linear-gradient(195deg, #dc3545 0%, #dc3545 100%); color: #fff; border: none; border-radius: 0.25rem; text-decoration: none;" href="javascript:void(0);"
                                            onclick="confirmCancelOrder('<?= $row['orderID'] ?>')"><i class='bx bxs-x-circle'></i></a>

                                    </td>
                                </tr>
                            <?php
                            }
                            mysqli_close($conn);
                            ?>


                        </tbody>

                    </table>
                    <div class="my-5">
                        <a href="index.php" class="btn btn-dark">ย้อนกลับ</a>
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
    const datePicker = MCDatepicker.create({
        el: '#datepicker',
        bodyType: 'modal',
        maxDate: new Date(),
        customWeekDays: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.']
        // customMonths: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม']
    });
</script>
<script>
    const datePicker2 = MCDatepicker.create({
        el: '#datepicker2',
        bodyType: 'modal',
        customWeekDays: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.']
        // customMonths: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
    });
</script>

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

<script>
    function confirmOrder(id) {
        Swal.fire({
            title: "ปรับสถานะคำสั่งซื้อ",
            text: "คุณต้องการเปลี่ยนสถานะคำสั่งซื้อนี้เป็น 'รอจัดส่ง' หรือไม่?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `adjust_order.php?id=${id}`;
            }
        });
    }
</script>
<script>
    function confirmCancelOrder(id) {
        Swal.fire({
            title: "ยกเลิกคำสั่งซื้อ",
            text: "คุณต้องการยกเลิกคำสั่งซื้อนี้หรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `cancel_order.php?id=${id}`;
            }
        });
    }
</script>


<?php
if (isset($_SESSION['delete_order'])) {
?>
    <script>
        Swal.fire({
            icon: "success",
            title: "ลบสำเร็จ!",
            text: "คำสั่งซื้อนี้ถูกลบเรียบร้อยแล้ว.",
            showConfirmButton: false,
            timer: 1000
        });
    </script>
<?php
    unset($_SESSION['delete_order']);
}
?>
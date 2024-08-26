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

    <!-- Jquery datepicker -->
    <link href="https://cdn.jsdelivr.net/npm/mc-datepicker/dist/mc-calendar.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/mc-datepicker/dist/mc-calendar.min.js"></script>

    <!-- css style -->
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
                        <div class="card text-white mb-4"
                            style="background: linear-gradient(195deg, #42424a 0%, #191919 100%);   ">
                            <div class="card-body">รายการสินค้าทั้งหมด<h4>
                                    <?= $row4['all_pd'] ?>
                                </h4>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small><a href="prd_show_product.php"
                                            style="text-decoration: none; color: white; font-size: 13px;">
                                            <i class='bx bxs-store' style="color: #fff; background: rgba(255, 255, 255, 0.3); padding: 7px; border-radius: 50%; font-size: 20px;"></i> สินค้าทั้งหมด</a></small></div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4"
                            style="background: linear-gradient(195deg, #fb8be9 0%, #D81B60 100%);">
                            <div class="card-body">รายการสั่งซื้อสินค้า (ยังไม่ชำระเงิน)<h4>
                                    <?= $row['order_no'] ?>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small><a href="report_order.php"
                                            style="text-decoration: none; color: white; font-size: 13px;">
                                            <i class='bx bxs-error-alt' style="color: #fff; background: rgba(255, 255, 255, 0.3); padding: 7px; border-radius: 50%; font-size: 20px;"></i> ยังไม่ชำระเงิน</a></small></div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4"
                            style="background: linear-gradient(195deg, #58d68d 0%, #43A047 100%);">
                            <div class="card-body">รายการสั่งซื้อสินค้า (ชำระเงินแล้ว)<h4>
                                    <?= $row3['order_yes'] ?>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small><a href="report_order_yes.php"
                                            style="text-decoration: none; color: white; font-size: 13px;">
                                            <i class='bx bxs-wallet' style="color: #fff; background: rgba(255, 255, 255, 0.3); padding: 7px; border-radius: 50%; font-size: 20px;"></i> ชำระเงินแล้ว</a></small></div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card text-white mb-4"
                            style="background: linear-gradient(195deg, #71cdf5 0%, #1A73E8 100%);">
                            <div class="card-body">รายการสั่งซื้อสินค้า (ยกเลิก) 10 ชิ้น<h4>
                                    <?= $row2['order_cancel'] ?>
                                </h4>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small><a href="report_order_no.php"
                                            style="text-decoration: none; color: white; font-size: 13px;">
                                            <i class='bx bxs-error-alt' style="color: #fff; background: rgba(255, 255, 255, 0.3); padding: 7px; border-radius: 50%; font-size: 20px;"></i> ยกเลิกคำสั่งซื้อ</a></small>
                                </div>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        แสดงข้อมูลการสั่งซื้อสินค้าที่รอการชำระเงิน

                        <div class="mt-3 mb-3">
                            <a href="report_order_yes.php"><button type="button"
                                    class="btn" style="background: linear-gradient(195deg, #198754 0%, #20c997 100%); color: #fff;"><i class='bx bx-check-circle'></i>&nbsp;ชำระเงินแล้ว</button></a>
                            <a href="report_order.php"><button type="button"
                                    class="btn" style="background: linear-gradient(195deg, #fd7e14 0%, #ffc107 100%); color: #fff;"><i class='bx bxs-time-five'></i>&nbsp;ยังไม่ชำระเงิน</button></a>
                            <a href="report_order_no.php"><button type="button"
                                    class="btn" style="background: linear-gradient(195deg, #dc3545 0%, #e35866 100%); color: #fff;"><i class='bx bx-x-circle'></i>&nbsp;ยกเลิกการสั่งซื้อ</button></a>
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
                                    <button type="submit" class="btn btn-block" style="background: linear-gradient(195deg, #0a58ca 0%, #0d6efd 100%); color: #fff;">
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
                                <th>ลูกค้า</th>
                                <!-- <th>ที่อยู่จัดส่งสินค้า</th> -->
                                <!-- <th>เบอร์โทรศัพท์</th> -->
                                <th>ราคารวมสุทธิ</th>
                                <th>วันที่สั่งซื้อ</th>
                                <th>สถานะ</th>
                                <th>รายละเอียด</th>
                                <th>ปรับสถานะ</th>
                                <!-- <th>ลบคำสั่งซื้อ</th> -->
                            </tr>
                        </thead>
                        <!-- search fill -->
                        <!-- <tfoot>
                                <tr>
                                    <th>orderID</th>
                                    <th>cus_name</th>
                                    <th>address</th>
                                    <th>telephone</th>
                                    <th>telephone</th>
                                    <th>telephone</th>
                                </tr>
                            </tfoot> -->

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
                                    <!-- <td>
                                        <?= $row['address'] ?>
                                    </td>
                                    <td>
                                        <?= $row['telephone'] ?>
                                    </td> -->
                                    <td>
                                        <span>
                                            <?php echo number_format($row['total_price'], 2); ?>
                                        </span>


                                    </td>
                                    <td>
                                        <?= $row['reg'] ?>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <?php
                                            if ($status == 1) {
                                                echo "<div class='' style='border-radius: 7px; background: linear-gradient(195deg, #eda500 0%, #ffd556 100%); color: #FFF; padding: 4px 0;'>
                                                    <b>รอตรวจสอบ</b>
                                                  </div>";
                                            } else if ($status == 2) {
                                                echo "<b style='color: green;'>ชำระเงินแล้ว</b>";
                                            } else if ($status == 0) {
                                                echo "<b style='color: red;'>ยกเลิกการสั่งซื้อ</b>";
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <a href="report_order_detail.php?id=<?= $row['orderID'] ?>"
                                                class="btn" style="background: linear-gradient(195deg, #0d6efd 0%, #0046ab 100%);  color: #fff;"><i class='bx bx-message-detail'></i></a>
                                        </div>
                                    <td>
                                        <div class="text-center">
                                            <a class="btn" style="background: linear-gradient(195deg, #198754 0%, #20c997 100%); color: #fff;" href="javascript:void(0);"
                                                onclick="confirmOrder('<?= $row['orderID'] ?>')"><i class='bx bx-check-circle'></i></a>
                                            <a class="btn" style="background: linear-gradient(195deg, #dc3545 0%, #df6a76 100%); color: #fff;" href="javascript:void(0);"
                                                onclick="confirmCancelOrder('<?= $row['orderID'] ?>')"><i class='bx bx-rotate-right'></i></a>
                                        </div>
                                    </td>
                                    <!-- <td>
                                            <div class="text-center">
                                            <a class="btn btn-primary" href="javascript:void(0);"
                                                onclick="confirmDelete('<?= $row['orderID'] ?>')"><i
                                                    class='bx bx-trash'></i></a>
                                            </div>
                                        </td> -->
                                </tr>
                            <?php
                            }
                            mysqli_close($conn);
                            ?>


                        </tbody>

                    </table>
                    <div class="my-5">
                        <a href="index.php" class="btn btn-primary">ย้อนกลับ</a>
                    </div>
                </div>
            </div>
    </div>
    </main>
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
            title: "Want to confirm your order?",
            text: "ต้องการยืนยันคำสั่งซื้อหรือไม่?",
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
            title: "Want to cancel the order?",
            text: "ต้องการยกเลิกคำสั่งซื้อหรือไม่?",
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
            text: "Successfully deleted.",
            showConfirmButton: false,
            timer: 1000
        });
    </script>
<?php
    unset($_SESSION['delete_order']);
}
?>
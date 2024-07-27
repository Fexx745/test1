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
                        <div class="card text-white mb-4" style="background: #dc3545;">
                            <div class="card-body">รายการสินค้าทั้งหมด<h4>
                                    <?= $row4['all_pd'] ?>
                                </h4>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <div><small style="font-size: 12px;"><a href="index.php"
                                            style="text-decoration: none; color: white;">
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
                                <div><small style="font-size: 12px;"><a href="report_order.php"
                                            style="text-decoration: none; color: white;">
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
                                <div><small style="font-size: 12px;"><a href="report_order_yes.php"
                                            style="text-decoration: none; color: white;">
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
                                <div><small style="font-size: 12px;"><a href="report_order_no.php"
                                            style="text-decoration: none; color: white;">
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
                        แสดงข้อมูลการสั่งซื้อสินค้าที่ (ยังไม่ได้ชำระเงิน)

                        <div class="mt-3 mb-3">
                            <a href="report_order_yes.php"><button type="button"
                                    class="btn" style="background: #198754; color: #fff;"><i class='bx bx-check-circle' ></i>&nbsp;ชำระเงินแล้ว</button></a>
                            <a href="report_order.php"><button type="button"
                                    class="btn" style="background: #6f42c1; color: #fff;"><i class='bx bxs-hourglass' ></i>&nbsp;ยังไม่ชำระเงิน</button></a>
                            <a href="report_order_no.php"><button type="button"
                                    class="btn" style="background: #dc3545; color: #fff;"><i class='bx bx-rotate-right' ></i>&nbsp;ยกเลิกการสั่งซื้อ</button></a>
                        </div>

                        <div>
                            <form name="form1" action="report_order.php" method="POST">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <input type="text" name="dt1" id="datepicker" class="form-control"
                                            placeholder="ค้าหาตั้งแต่วันที่" readonly>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="dt2" id="datepicker2" class="form-control"
                                            placeholder="ถึงวันที่ ..." readonly>
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-primary"><i class='bx bx-search-alt' ></i></button>
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
                                    <th>ที่อยู่จัดส่งสินค้า</th>
                                    <th>เบอร์โทรศัพท์</th>
                                    <th>ราคารวมสุทธิ</th>
                                    <th>วันที่สั่งซื้อ</th>
                                    <th>รอการตรวจสอบ</th>
                                    <th>รายละเอียด</th>
                                    <th>ปรับสถานะ</th>
                                    <th>ยกเลิกการสั่งซื้อ</th>
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
                                    echo '<div class="alert alert-secondary">';
                                    echo "<strong>ค้นหาตั้งแต่วันที่</strong> " . $ddt1 . " <strong>ถึงวันที่</strong> " . $ddt2;
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
                                            <?= $row['address'] ?>
                                        </td>
                                        <td>
                                            <?= $row['telephone'] ?>
                                        </td>
                                        <td>
                                            <span style="color: black; font-weight: bold;">
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
                                                echo "<div class='' style='border-radius: 100px; background: #a272fa; color: #fff; padding: 5px;'><i class='bx bxs-hourglass' >รอตรวจสอบ</i></div>";
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
                                                class="btn" style="background: #f7d367;"><i class='bx bx-message-detail' ></i></a></td>
                                            </div>
                                        <td>
                                           <div class="text-center">
                                           <a class="btn btn-success" href="javascript:void(0);"
                                                onclick="confirmOrder('<?= $row['orderID'] ?>')"><i class='bx bx-check-circle' ></i></a>
                                           </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                            <a class="btn btn-danger" href="javascript:void(0);"
                                                onclick="confirmCancelOrder('<?= $row['orderID'] ?>')"><i class='bx bx-rotate-right' ></i></a>
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
                        <div class="mt-4" class="mb-2">
                            <a href="index.php" class="btn btn-primary">ย้อนกลับ</a>
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
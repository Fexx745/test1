<?php
include('condb.php');
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit(); // คำสั่งออกจากการทำงานทันทีหลังจาก redirect
}
$idpd = $_GET['id'];

// Fetch parcel_number from tb_order table
$query = "SELECT parcel_number FROM tb_order WHERE orderID = '$idpd'";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);
$parcel_number = $order['parcel_number'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- head content -->
    <script src="assets/dist/sweetalert2.all.min.js"></script>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.2.3/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="sb-nav-fixed">
    <?php include('menu.php'); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 mt-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class='bx bxs-time-five'></i>
                        แสดงรายละเอียดคำสั่งซื้อ
                        <div class="mt-3 mb-3">
                            <a href="report_order.php"><button type="button"
                                    class="btn" style="background: linear-gradient(195deg, #eda500 0%, #f69113 100%); color: #fff;"><i class='bx bxs-time-five'></i>&nbsp;ยังไม่ชำระเงิน</button></a>
                            <a href="report_order_wait.php"><button type="button"
                                    class="btn" style="background: linear-gradient(195deg, #ee4d2d 0%, #ff7337 100%); color: #fff;"><i class='bx bx-car'></i>&nbsp;รอจัดส่ง</button></a>
                            <a href="report_order_yes.php"><button type="button"
                                    class="btn" style="background: linear-gradient(195deg, #20c997 0%, #198754 100%); color: #fff;"><i class='bx bx-check-circle'></i>&nbsp;จัดส่งเรียบร้อย</button></a>
                            <a href="report_order_no.php"><button type="button"
                                    class="btn" style="background: linear-gradient(195deg, #dc3545 0%, #e35866 100%); color: #fff;"><i class='bx bx-x-circle'></i>&nbsp;ยกเลิกการสั่งซื้อ</button></a>
                        </div>
                    </div>
                    <div class="container">
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>รหัสสินค้า</th>
                                    <th>ชื่อสินค้า</th>
                                    <th>ราคา</th>
                                    <th>จำนวน</th>
                                    <th>ราคารวม</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM tb_order as t 
                                INNER JOIN tb_order_detail as d ON t.orderID = d.orderID 
                                INNER JOIN product as p ON d.p_id = p.p_id
                                INNER JOIN price_history as ph ON p.p_id = ph.p_id
                                INNER JOIN tb_payment as s ON t.orderID = s.orderID
                                INNER JOIN tb_member as tm ON tm.id = t.member_id
                                WHERE d.orderID = '$idpd'
                                ORDER BY p.p_id ASC";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                    $sum_total = $row['total_price'];
                                ?>
                                    <tr>
                                        <td><?= $row['p_id'] ?></td>
                                        <td><?= $row['p_name'] ?></td>
                                        <td><?= $row['price'] ?></td>
                                        <td><?= $row['orderQty'] ?></td>
                                        <td><?= $row['Total'] ?></td>
                                    </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#slipModal">
                            ดูหลักฐานการโอน
                        </button><br><br>
                        <div class="my-2">
                            <b>เลขคำสั่งซื้อ</b>
                            <span><?= $row['orderID'] ?></span>
                        </div>
                        <b>ที่อยู่ลูกค้า</b>
                        <span><?= $row['address'] ?></span>
                        <div class="my-2">
                            <b>เบอร์โทรศัพท์</b>
                            <span><?= $row['telephone'] ?></span>
                        </div>
                        <div class="my-4"><b>ราคารวมสุทธิ</b>
                            <span style="color: #ee4d2d; font-weight: bold; font-size: 18px;"><?= number_format($sum_total, 2) ?></span>
                            <b>บาท</b>
                        </div>
                        <div class="mb-3 mt-3">
                            <!-- Modal -->
                            <div class="modal fade" id="slipModal" tabindex="-1" aria-labelledby="slipModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="slipModalLabel">หลักฐานการโอน</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                            $imagePath = "../assets/images/slip_images/" . $row['slip_image'];
                                            $noImg = "../assets/images/no_img.png";
                                            if (file_exists($imagePath)) {
                                                echo "<img src=\"$imagePath\" alt=\"Slip Image\" class=\"img-fluid\">";
                                            } else {
                                                echo "<img src=\"$noImg\" alt=\"No Image\" class=\"img-fluid\">";
                                            }
                                            ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">ปิด</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <a href="report_order.php" class="btn btn-dark">ย้อนกลับ</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    <?php
                                }
                                mysqli_close($conn);
    ?>
    <?php include('footer.php'); ?>
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
    function del(mypage) {
        var agree = confirm('คุณต้องการยกเลิกใบสั่งซื้อสินค้าหรือไม่?');
        if (agree) {
            window.location = mypage;
        }
    }

    function adjust(mypage1) {
        var agree = confirm('คุณต้องการปรับสถานะการชำระเงินหรือไม่?');
        if (agree) {
            window.location = mypage1;
        }
    }
</script>

<?php
if (isset($_SESSION['success_message'])) {
?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "การแก้ไขคำสั่งซื้อเสร็จสิ้นแล้ว!",
            showConfirmButton: false,
            timer: 1500
        });
    </script>

<?php
    unset($_SESSION['success_message']);
}
?>
<?php
include ('condb.php');
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
</head>

<body class="sb-nav-fixed">
    <?php include ('menu.php'); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 mt-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        แสดงข้อมูลการสั่งซื้อสินค้าที่ (ยังไม่ได้ชำระเงิน)
                        <div class="mt-3 mb-3">
                            <a href="report_order_yes.php"><button type="button"
                                    class="btn btn-success">ชำระเงินแล้ว</button></a>
                            <a href="report_order.php"><button type="button"
                                    class="btn btn-primary">ยังไม่ชำระเงิน</button></a>
                            <a href="report_order_no.php"><button type="button"
                                    class="btn btn-danger">ยกเลิกการสั่งซื้อ</button></a>
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
                            <b>ราคารวมสุทธิ</b>
                            <span style="color: green; font-weight: bold;"><?= number_format($sum_total, 2) ?></span>
                            <b>บาท</b>
                            <div class="mb-3 mt-3">
                                <!-- <h5 class="alert alert-success"><i class='bx bxs-file-image' ></i> หลักฐานการโอน</h5> -->
                                <?php
                                $imagePath = "../assets/images/slip_images/" . $row['slip_image'];
                                $noImg = "../assets/images/no_img.png";
                                if (file_exists($imagePath)) {
                                    echo "<img src=\"$imagePath\" alt=\"\" style=\"margin: 15px 0 15px 0; width: 500px; height: 500px; object-fit: contain;\">";
                                } else {
                                    echo "<img src=\"$noImg\" alt=\"\" style=\"margin: 15px 0 15px 0; width: 500px; height: 500px; object-fit: contain;\">";
                                }
                                ?>
                            </div>
                            <form action="update_order.php" method="post">
                                <!-- ตัวแปร order_id ที่ถูกส่งมาจาก GET -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="hidden" name="order_id" value="<?php echo $idpd; ?>">
                                        <label for="shipping_type">ประเภทขนส่ง&nbsp;&gt;</label>
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="shipping_type"><i
                                                    class='bx bx-layer-plus'></i></label>
                                            <select name="shipping_type" id="shipping_type" class="form-select">
                                                <?php
                                                include ('condb.php');
                                                // Fetch shipping type for the current order
                                                $query = "SELECT shipping_type_id FROM tb_order WHERE orderID = '$idpd'";
                                                $result = mysqli_query($conn, $query);
                                                $shipping_type_id = mysqli_fetch_assoc($result)['shipping_type_id'];

                                                // Retrieve all shipping types
                                                $sql = "SELECT shipping_type_id, shipping_type_name FROM shipping_type ORDER BY shipping_type_id";
                                                $result = mysqli_query($conn, $sql);

                                                if (mysqli_num_rows($result) > 0) {
                                                    while ($rows = mysqli_fetch_array($result)) {
                                                        // Check if this option should be selected
                                                        $selected = ($shipping_type_id == $rows['shipping_type_id']) ? 'selected' : '';
                                                        echo "<option value='{$rows['shipping_type_id']}' $selected>{$rows['shipping_type_name']}</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>No shipping types found</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="parcel_number">เลขพัสดุ&nbsp;&gt;</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class='bx bx-book-reader'></i></span>
                                            <input type="text" class="form-control" name="parcel_number"
                                                placeholder="กรอกเลขพัสดุ" value="<?php echo $parcel_number; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <a href="report_order.php" class="btn btn-warning">ย้อนกลับ</a>
                                    <button type="submit" class="btn btn-success">ยืนยัน</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <?php
                                }
                                mysqli_close($conn);
                                ?>
        <?php include ('footer.php'); ?>
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
            title: "แก้ไขสำเร็จ!",
            text: "คำสั่งซื้อถูกอัพเดทแล้ว",
            showConfirmButton: false,
            timer: 1500
        });
    </script>

    <?php
    unset($_SESSION['success_message']);
}
?>
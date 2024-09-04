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
                        <?php include('report_tutorial.php'); ?>
                        <i class='bx bxs-time-five'></i>
                        แสดงรายละเอียดคำสั่งซื้อ
                        <div class="mt-3 mb-3">
                            <a href="report_order.php"><button type="button"
                                    class="btn" style="background: linear-gradient(195deg, #eda500 0%, #f69113 100%); color: #fff;"><i class='bx bxs-time-five'></i>&nbsp;ยังไม่ชำระเงิน</button></a>
                            <a href="report_order_wait.php"><button type="button"
                                    class="btn" style="background: linear-gradient(195deg, #ee4d2d 0%, #ff7337 100%); color: #fff;"><i class='bx bxs-truck'></i>&nbsp;รอจัดส่ง</button></a>
                            <a href="report_order_yes.php"><button type="button"
                                    class="btn" style="background: linear-gradient(195deg, #20c997 0%, #198754 100%); color: #fff;"><i class='bx bxs-check-circle'></i>&nbsp;จัดส่งเรียบร้อย</button></a>
                            <a href="report_order_no.php"><button type="button"
                                    class="btn" style="background: linear-gradient(195deg, #dc3545 0%, #e35866 100%); color: #fff;"><i class='bx bxs-x-circle'></i>&nbsp;ยกเลิกการสั่งซื้อ</button></a>
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
                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#slipModal"
                        style="background: linear-gradient(195deg, #fff3cd 0%, #fff3cd 100%); color: #333; border: 1px solid #e5e5e5; border-radius: 0.25rem;">หลักฐานการโอน
                        </button>

                        <br><br>
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
                            <span style="color: #26aa99; font-weight: bold; font-size: 18px;"><?= number_format($sum_total, 2) ?></span>
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
                        <form action="update_order.php" method="post">
                            <!-- ตัวแปร order_id ที่ถูกส่งมาจาก GET -->
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="hidden" name="order_id" value="<?php echo $idpd; ?>">
                                    <div class="col-md-6">
                                        <label for="shipping_type">ประเภทขนส่ง&nbsp;&gt;</label>
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="shipping_type"><i class='bx bxs-truck'></i></label>
                                            <select name="shipping_type" id="shipping_type" class="form-select">
                                                <?php
                                                include('condb.php');
                                                $query = "SELECT shipping_type_id FROM tb_order WHERE orderID = '$idpd'";
                                                $result = mysqli_query($conn, $query);
                                                $shipping_type_id = mysqli_fetch_assoc($result)['shipping_type_id'];

                                                $sql = "SELECT shipping_type_id, shipping_type_name FROM shipping_type ORDER BY shipping_type_id";
                                                $result = mysqli_query($conn, $sql);

                                                if (mysqli_num_rows($result) > 0) {
                                                    while ($rows = mysqli_fetch_array($result)) {
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
                                            <span class="input-group-text"><i class='bx bx-barcode'></i></span>
                                            <input type="text" class="form-control" name="parcel_number"
                                                placeholder="กรอกเลขพัสดุ" value="<?php echo $parcel_number; ?>">
                                        </div>
                                    </div>

                                </div>
                                <div class="mb-2">
                                    <a href="report_order_wait.php" class="btn btn-dark">ย้อนกลับ</a>
                                    <button type="submit" class="btn btn-danger">ยืนยันการจัดส่ง</button>
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

<!-- <script>
    function confirmOrder(id) {
        Swal.fire({
            title: "ยืนยันการเปลี่ยนสถานะคำสั่งซื้อ",
            text: "คุณต้องการเปลี่ยนสถานะคำสั่งซื้อนี้เป็น 'จัดส่งสำเร็จ' หรือไม่?",
            footer: '<span style="color: #ee2c4a;">**โปรดตรวจสอบเลขพัสดุให้ถูกต้องก่อนยืนยัน</span>',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#198754",
            cancelButtonColor: "#d33",
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `update_order.php`;
            }
        });
    }
</script> -->
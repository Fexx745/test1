<?php
session_start();
include 'condb.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['orderID'])) {
    header("Location: view_orders.php"); // Redirect back to orders page if no orderID is provided
    exit;
}

$orderID = intval($_GET['orderID']);

$sql = "SELECT tb_order.orderID, tb_order.reg as order_date, tb_order.total_price, tb_order.order_status, tb_order.parcel_number,
               tb_order_detail.p_id, tb_order_detail.orderQty, tb_order_detail.Total,
               product.p_name, product.image,  /* เพิ่มการดึงข้อมูลชื่อไฟล์รูปภาพ */
               shipping_type.shipping_type_name
        FROM tb_order
        JOIN tb_order_detail ON tb_order.orderID = tb_order_detail.orderID
        JOIN product ON tb_order_detail.p_id = product.p_id
        LEFT JOIN shipping_type ON tb_order.shipping_type_id = shipping_type.shipping_type_id
        WHERE tb_order.orderID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderID);
$stmt->execute();
$result = $stmt->get_result();

$order_details = [];
while ($row = $result->fetch_assoc()) {
    if (!isset($order_details['order_date'])) {
        $order_details['order_date'] = $row['order_date'];
        $order_details['total_price'] = $row['total_price'];
        $order_details['order_status'] = $row['order_status'];
        $order_details['parcel_number'] = $row['parcel_number'];
        $order_details['shipping_type_name'] = $row['shipping_type_name'];
    }
    $order_details['items'][] = [
        'product_name' => $row['p_name'],
        'orderQty' => $row['orderQty'],
        'Total' => $row['Total']
    ];
}

function getOrderStatus($status)
{
    switch ($status) {
        case '0':
            return 'ยกเลิก';
        case '1':
            return 'รอตรวจสอบ';
        case '2':
            return 'ชำระเงินแล้ว';
        default:
            return 'สถานะไม่ทราบ';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <?php include ('script-css.php'); ?>
</head>

<body>
    <?php include ('nav.php'); ?>

    <div class="body-container">

        <?php include ('bc-menu.php'); ?>

        <div class="view-history-menu">
            <div class="col-mb-12 mt-2" style="margin-bottom: 20px;">
                <h3><i class='bx bx-receipt'></i>&nbsp;รายละเอียดการสั่งซื้อ</h3>
            </div>
            <?php if (empty($order_details)): ?>
                <p>ไม่พบรายละเอียดการสั่งซื้อ</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ชื่อสินค้า</th>
                                <th scope="col">จำนวน</th>
                                <th scope="col">รวม (บาท)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_details['items'] as $item): ?>
                                <tr>
                                    <td><img src="assets/images/product/<?= $row['image'] ?>"></td>

                                    <td><?php echo $item['product_name']; ?></td>
                                    <td><?php echo $item['orderQty']; ?></td>
                                    <td>฿<?php echo $item['Total']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="order-summary">
                    <p><strong>เลขที่ใบสั่งซื้อ:</strong> <?php echo str_pad($orderID, 10, '0', STR_PAD_LEFT); ?></p>
                    <p><strong>วันที่สั่งซื้อ:</strong> <?php echo $order_details['order_date']; ?></p>
                    <p><strong>ยอดรวม:</strong> ฿<?php echo $order_details['total_price']; ?></p>
                    <p><strong>สถานะ:</strong> <?php echo getOrderStatus($order_details['order_status']); ?></p>
                    <p><strong>เลขพัสดุ:</strong> <?php echo $order_details['parcel_number'] ?: 'รอจัดส่ง'; ?></p>
                    <p><strong>ประเภทขนส่ง:</strong> <?php echo $order_details['shipping_type_name']; ?></p>
                </div>
            <?php endif; ?>
            <div class="previous-button">
                <a href="view-order-history.php" class="btn btn-dark">ย้อนกลับ</a>
            </div>

        </div> <!-- end view-order-details -->
    </div> <!-- body-container -->

    <?php include ('footer.php'); ?>

</body>

</html>

<?php include ('script-js.php'); ?>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
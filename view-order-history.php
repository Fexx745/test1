<?php
session_start();
include 'condb.php'; // ไฟล์ condb.php จะมีการเชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบแล้วหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลการสั่งซื้อของผู้ใช้จากฐานข้อมูล
$sql = "SELECT tb_order.orderID, tb_order.reg as order_date, tb_order.total_price, tb_order.order_status, tb_order.parcel_number,
               tb_order_detail.p_id, tb_order_detail.orderQty, tb_order_detail.Total,
               product.p_name,
               shipping_type.shipping_type_name
        FROM tb_order
        JOIN tb_order_detail ON tb_order.orderID = tb_order_detail.orderID
        JOIN product ON tb_order_detail.p_id = product.p_id
        LEFT JOIN shipping_type ON tb_order.shipping_type_id = shipping_type.shipping_type_id
        WHERE tb_order.member_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$order_history = [];
while ($row = $result->fetch_assoc()) {
    $order_history[$row['orderID']]['order_date'] = $row['order_date'];
    $order_history[$row['orderID']]['total_price'] = $row['total_price'];
    $order_history[$row['orderID']]['order_status'] = $row['order_status'];
    $order_history[$row['orderID']]['parcel_number'] = $row['parcel_number'];
    $order_history[$row['orderID']]['shipping_type_name'] = $row['shipping_type_name'];
    $order_history[$row['orderID']]['items'][] = [
        'product_name' => $row['p_name'],
        'orderQty' => $row['orderQty'],
        'Total' => $row['Total']
    ];
}

function getOrderStatus($status) {
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
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการสั่งซื้อ</title>
    <?php include ('script-css.php'); ?>
    <style>
        body {
            background-color: #f8f9fa; /* สีพื้นหลัง */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* แบบอักษร */
            color: #212529; /* สีตัวอักษรหลัก */
            padding-top: 20px; /* ระยะห่างด้านบนของเนื้อหา */
        }
        .container {
            max-width: 800px; /* ความกว้างสูงสุดของเนื้อหา */
        }
        .table {
            margin-bottom: 20px; /* ระยะห่างด้านล่างของตาราง */
            border-collapse: collapse;
        }
        .table th, .table td {
            vertical-align: middle; /* การจัดการตำแหน่งของข้อความในเซลล์ */
            border: 1px solid black;
            padding: 5px;

        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="mb-4">ประวัติการสั่งซื้อ</h1>
    <?php if (empty($order_history)): ?>
        <p>ยังไม่มีประวัติการสั่งซื้อ</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">เลขที่ใบสั่งซื้อ</th>
                        <th scope="col">วันที่สั่งซื้อ</th>
                        <th scope="col">ยอดรวม (บาท)</th>
                        <th scope="col">สถานะ</th>
                        <th scope="col">เลขพัสดุ</th>
                        <th scope="col">ประเภทขนส่ง</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_history as $orderID => $order): ?>
                        <tr>
                            <td><?php echo $orderID; ?></td>
                            <td><?php echo $order['order_date']; ?></td>
                            <td><?php echo $order['total_price']; ?></td>
                            <td><?php echo getOrderStatus($order['order_status']); ?></td>
                            <td><?php echo $order['parcel_number'] ?: 'รอจัดส่ง'; ?></td>
                            <td><?php echo $order['shipping_type_name']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="index.php" class="btn btn-primary">ย้อนกลับ</a>
</div>
<script src="path/to/bootstrap.js"></script> <!-- ปรับเส้นทางไฟล์ JavaScript ของ Bootstrap -->
</body>
</html>

<?php include ('script-js.php'); ?>
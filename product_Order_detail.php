<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
} else if ($_SESSION['status'] !== '0') {
    header('Location: login.php');
    exit();
}

include 'condb.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['orderID'])) {
    header("Location: view_orders.php");
    exit;
}

$orderID = intval($_GET['orderID']);

$sql = "SELECT tb_order.orderID, tb_order.reg as order_date, tb_order.total_price, tb_order.order_status, tb_order.parcel_number, tb_order.annotation,
               tb_order_detail.p_id, tb_order_detail.orderQty, tb_order_detail.Total,
               product.p_name, product.image, 
               shipping_type.shipping_type_name,
               tb_payment.slip_image  /* Retrieve slip image from tb_payment table */
        FROM tb_order
        JOIN tb_order_detail ON tb_order.orderID = tb_order_detail.orderID
        JOIN product ON tb_order_detail.p_id = product.p_id
        LEFT JOIN shipping_type ON tb_order.shipping_type_id = shipping_type.shipping_type_id
        LEFT JOIN tb_payment ON tb_order.orderID = tb_payment.orderID  /* Join tb_payment table */
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
        $order_details['annotation'] = $row['annotation'];
        $order_details['shipping_type_name'] = $row['shipping_type_name'];
        $order_details['slip_image'] = $row['slip_image'];  /* Store the slip image file path */
    }
    $order_details['items'][] = [
        'product_name' => $row['p_name'],
        'orderQty' => $row['orderQty'],
        'Total' => $row['Total'],
        'image' => $row['image']
    ];
}

function getOrderStatus($status)
{
    switch ($status) {
        case '0':
            return 'ยกเลิกสั่งซื้อ';
        case '1':
            return 'รอตรวจสอบ';
        case '2':
            return 'จัดส่งสำเร็จ';
        case '3':
            return 'รอจัดส่งสินค้า';
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
    <title>SHOP | ซื้อขายผ่านเว็บไซต์ออนไลน์</title>
    <?php include('script-css.php'); ?>
</head>

<body>
    <?php include('nav.php'); ?>

    <div class="body-container">

        <?php include('index_Menu.php'); ?>

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
                                    <td><img src="assets/images/product/<?= htmlspecialchars($item['image']); ?>"
                                            alt="Product Image" style="width: 50px; height: 70px; object-fit: cover;"></td>
                                    <td><?php echo $item['product_name']; ?></td>
                                    <td><?php echo $item['orderQty']; ?></td>
                                    <td>฿<?php echo $item['Total']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="order-summary">
                    <p><strong><i class='bx bx-receipt'></i> เลขที่ใบสั่งซื้อ:</strong> <?php echo str_pad($orderID, 10, '0', STR_PAD_LEFT); ?></p>
                    <p><strong><i class='bx bx-calendar'></i> วันที่สั่งซื้อ:</strong> <?php echo $order_details['order_date']; ?></p>
                    <p><strong><i class='bx bx-dollar'></i> ยอดรวม:</strong> ฿<?php echo $order_details['total_price']; ?></p>
                    <p><strong><i class='bx bxs-info-circle'></i> สถานะ:</strong> <?php echo getOrderStatus($order_details['order_status']); ?></p>
                    <p><strong><i class='bx bx-package'></i> เลขพัสดุ:</strong> <?php echo $order_details['parcel_number'] ?: 'รอจัดส่ง'; ?></p>
                    <p><strong><i class='bx bxs-truck'></i> ประเภทขนส่ง:</strong> <?php echo $order_details['shipping_type_name']; ?></p>
                    <p><strong><i class='bx bx-note'></i> หมายเหตุ:</strong> <?php echo $order_details['annotation']; ?></p>

                    <?php if (!empty($order_details['slip_image'])): ?>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#slipModal">
                            <i class='bx bx-receipt'></i> หลักฐานการชำระเงิน
                        </button>
                    <?php else: ?>
                        <p>ไม่มีสลิปการชำระเงิน</p>
                    <?php endif; ?>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="slipModal" tabindex="-1" role="dialog" aria-labelledby="slipModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="slipModalLabel">สลิปการชำระเงินของคุณ</h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body d-flex justify-content-center align-items-center" style="height: 700px;">
                                <img src="assets/images/slip_images/<?php echo htmlspecialchars($order_details['slip_image']); ?>" alt="Slip Image" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            </div>
                            <div class="modal-footer">
                                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button> -->
                            </div>
                        </div>
                    </div>
                </div>


            <?php endif; ?>
            <div class="previous-button">
                <a href="product_View_Order.php" class="btn btn-dark">ย้อนกลับ</a>
            </div>

        </div> <!-- end view-order-details -->
    </div> <!-- body-container -->

    <?php include('footer.php'); ?>

</body>

</html>

<?php include('script-js.php'); ?>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
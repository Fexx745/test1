<?php
session_start();
include 'condb.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
} else if ($_SESSION['status'] !== '0') {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$orders_per_page = 11;

$sql_total_orders = "SELECT COUNT(*) FROM tb_order WHERE member_id = ?";
$stmt_total = $conn->prepare($sql_total_orders);
$stmt_total->bind_param("i", $user_id);
$stmt_total->execute();
$stmt_total->bind_result($total_orders);
$stmt_total->fetch();
$stmt_total->close();

$total_pages = ceil($total_orders / $orders_per_page);

$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages) $current_page = $total_pages;

$offset = ($current_page - 1) * $orders_per_page;

$sql = "SELECT tb_order.orderID, tb_order.reg as order_date, tb_order.total_price, tb_order.order_status, tb_order.parcel_number,
               tb_order_detail.p_id, tb_order_detail.orderQty, tb_order_detail.Total,
               product.p_name,
               shipping_type.shipping_type_name
        FROM tb_order
        JOIN tb_order_detail ON tb_order.orderID = tb_order_detail.orderID
        JOIN product ON tb_order_detail.p_id = product.p_id
        LEFT JOIN shipping_type ON tb_order.shipping_type_id = shipping_type.shipping_type_id
        WHERE tb_order.member_id = ?
        ORDER BY tb_order.reg DESC
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $orders_per_page, $offset);
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

function getOrderStatus($status)
{
    switch ($status) {
        case '0':
            return 'ยกเลิกสั่งซื้อ';
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
    <title>Edit Profile</title>
    <?php include('script-css.php'); ?>
</head>

<body>
    <?php include('nav.php'); ?>

    <div class="body-container">

        <?php include('index_Menu.php'); ?>

        <div class="view-history-menu">
            <div class="col-mb-12 mt-2" style="margin-bottom: 20px;">
                <h3><img src="assets/images/other/history.png" alt=""
                        style="width: 50px; height: 50px; margin-top: -10px;">&nbsp;ประวัติการสั่งซื้อ</h3>
            </div>
            <?php if (empty($order_history)) : ?>
                <p>ยังไม่มีประวัติการสั่งซื้อ</p>
            <?php else : ?>
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
                                <th scope="col">รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_history as $orderID => $order) : ?>
                                <tr>
                                    <td><?php echo str_pad($orderID, 10, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo $order['order_date']; ?></td>
                                    <td>฿<?php echo $order['total_price']; ?></td>
                                    <td>
                                        <?php if ($order['order_status'] == '2') : ?>
                                            <div class="success-status"><i class='bx bxs-check-circle'></i>&nbsp;<?php echo getOrderStatus($order['order_status']); ?></div>
                                        <?php elseif ($order['order_status'] == '1') : ?>
                                            <div class="wait-status"><i class='bx bxs-time'></i>&nbsp;<?php echo getOrderStatus($order['order_status']); ?></div>
                                        <?php elseif ($order['order_status'] == '0') : ?>
                                            <div class="cancel-status"><i class='bx bxs-x-circle'></i>&nbsp;<?php echo getOrderStatus($order['order_status']); ?></div>
                                        <?php else : ?>
                                            <?php echo getOrderStatus($order['order_status']); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $order['parcel_number'] ?: 'รอจัดส่ง'; ?></td>
                                    <td><?php echo $order['shipping_type_name']; ?></td>
                                    <td class="text-center"><a href="product_Order_detail.php?orderID=<?php echo $orderID; ?>" class="btn btn-view"><i class='bx bx-receipt'></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Links -->
                <div class="page-order-view">
                    <ul class="pagination">
                        <?php if ($current_page > 1) : ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true"><i class='bx bx-chevron-left'></i></span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li class="page-item <?php if ($i == $current_page) echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if ($current_page < $total_pages) : ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true"><i class='bx bx-chevron-right'></i></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <div class="previous-button">
                <a href="index.php" class="btn btn-dark">ย้อนกลับ</a>
            </div>

        </div> <!-- end view-history-menu -->
    </div> <!-- body-container -->

    <?php include('footer.php'); ?>

</body>

</html>

<?php include('script-js.php'); ?>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
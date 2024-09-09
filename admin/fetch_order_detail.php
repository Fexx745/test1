<?php
include('condb.php');

$orderID = $_GET['id'];

// Query to get order details along with the payment slip
$sql = "SELECT o.*, m.prefix, m.firstname, m.lastname, m.telephone, m.address, p.slip_image 
        FROM tb_order o 
        JOIN tb_member m ON o.member_id = m.id
        LEFT JOIN tb_payment p ON o.orderID = p.orderID
        WHERE o.orderID = '$orderID'";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_array($result)) {
?>
    <h5>เลขที่ใบสั่งซื้อ: <?php echo $row['orderID']; ?></h5>
    <span>ชื่อลูกค้า:</span><span> <?php echo $row['prefix'] . " " . $row['firstname'] . " " . $row['lastname']; ?></span><br>
    <span>เบอร์โทรศัพท์:</span><span> <?php echo $row['telephone']; ?></span><br>
    <span>ที่อยู่:</span><span> <?php echo $row['address']; ?></span><br>
    <span>รวมสุทธิ:</span><span> <?php echo number_format($row['total_price'], 2); ?></span><br><br>
    <span>วันที่สั่งซื้อ:</span><span> <?php echo $row['reg']; ?></span><br>

    <!-- Display order status with icons -->
    <b>สถานะ: </b>
    <?php if ($row['order_status'] == 1) { ?>
        <i class='fas fa-clock' style='color: #f0ad4e;'></i> <span style='color: #f0ad4e;'>รอตรวจสอบ</span><br><br>
    <?php } elseif ($row['order_status'] == 2) { ?>
        <i class='fas fa-check-circle' style='color: #5cb85c;'></i> <span style='color: #5cb85c;'>ชำระเงินแล้ว</span><br><br>
    <?php } else { ?>
        <i class='fas fa-times-circle' style='color: #d9534f;'></i> <span style='color: #d9534f;'>ยกเลิกการสั่งซื้อ</span><br><br>
    <?php } ?>


    <b>หมายเหตุ:</b><span> <?php echo $row['annotation']; ?></span><br><br>

    <!-- Display payment slip image if available -->
    <?php if (!empty($row['slip_image'])) { ?>
        <p>รูปภาพสลิป:</p>
        <img src='../assets/images/slip_images/<?php echo $row['slip_image']; ?>' alt='Payment Slip' style='max-width:100%; height:auto;'>
    <?php } else { ?>
        <p>ไม่มีรูปภาพสลิป</p>
    <?php } ?>

<?php
} else {
    echo "<p>ไม่พบรายละเอียดคำสั่งซื้อ</p>";
}

mysqli_close($conn);
?>
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
    // Display order details in the modal
    echo "<h5>เลขที่ใบสั่งซื้อ: " . $row['orderID'] . "</h5>";
    echo "<b>ชื่อลูกค้า:</b><span> " . $row['prefix'] . " " . $row['firstname'] . " " . $row['lastname'] . "</span><br>";
    echo "<b>เบอร์โทรศัพท์:</b><span> " . $row['telephone'] . " " . "</span><br>";
    echo "<b>ที่อยู่:</b><span> " . $row['address'] . " " . "</span><br>";
    echo "<b>รวมสุทธิ:</b><span> " . number_format($row['total_price'], 2) . "</span><br><br>";
    echo "<b>วันที่สั่งซื้อ:</b><span> " . $row['reg'] . "</span><br>";

    // Display status with icons
    echo "<b>สถานะ: ";
    if ($row['order_status'] == 1) {
        echo "<i class='fas fa-clock'></i> รอตรวจสอบ";
    } elseif ($row['order_status'] == 2) {
        echo "<i class='fas fa-check-circle'></i> ชำระเงินแล้ว";
    } else {
        echo "<i class='fas fa-times-circle'></i> ยกเลิกการสั่งซื้อ";
    }
    echo "</b><br><br>";


    // Display payment slip image if available
    if (!empty($row['slip_image'])) {
        echo "<p>รูปภาพสลิป:</p>";
        echo "<img src='../assets/images/slip_images/" . $row['slip_image'] . "' alt='Payment Slip' style='max-width:100%; height:auto;'>";
    } else {
        echo "<p>ไม่มีรูปภาพสลิป</p>";
    }

    // Add more details as needed
} else {
    echo "<p>ไม่พบรายละเอียดคำสั่งซื้อ</p>";
}

mysqli_close($conn);

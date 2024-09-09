<?php
include('condb.php');
session_start();

// Debugging: Print received POST data (remove this after testing)
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";

if (isset($_POST['order_id'], $_POST['shipping_type'])) {
    // Retrieve form data
    $order_id = $_POST['order_id'];
    $shipping_type = $_POST['shipping_type'];
    $parcel_number = isset($_POST['parcel_number']) ? $_POST['parcel_number'] : null;
    $annotation = isset($_POST['annotation']) ? $_POST['annotation'] : null;

    // Update query
    $update_sql = "UPDATE tb_order SET shipping_type_id = ?, parcel_number = ?, annotation = ?, order_status = 2 WHERE orderID = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("issi", $shipping_type, $parcel_number, $annotation, $order_id);

    // Execute update
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "อัพเดทข้อมูลใบสั่งซื้อเรียบร้อยแล้ว";
    } else {
        $_SESSION['error_message'] = "มีปัญหาในการอัพเดทข้อมูลใบสั่งซื้อ";
    }

    // Close statement
    $stmt->close();
} else {
    $_SESSION['error_message'] = "ไม่พบข้อมูลที่ต้องการอัพเดท";
}

// Redirect back to report_order_yes.php
if (isset($order_id)) {
    header("Location: report_order_yes.php?id={$order_id}");
} else {
    header("Location: report_order.php");
}
exit(); // Exit script
?>

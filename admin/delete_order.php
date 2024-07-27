<?php
session_start();
include('condb.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $orderID = $_GET['id'];

    // ลบข้อมูลใน tb_payment_slip
    $sql_delete_payment_slip = "DELETE FROM tb_payment WHERE orderID = '$orderID'";
    $result_delete_payment_slip = mysqli_query($conn, $sql_delete_payment_slip);

    if ($result_delete_payment_slip) {
        // ลบข้อมูลใน tb_order_detail
        $sql_delete_order_detail = "DELETE FROM tb_order_detail WHERE orderID = '$orderID'";
        $result_delete_order_detail = mysqli_query($conn, $sql_delete_order_detail);

        if ($result_delete_order_detail) {
            // ลบข้อมูลใน tb_order
            $sql_delete_order = "DELETE FROM tb_order WHERE orderID = '$orderID'";
            $result_delete_order = mysqli_query($conn, $sql_delete_order);

            if ($result_delete_order) {
                $_SESSION['delete_order'] = "ลบรายการเรียบร้อยแล้ว";
                header('Location: report_order.php');
                exit();
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการลบรายการ');</script>";
                echo "<script>window.location='report_order.php';</script>";
                exit;
            }
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการลบรายละเอียดการสั่งซื้อ');</script>";
            echo "<script>window.location='report_order.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูลการชำระเงิน');</script>";
        echo "<script>window.location='report_order.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ข้อมูลไม่ถูกต้อง');</script>";
    echo "<script>window.location='report_order.php';</script>";
    exit;
}

?>

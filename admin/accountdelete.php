<?php
session_start();
if (isset($_GET['id'])) {
    include('condb.php');

    $id = $_GET['id'];

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Delete related records in tb_payment
        $delete_payment_query = "
            DELETE p
            FROM tb_payment p
            JOIN tb_order o ON p.orderID = o.orderID
            WHERE o.member_id = '$id'
        ";
        if (!mysqli_query($conn, $delete_payment_query)) {
            throw new Exception("Error deleting payments: " . mysqli_error($conn));
        }

        // Delete related records in tb_order_detail
        $delete_order_detail_query = "
            DELETE od
            FROM tb_order_detail od
            JOIN tb_order o ON od.orderID = o.orderID
            WHERE o.member_id = '$id'
        ";
        if (!mysqli_query($conn, $delete_order_detail_query)) {
            throw new Exception("Error deleting order details: " . mysqli_error($conn));
        }

        // Delete related records in tb_order
        $delete_order_query = "DELETE FROM tb_order WHERE member_id = '$id'";
        if (!mysqli_query($conn, $delete_order_query)) {
            throw new Exception("Error deleting orders: " . mysqli_error($conn));
        }

        // Delete member record
        $delete_member_query = "DELETE FROM tb_member WHERE id = '$id'";
        if (!mysqli_query($conn, $delete_member_query)) {
            throw new Exception("Error deleting member: " . mysqli_error($conn));
        }

        // Commit transaction
        mysqli_commit($conn);

        $_SESSION['deleteaccount'] = "ลบผู้ใช้";
        header('Location: show_account.php');
        exit();
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);

        // Display error message
        echo $e->getMessage();
    }

    mysqli_close($conn);
} else {
    echo "unit ID not provided.";
}
?>

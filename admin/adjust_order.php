<?php
    session_start();
    include('condb.php');

    $idpd = $_GET['id'];

    $sql = "UPDATE tb_order SET order_status = 2 WHERE orderID='$idpd' ";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $_SESSION['confirmOrder'] = "ยกเลิกคำสั่งซื้อ";
        header('Location: report_order_yes.php');
        exit();
    } else {
        echo "<script>alert('ไม่สามารถปรับสถานะได้!')</script>";
    }

    mysqli_close($conn);
?>
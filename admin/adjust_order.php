<?php
    session_start();
    include('condb.php');

    $idpd = $_GET['id'];

    $sql = "UPDATE tb_order SET order_status = 3 WHERE orderID='$idpd' ";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $_SESSION['confirmOrder'] = "เปลี่ยนสถาะเป็นการัจดส่ง";
        header('Location: report_order_wait.php');
        exit();
    } else {
        echo "<script>alert('ไม่สามารถปรับสถานะได้!')</script>";
    }

    mysqli_close($conn);
?>
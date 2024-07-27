<?php
    session_start();
    include('condb.php');

    $idpd = $_GET['id'];

    $sql = "UPDATE tb_order SET order_status = 0 WHERE orderID='$idpd' ";
    $result = mysqli_query($conn, $sql);
    if($result){
        $_SESSION['cancelOrder'] = "ยกเลิกคำสั่งซื้อ";
        header('Location: report_order_no.php');
        exit();
    }else{
        echo "<script>alert('ไม่สามารถลบข้อมูลได้!')</script>";
    }

    mysqli_close($conn);
?>
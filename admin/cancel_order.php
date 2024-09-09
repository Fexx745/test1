<?php
    session_start();
    include('condb.php');

    // รับค่า id และ annotation จาก URL
    $idpd = $_GET['id'];
    $annotation = isset($_GET['annotation']) ? mysqli_real_escape_string($conn, $_GET['annotation']) : '';

    // อัปเดตคำสั่งซื้อและบันทึกหมายเหตุในฐานข้อมูล
    $sql = "UPDATE tb_order SET order_status = 0, annotation = '$annotation' WHERE orderID='$idpd'";
    $result = mysqli_query($conn, $sql);

    if($result){
        $_SESSION['cancelOrder'] = "ยกเลิกคำสั่งซื้อสำเร็จ";
        header('Location: report_order_no.php');
        exit();
    }else{
        echo "<script>alert('ไม่สามารถลบข้อมูลได้!')</script>";
    }

    mysqli_close($conn);
?>

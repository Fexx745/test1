<?php
// เชื่อมต่อฐานข้อมูล
include('condb.php');
session_start();

// ตรวจสอบว่ามีการส่งค่า id มาจากหน้ารายการหรือไม่
if (isset($_GET['id'])) {
    // รับค่า id ที่ส่งมาจากหน้ารายการ
    $id = $_GET['id'];

    // ตรวจสอบว่ามีข้อมูลที่ต้องการลบหรือไม่
    $checkSql = "SELECT * FROM shipping_type WHERE shipping_type_id = $id";
    $checkResult = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        // ข้อมูลมีอยู่ในฐานข้อมูล สามารถลบได้
        $sql = "DELETE FROM shipping_type WHERE shipping_type_id = $id";
        
        if (mysqli_query($conn, $sql)) {
            // หากลบข้อมูลสำเร็จให้ redirect ไปที่หน้า shipping_add.php หรือหน้าที่ต้องการ
            $_SESSION['deleteshipping'] = "ลบขนส่งสำเร็จ";
            header('Location: shipping_add.php');
            exit();
        } else {
            // หากเกิดข้อผิดพลาดในการลบ
            $_SESSION['error_shipping_delete'] = "ไม่สามารถลบข้อมูลได้เนื่องจากมีข้อมูลซ้ำหรือเกิดข้อผิดพลาดอื่นๆ";
            header('Location: shipping_add.php');
            exit();
        }
    } else {
        // ข้อมูลไม่พบในฐานข้อมูล
        header('Location: shipping_add.php');
        exit();
    }
} else {
    // ถ้าไม่มีการส่ง id มา
    header('Location: shipping_add.php');
    exit();
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>

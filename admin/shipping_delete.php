<?php
// เชื่อมต่อฐานข้อมูล
include('condb.php');
session_start();

// ตรวจสอบว่ามีการส่งค่า id มาจากหน้ารายการหรือไม่
if (isset($_GET['id'])) {
    // รับค่า id ที่ส่งมาจากหน้ารายการ
    $id = $_GET['id'];

    // เตรียมคำสั่ง SQL เพื่อลบข้อมูล
    $sql = "DELETE FROM shipping_type WHERE shipping_type_id = $id";

    // ทำการส่งคำสั่ง SQL ไปยังฐานข้อมูล
    if (mysqli_query($conn, $sql)) {
        // หากลบข้อมูลสำเร็จให้ redirect ไปที่หน้า index.php หรือหน้าที่ต้องการ
        $_SESSION['deleteshipping'] = "ลบขนส่ง";
        header('Location: shipping_add.php');
        exit();
    } else {
        // หากเกิดข้อผิดพลาดในการลบ
        echo "เกิดข้อผิดพลาดในการลบข้อมูล: " . mysqli_error($conn);
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>

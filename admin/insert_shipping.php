<?php
// เชื่อมต่อฐานข้อมูล
session_start();
include ('condb.php');

// ตรวจสอบว่ามีการส่งค่าจากฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $shipping_name = $_POST['shipping_name'];

    // เตรียมคำสั่ง SQL เพื่อเพิ่มข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO shipping_type (shipping_type_name) VALUES ('$shipping_name')";

    // ทำการส่งคำสั่ง SQL ไปยังฐานข้อมูล
    if (mysqli_query($conn, $sql)) {
        $_SESSION['addshipping'] = "เพิ่มขนส่ง";
        header('Location: addshipping.php');
        exit();
    } else {
        // หากเกิดข้อผิดพลาดในการบันทึก
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . mysqli_error($conn);
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    mysqli_close($conn);
}
?>
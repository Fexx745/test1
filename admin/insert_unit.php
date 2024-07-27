<?php
session_start();
// ตรวจสอบว่ามีการส่งข้อมูลผ่าน POST มาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // ตรวจสอบว่ามีการส่งค่าชื่อหน่วยมาหรือไม่
    if (isset($_POST["unitname"])) {
        
        // เชื่อมต่อกับฐานข้อมูล
        include('condb.php');

        // ดึงข้อมูลที่ได้รับจากฟอร์ม
        $unitname = mysqli_real_escape_string($conn, $_POST["unitname"]);

        // เขียนคำสั่ง SQL เพื่อ Insert ข้อมูล
        $sql = "INSERT INTO unit_type (unit_name) VALUES ('$unitname')";

        // ทำการ Insert ข้อมูล
        $result = mysqli_query($conn, $sql);

        // ตรวจสอบว่า Insert สำเร็จหรือไม่
        if ($result) {
            $_SESSION['addunit'] = "เพิ่มยี่ห้อ";
            header('Location: addunit.php');
            exit();
        } else {
            echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . mysqli_error($conn);
        }

        // ปิดการเชื่อมต่อฐานข้อมูล
        mysqli_close($conn);
    } else {
        // ถ้าไม่มีค่า typename ถูกส่งมา
        echo "กรุณากรอกข้อมูลให้ครบถ้วน";
    }
}
?>

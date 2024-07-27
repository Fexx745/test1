<?php
session_start();
include('condb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบว่ามีการส่งค่ามาจากฟอร์มหรือไม่
    if (isset($_POST['unitid']) && isset($_POST['unitname'])) {
        // รับค่าที่ส่งมาจากฟอร์ม
        $unit_id = $_POST['unitid'];
        $unit_name = $_POST['unitname'];

        // เขียนคำสั่ง SQL สำหรับการอัปเดตข้อมูล
        $update_query = "UPDATE unit_type SET unit_name = '$unit_name' WHERE unit_id = '$unit_id'";
        
        // ทำการอัปเดตข้อมูลในฐานข้อมูล
        $result = mysqli_query($conn, $update_query);

        if ($result) {
            $_SESSION['editunit'] = "แก้ไขหน่วย";
            header('Location: addunit.php');
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
}
?>

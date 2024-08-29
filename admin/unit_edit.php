<?php
session_start();
include('condb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบว่ามีการส่งค่ามาจากฟอร์มหรือไม่
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['unitid']) && isset($data['unitname'])) {
        // รับค่าที่ส่งมาจากฟอร์ม
        $unit_id = $data['unitid'];
        $unit_name = $data['unitname'];

        // เขียนคำสั่ง SQL สำหรับการอัปเดตข้อมูล
        $update_query = "UPDATE unit_type SET unit_name = '$unit_name' WHERE unit_id = '$unit_id'";
        
        // ทำการอัปเดตข้อมูลในฐานข้อมูล
        $result = mysqli_query($conn, $update_query);

        if ($result) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error updating record: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid input"]);
    }
}
?>

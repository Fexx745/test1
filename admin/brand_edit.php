<?php
session_start();
include('condb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบว่ามีการส่งค่ามาจากฟอร์มหรือไม่
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['brandid']) && isset($data['brandname'])) {
        // รับค่าที่ส่งมาจากฟอร์ม
        $brand_id = $data['brandid'];
        $brand_name = $data['brandname'];

        // ตรวจสอบว่า brand_name ซ้ำหรือไม่ โดยตรวจสอบกับ brand_id ที่ไม่ใช่ตัวปัจจุบัน
        $check_query = "SELECT * FROM brand_type WHERE brand_name = '$brand_name' AND brand_id != '$brand_id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // ถ้าพบว่าชื่อแบรนด์ซ้ำ
            echo json_encode(["status" => "error", "message" => "ชื่อแบรนด์มีอยู่แล้ว"]);
        } else {
            // ถ้าไม่ซ้ำ ทำการอัปเดตข้อมูล
            $update_query = "UPDATE brand_type SET brand_name = '$brand_name' WHERE brand_id = '$brand_id'";
            $result = mysqli_query($conn, $update_query);

            if ($result) {
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error updating record: " . mysqli_error($conn)]);
            }
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid input"]);
    }
}
?>

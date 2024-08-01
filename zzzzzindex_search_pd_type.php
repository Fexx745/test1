<?php
// index_search_pd_type.php

// เชื่อมต่อฐานข้อมูล
include('condb.php');

// ตรวจสอบว่ามีการส่งค่า type_id มาหรือไม่
if(isset($_GET['type_id'])) {
    // รับค่า type_id จาก query string
    $type_id = $_GET['type_id'];

    // คำสั่ง SQL เพื่อค้นหาสินค้าตามประเภทที่ผู้ใช้เลือก
    $sql = "SELECT * FROM product WHERE type_id = $type_id";
    $result = mysqli_query($conn, $sql);

    // ตรวจสอบว่ามีสินค้าที่ค้นพบหรือไม่
    if(mysqli_num_rows($result) > 0) {
        // แสดงรายการสินค้า
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>".$row['product_name']."</p>"; // แสดงชื่อสินค้าตั้งแต่ฐานข้อมูล
            // ต่อให้แสดงรายละเอียดเพิ่มเติมของสินค้าได้ตามต้องการ
        }
    } else {
        echo "ไม่พบสินค้าในประเภทนี้";
    }

} else {
    echo "ไม่พบประเภทสินค้า";
}
?>

<?php
// เชื่อมต่อฐานข้อมูล
include('condb.php');

// ตรวจสอบการเชื่อมต่อ
if (!$conn) {
    die("การเชื่อมต่อล้มเหลว: " . mysqli_connect_error());
}

// คำสั่ง SQL เพื่อค้นหาจำนวนการขายสำหรับแต่ละสินค้า (p_id)
$sql = "SELECT p_id, COUNT(*) AS sales_count
        FROM tb_order_detail
        GROUP BY p_id";

$result = mysqli_query($conn, $sql);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if (mysqli_num_rows($result) > 0) {
    // แสดงผลลัพธ์ในรูปแบบของตาราง HTML
    echo "<table>";
    echo "<tr><th>p_id</th><th>จำนวนการขาย</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row["p_id"] . "</td><td>" . $row["sales_count"] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "ไม่พบข้อมูลการขายสินค้า";
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
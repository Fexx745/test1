<?php
header('Content-Type: application/json');

include('condb.php');

// ดึงปีปัจจุบัน
$currentYear = date('Y');

// สร้างอาร์เรย์สำหรับ 12 เดือน
$months = array(
    1 => 'January', 2 => 'February', 3 => 'March',
    4 => 'April', 5 => 'May', 6 => 'June',
    7 => 'July', 8 => 'August', 9 => 'September',
    10 => 'October', 11 => 'November', 12 => 'December'
);

// เตรียมอาร์เรย์สำหรับผลลัพธ์
$data = array_fill_keys(array_keys($months), 0);

// ดึงข้อมูลจากฐานข้อมูล
$sqlQuery = "SELECT SUM(total_price) AS sumTotal, MONTH(reg) AS reg_month FROM tb_order WHERE YEAR(reg) = $currentYear GROUP BY reg_month ORDER BY reg_month";
$result = mysqli_query($conn, $sqlQuery);

// เพิ่มข้อมูลยอดขายเข้าไปในอาร์เรย์
while ($row = mysqli_fetch_assoc($result)) {
    $data[$row['reg_month']] = (int)$row['sumTotal'];
}

// สร้างข้อมูลสำหรับการส่งออก
$output = [];
foreach ($months as $key => $value) {
    $output[] = [
        'reg_month' => $value,
        'sumTotal' => $data[$key]
    ];
}

mysqli_close($conn);

echo json_encode($output);
?>

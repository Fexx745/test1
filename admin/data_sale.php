<?php
header('Content-Type: application/json');

include('condb.php');

// ดึงปีปัจจุบัน
$currentYear = date('Y');

$sqlQuery = "SELECT SUM(total_price) as sumTotal, DATE_FORMAT(reg, '%M' ) as reg_month FROM tb_order WHERE YEAR(reg) = $currentYear GROUP BY reg_month ORDER BY reg";

$result = mysqli_query($conn, $sqlQuery);

$data = array();
foreach ($result as $row) {
    $data[] = $row;
}

mysqli_close($conn);

echo json_encode($data);
?>

<?php
session_start();
include('condb.php');

$brandname = $_POST['brandname'];

// ตรวจสอบว่ามีชื่อยี่ห้อซ้ำหรือไม่
$sql = "SELECT * FROM brand_type WHERE brand_name = '$brandname'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['error_brand'] = "มีชื่อยี่ห้อสินค้านี้อยู่แล้ว";
    header("Location: brand_add.php"); // เปลี่ยนเป็น URL ที่ต้องการ
    exit();
}

// ถ้าไม่มีซ้ำให้เพิ่มเข้าไป
$sql = "INSERT INTO brand_type (brand_name) VALUES ('$brandname')";
mysqli_query($conn, $sql);
$_SESSION['addbrand'] = true;
header("Location: brand_add.php"); // เปลี่ยนเป็น URL ที่ต้องการ
?>

?>

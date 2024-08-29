<?php
include_once("condb.php");

session_start();
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $banner_id = $_GET['id'];

    // ตรวจสอบว่า ID ที่ส่งมาเป็นตัวเลขหรือไม่
    if (is_numeric($banner_id)) {
        // ลบข้อมูลจากฐานข้อมูล
        $sql = "DELETE FROM banners WHERE banner_id = $banner_id";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['deletebanner'] = "ลบข้อมูลเรียบร้อยแล้ว";
            header('Location: banner_add.php');
            exit();
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid ID";
    }
} else {
    echo "Invalid request";
}
?>

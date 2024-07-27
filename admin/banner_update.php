<?php
session_start();
include "condb.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['pid']) && isset($_POST['b_name']) && isset($_POST['b_detail'])) {
        
        // ตรวจสอบการเชื่อมต่อกับฐานข้อมูล
        if (!$conn) {
            die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . mysqli_connect_error());
        }
        
        $banner_id = $_POST['pid'];
        $banner_name = $_POST['b_name'];
        $banner_detail = $_POST['b_detail'];
        
        if (isset($_FILES['b_image']) && $_FILES['b_image']['error'] === UPLOAD_ERR_OK) {
            $file_name = $_FILES['b_image']['name'];
            $file_tmp = $_FILES['b_image']['tmp_name'];
            move_uploaded_file($file_tmp, "../assets/images/banner/" . $file_name);
            
            $sql_image = "UPDATE banners SET image = '$file_name' WHERE banner_id = $banner_id";
            if (mysqli_query($conn, $sql_image)) {
                echo "รูปภาพถูกอัปเดตเรียบร้อย";
            } else {
                echo "เกิดข้อผิดพลาดในการอัปเดตรูปภาพ: " . mysqli_error($conn);
            }
        }
        
        $sql_update = "UPDATE banners SET banner_name = '$banner_name', banner_detail = '$banner_detail' WHERE banner_id = $banner_id";
        if (mysqli_query($conn, $sql_update)) {
            echo "ข้อมูลถูกอัปเดตเรียบร้อย";
        } else {
            echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . mysqli_error($conn);
        }
        
        $_SESSION['banner_update'] = "แก้ไขรูปสำเร็จ";
        header('Location: editbanner.php');
        exit();
    } else {
        echo "กรุณากรอกข้อมูลในฟิลด์ที่จำเป็นทั้งหมด";
    }
} else {
    header("Location: editbanner.php");
    exit();
}
?>

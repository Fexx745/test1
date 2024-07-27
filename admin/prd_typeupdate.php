<?php
// ตรวจสอบว่ามีการส่งข้อมูลแบบ POST มาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบสิทธิ์และตรวจสอบการติดตั้งข้อมูลที่จำเป็น
    session_start();
    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit();
    }

    // รับค่าที่ส่งมาจากฟอร์ม
    $typeid = $_POST['typeid'];
    $typename = $_POST['typename'];

    // ตรวจสอบว่ามีการเลือกรูปภาพใหม่หรือไม่
    if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // อัปโหลดไฟล์รูปภาพใหม่
        $targetDir = "../assets/images/type_product/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // อัพโหลดไฟล์สำเร็จ อัพเดตฐานข้อมูลด้วยชื่อไฟล์รูปภาพใหม่
            $image_filename = $_FILES["image"]["name"];
            include('condb.php');  // เชื่อมต่อฐานข้อมูล
            $sql_update_image = "UPDATE product_type SET type_image = '$image_filename' WHERE type_id = '$typeid'";
            mysqli_query($conn, $sql_update_image);
        } else {
            // จัดการข้อผิดพลาดหากการอัพโหลดไฟล์ล้มเหลว
            echo "Failed to upload the image.";
        }
    }

    // อัปเดตชื่อประเภทสินค้า
    include('condb.php');  // เชื่อมต่อฐานข้อมูล
    $sql_update_type = "UPDATE product_type SET type_name = '$typename' WHERE type_id = '$typeid'";
    $result_update = mysqli_query($conn, $sql_update_type);

    if ($result_update) {
        $_SESSION['edit_producttype'] = "แก้ไขประเภทสินค้า";
        header('Location: addproducttype.php');
        exit();
    } else {
        echo "Update failed!";
    }
} else {
    header('Location: prd_typeupdate_form.php');  // เปลี่ยนนี้เป็นหน้าที่คุณต้องการ
    exit();
}
?>

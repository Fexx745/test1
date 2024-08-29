<?php
include_once("condb.php");

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีข้อมูลที่ถูกส่งมา
    if (isset($_POST['banner_name']) && isset($_FILES['fileimage'])) {
        $banner_name = $_POST['banner_name'];
        $banner_detail = isset($_POST['banner_detail']) ? $_POST['banner_detail'] : '';

        // ตรวจสอบว่ามีการเลือกไฟล์รูปภาพ
        if ($_FILES['fileimage']['error'] == 0) {
            $target_dir = "../assets/images/banner/";
            $target_file = $target_dir . basename($_FILES["fileimage"]["name"]);

            // ตรวจสอบว่าไฟล์รูปภาพมีนามสกุลที่อนุญาต
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_extensions = array("jpg", "jpeg", "png", "gif");

            if (in_array($imageFileType, $allowed_extensions)) {
                // ย้ายไฟล์ไปยังตำแหน่งที่เก็บ
                move_uploaded_file($_FILES["fileimage"]["tmp_name"], $target_file);

                // เพิ่มข้อมูลลงในฐานข้อมูล
                $sql = "INSERT INTO banners (banner_name, banner_detail, image) VALUES ('$banner_name', '$banner_detail', '" . basename($target_file) . "')";
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['success'] = "เพิ่มรูปสำเร็จ";
                    header('Location: banner_add.php');
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            } else {
                echo "รูปภาพต้องเป็นไฟล์นามสกุล jpg, jpeg, png, หรือ gif เท่านั้น";
            }
        } else {
            echo "เกิดข้อผิดพลาดในการอัพโหลดรูปภาพ";
        }
    } else {
        // ลบข้อความนี้หากไม่ต้องการแสดง
        // echo "กรุณากรอกข้อมูลที่จำเป็น";
    }
}
?>

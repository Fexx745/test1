<?php
session_start();
include('condb.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $typename = $_POST['typename']; // รับค่าชื่อประเภทสินค้า

    // ตรวจสอบว่าชื่อประเภทสินค้านี้มีอยู่ในฐานข้อมูลหรือไม่
    $checkSql = "SELECT * FROM product_type WHERE type_name = '$typename'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // ชื่อประเภทสินค้าซ้ำ
        $_SESSION['check_name'] = "ชื่อประเภทสินค้านี้มีอยู่แล้ว";
        header('Location: producttype_List.php');
        exit();
    }

    // Check if a file is selected for upload
    if (isset($_FILES['fileimage']) && $_FILES['fileimage']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['fileimage']['tmp_name'];
        $fileName = $_FILES['fileimage']['name'];
        $fileSize = $_FILES['fileimage']['size'];

        // ขนาดไฟล์สูงสุด 5MB
        if ($fileSize > 5000000) {
            echo "ขนาดไฟล์ใหญ่เกินไป";
            exit();
        }

        // ตั้งชื่อและตำแหน่งที่ต้องการบันทึกไฟล์
        $uploadDir = '../assets/images/type_product/';
        $destPath = $uploadDir . $fileName;

        // ย้ายไฟล์ไปยังตำแหน่งที่ต้องการบันทึก
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // หลังจากอัปโหลดไฟล์สำเร็จ ทำการบันทึกข้อมูลลงในฐานข้อมูล
            $sql = "INSERT INTO product_type (type_name, type_image) VALUES ('$typename', '$fileName')";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['addproducttype'] = "เพิ่มประเภทสินค้าสำเร็จ";
                header('Location: producttype_List.php');
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
        }
    } else {
        // No file selected, proceed without image upload
        $sql = "INSERT INTO product_type (type_name) VALUES ('$typename')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['addproducttype'] = "เพิ่มประเภทสินค้าสำเร็จ";
            header('Location: producttype_List.php');
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    mysqli_close($conn);
}

<?php
session_start();
include('condb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าที่ส่งมาจากฟอร์ม
    $product_id = mysqli_real_escape_string($conn, $_POST['price_id']);
    $price = $_POST['price'];
    $from_date = !empty($_POST['from_date']) ? $_POST['from_date'] : null;
    $to_date = !empty($_POST['to_date']) ? $_POST['to_date'] : null;

    // ตรวจสอบว่ามี ID สินค้าที่ต้องการอัปเดตหรือไม่
    if (!empty($product_id)) {
        // ตรวจสอบว่า from_date ไม่เป็นวันที่ย้อนหลัง
        if ($from_date && strtotime($from_date) < strtotime(date('Y-m-d'))) {
            $_SESSION['error'] = "ไม่สามารถเลือกวันที่ย้อนหลังได้";
            header('Location: prd_editprice.php?id=' . $product_id);
            exit();
        }

        // สร้างคำสั่ง SQL สำหรับอัพเดทข้อมูล
        $update_sql = "UPDATE price_history SET price = '$price'";
        
        // เพิ่มการตรวจสอบและอัพเดทวันที่ถ้ามีการเลือกวันที่
        if (!empty($from_date)) {
            $update_sql .= ", from_date = '$from_date'";
        }

        if (!empty($to_date)) {
            $update_sql .= ", to_date = '$to_date'";
        }

        $update_sql .= " WHERE p_id = '$product_id'";
        
        // Debugging output
        echo $update_sql;

        $result = mysqli_query($conn, $update_sql);

        if ($result) {
            $_SESSION['success'] = "แก้ไขราคาสินค้าเรียบร้อยแล้ว";
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "ไม่พบ ID สินค้าที่ต้องการอัปเดต";
    }
    $_SESSION['editprice'] = "อัพเดทราคาสำเร็จ";
    header('Location: prd_editprice.php?id=' . $product_id);
    exit();
} else {
    $_SESSION['error'] = "ไม่สามารถเข้าถึงหน้านี้โดยตรงได้";
    header('Location: prd_editprice.php');
    exit();
}

mysqli_close($conn);


?>
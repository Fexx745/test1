<?php
session_start();
include('condb.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unitname = $_POST['unitname'];

    // ตรวจสอบว่ามีหน่วยสินค้านี้อยู่ในฐานข้อมูลหรือไม่
    $checkSql = "SELECT * FROM unit_type WHERE unit_name = '$unitname'";
    $checkResult = mysqli_query($conn, $checkSql);

    if ($checkResult->num_rows > 0) {
        // ชื่อหน่วยสินค้าซ้ำ
        $_SESSION['error_unit'] = "หน่วยสินค้านี้มีอยู่แล้ว";
        header('Location: unit_add.php'); // เปลี่ยนเป็นชื่อหน้าที่คุณใช้
        exit();
    }

    // แทรกข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO unit_type (unit_name) VALUES ('$unitname')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['addunit'] = "เพิ่มหน่วยสินค้าสำเร็จ";
        header('Location: unit_add.php'); // เปลี่ยนเป็นชื่อหน้าที่คุณใช้
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    mysqli_close($conn);
}

?>

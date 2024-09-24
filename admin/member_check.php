<?php
session_start();
include('condb.php');

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // ตรวจสอบอีเมลซ้ำ
    $sql = "SELECT * FROM tb_member WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "exists"; // อีเมลมีอยู่แล้ว
    } else {
        echo "available"; // อีเมลว่าง
    }
}

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $sql = "SELECT * FROM tb_member WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "exists"; // ชื่อผู้ใช้มีอยู่แล้ว
    } else {
        echo "available"; // ชื่อผู้ใช้ว่าง
    }
}

// ตรวจสอบหมายเลขโทรศัพท์
if (isset($_POST['telephone'])) {
    $telephone = $_POST['telephone'];
    
    // ตรวจสอบหมายเลขโทรศัพท์ซ้ำ
    $sql = "SELECT * FROM tb_member WHERE telephone = '$telephone'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "telephone_exists"; // หมายเลขโทรศัพท์มีอยู่แล้ว
    } else {
        echo "telephone_available"; // หมายเลขโทรศัพท์ว่าง
    }
}

?>

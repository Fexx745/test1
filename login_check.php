<?php
include('condb.php');
session_start();

$username = mysqli_real_escape_string($conn, $_POST['username']);
$psw = $_POST['psw'];

$sql = "SELECT * FROM tb_member WHERE username='$username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $hashed_password = $row['password'];
    $status = $row['status']; // เพิ่มการดึงค่า status จากฐานข้อมูล

    // ตรวจสอบรหัสผ่านที่ป้อนกับรหัสผ่านที่แฮชจากฐานข้อมูล
    if (password_verify($psw, $hashed_password)) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['prefix'] = $row['prefix'];
        $_SESSION['fname'] = $row['firstname'];
        $_SESSION['lname'] = $row['lastname'];
        $_SESSION['phone'] = $row['telephone'];
        $_SESSION['address'] = $row['address'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['status'] = $row['status'];
        $_SESSION['hashed_password'] = $hashed_password;

        // ตรวจสอบค่าของ status และส่งผู้ใช้ไปยังหน้าที่ถูกต้องตามค่า status

        if ($status == 0) {
            $_SESSION['success'] = "เข้าสู่ระบบสำเร็จ";
            header('Location: index.php');
            exit();
        } elseif ($status == 1) {
            $_SESSION['success-admin'] = "เข้าสู่ระบบสำเร็จ";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "<p>Your username or password is invalid</p>";
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['error'] = "<p>Your username or password is invalid</p>";
    header('Location: index.php');
    exit();
}
?>
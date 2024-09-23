<?php
// เชื่อมต่อกับฐานข้อมูล
session_start();
include('condb.php');

if(isset($_POST['submit'])) {
    // รับค่าอีเมลและโทเค็นการรีเซ็ตรหัสผ่านจากฟอร์ม
    $email = $_POST['email'];
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    // ตรวจสอบว่าอีเมลและโทเค็นไม่ว่างเปล่า
    if (!empty($email) && !empty($token) && !empty($new_password)) {
        // ตรวจสอบว่าอีเมลและโทเค็นตรงกับข้อมูลในฐานข้อมูลหรือไม่
        $query = "SELECT * FROM tb_member WHERE email='$email' AND reset_token='$token'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // อัปเดตรหัสผ่านใหม่ในฐานข้อมูล
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE tb_member SET password='$hashed_password', reset_token='' WHERE email='$email'";
            mysqli_query($conn, $update_query);

            // แสดงข้อความว่ารีเซ็ตรหัสผ่านสำเร็จ
            // echo "รีเซ็ตรหัสผ่านสำเร็จ";
            $_SESSION['psw_suc'] = "รีเซ็ตรหัสผ่านสำเร็จ";
            header('Location: login.php');
            exit();
        } else {
            // แสดงข้อความว่าข้อมูลไม่ถูกต้อง
            echo "ข้อมูลไม่ถูกต้อง";
        }
    } else {
        // แสดงข้อความว่าไม่มีข้อมูล
        echo "ไม่มีข้อมูล";
        // $_SESSION['reset_psw'] = "ล้มเหลวในการรีเซ็ตรหัสผ่าน: ข้อมูลไม่ครบถ้วน";
        // header('Location: reset_password.php');
        // exit();
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>

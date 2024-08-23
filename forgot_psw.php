<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // ใช้งาน PHPMailer

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    session_start();
    include('condb.php');

    // ตรวจสอบว่ามีอีเมลนี้ในฐานข้อมูลหรือไม่
    $query = "SELECT * FROM tb_member WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);

    if (mysqli_num_rows($result) > 0) {
        // สร้างโทเค็นสำหรับการรีเซ็ตรหัสผ่าน
        $token = bin2hex(random_bytes(50));

        // อัปเดตโทเค็นในฐานข้อมูล
        $update_query = "UPDATE tb_member SET reset_token='$token' WHERE email='$email'";
        mysqli_query($conn, $update_query);

        // ส่งอีเมลรีเซ็ตรหัสผ่าน
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jatupon.si@rmuti.ac.th'; // อีเมล Gmail ของคุณ
        $mail->Password = 'ChatuponTH21396'; // รหัสผ่าน Gmail ของคุณ
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('MyShop', 'MyShop_Online'); // อีเมลและชื่อผู้ส่ง
        $mail->addAddress($email); // อีเมลผู้รับ

        $mail->isHTML(true);
        $mail->Subject = 'Reset Password';
        $mail->Body = '
        <div style="font-family: \'Roboto\', sans-serif; background-color: #f4f4f4; padding: 20px; border-radius: 5px;">
            <h2 style="font-size: 20px; color: #333333; margin-bottom: 15px;">สวัสดี, คุณ ' . $row['username'] . '</h2>
            <p style="font-size: 16px; color: #555555; line-height: 1.5; margin-bottom: 25px;">
                คุณได้ทำการร้องขอการรีเซ็ตรหัสผ่าน กรุณาคลิกที่ปุ่มด้านล่างเพื่อดำเนินการต่อ:
            </p>
            <a href="http://localhost/test1/reset_password.php?email=' . urlencode($email) . '&token=' . $token . '" 
               style="display: inline-block; background-color: #00C300; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px; 
               font-weight: 600; text-align: center; transition: background-color 0.3s;">
               รีเซ็ตรหัสผ่าน
            </a>
        </div>';




        if (!$mail->send()) {
            echo 'เกิดข้อผิดพลาดในการส่งอีเมล: ' . $mail->ErrorInfo;
        } else {
            // echo 'ลิงก์รีเซ็ตรหัสผ่านถูกส่งไปยังอีเมลของคุณแล้ว';
            $_SESSION['send-email'] = "ลิงก์รีเซ็ตรหัสผ่านถูกส่งไปยังอีเมลของคุณแล้ว";
            header('Location: forgot-form.php');
            exit();
        }
    } else {
        $_SESSION['not-email'] = "ไม่พบอีเมลในระบบ";
        header('Location: forgot-form.php');
        exit();
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    mysqli_close($conn);
}

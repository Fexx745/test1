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
            <b style="font-size: 17px; color: #000;">สวัสดี, คุณ ' . $row['username'] . ' ได้ทำการร้องขอการรีเซ็ตรหัสผ่าน</b>
            <p style="font-size: 16px; color: #000;">กรุณาคลิกที่ปุ่ม Reset Password ด้านล่างเพื่อรีเซ็ตรหัสผ่านของคุณ:</p>
            <a href="http://localhost/MyShop_Online%20%5bNEW%5d/reset_password.php?email=' . urlencode($email) . '&token=' . $token . '" style="display: inline-block; 
            background-color: #dc3545; color: #fff; text-decoration: none; padding: 7px 17px; border-radius: 5px; transition: opacity 0.5s; font-weight: 500;">Reset Password</a>
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
?>

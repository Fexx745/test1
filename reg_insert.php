<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('condb.php');

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $prefix = mysqli_real_escape_string($conn, $_POST['prefix']);
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['psw']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_psw']);

    // เก็บข้อมูลที่กรอกใน session
    $_SESSION['form_data'] = [
        'email' => $email,
        'prefix' => $prefix,
        'fname' => $fname,
        'lname' => $lname,
        'address' => $address,
        'phone' => $phone,
        'username' => $username
    ];

    // ตรวจสอบเบอร์โทรศัพท์ความยาว
    if (strlen($phone) < 9) {
        $_SESSION['Error'] = "กรุณาใส่เบอร์โทรศัพท์ให้ครบ 9-10 ตัว";
        $_SESSION['Error_field'] = 'phone'; // กำหนดฟิลด์ที่มีปัญหา
        unset($_SESSION['form_data']['phone']); // เคลียร์ข้อมูล phone ใน session
        header('Location: reg.php');
        exit();
    } elseif (strlen($phone) > 10) {
        $_SESSION['Error'] = "เบอร์โทรศัพท์ต้องมี 9-10 ตัว";
        $_SESSION['Error_field'] = 'phone'; // กำหนดฟิลด์ที่มีปัญหา
        unset($_SESSION['form_data']['phone']); // เคลียร์ข้อมูล phone ใน session
        header('Location: reg.php');
        exit();
    }

    // ตรวจสอบชื่อผู้ใช้ที่มีอยู่แล้ว
    $username_check_query = "SELECT * FROM tb_member WHERE username='$username' LIMIT 1";
    $result_username_check = mysqli_query($conn, $username_check_query);
    if (mysqli_num_rows($result_username_check) > 0) {
        $_SESSION['Error_field'] = 'username'; // เก็บฟิลด์ที่เกิดข้อผิดพลาด
        $_SESSION['form_data']['username'] = $username; // เก็บค่าชื่อผู้ใช้ที่กรอก
        $_SESSION['error_message'] = 'ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว!! กรุณาเลือกชื่อผู้ใช้อื่น';
        header("Location: reg.php");
        exit();
    }

    // ตรวจสอบอีเมลล์ที่มีอยู่แล้ว
    $email_check_query = "SELECT * FROM tb_member WHERE email='$email' LIMIT 1";
    $result_email_check = mysqli_query($conn, $email_check_query);
    if (mysqli_num_rows($result_email_check) > 0) {
        $_SESSION['Error_field'] = 'email'; // เก็บฟิลด์ที่เกิดข้อผิดพลาด
        $_SESSION['form_data']['email'] = $email; // เก็บค่าอีเมลล์ที่กรอก
        $_SESSION['error_message'] = 'อีเมลล์ถูกใช้ไปแล้ว!! กรุณาใช้อีเมลล์อื่น';
        header("Location: reg.php");
        exit();
    }

    // ตรวจสอบเบอร์โทรศัพท์ที่มีอยู่แล้ว
    $phone_check_query = "SELECT * FROM tb_member WHERE telephone='$phone' LIMIT 1";
    $result_phone_check = mysqli_query($conn, $phone_check_query);
    if (mysqli_num_rows($result_phone_check) > 0) {
        $_SESSION['Error_field'] = 'phone'; // เก็บฟิลด์ที่เกิดข้อผิดพลาด
        $_SESSION['form_data']['phone'] = $phone; // เก็บค่าเบอร์โทรศัพท์ที่กรอก
        $_SESSION['error_message'] = 'เบอร์โทรศัพท์นี้ถูกใช้ไปแล้ว!! กรุณาใช้เบอร์โทรศัพท์อื่น';
        header("Location: reg.php");
        exit();
    }


    // ตรวจสอบรหัสผ่านที่ตรงกัน
    if ($password !== $confirmPassword) {
        $_SESSION['Error'] = "รหัสผ่านไม่ตรงกัน";
        $_SESSION['Error_field'] = 'psw'; // กำหนดฟิลด์ที่มีปัญหา
        header('Location: reg.php');
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $insert_query = "INSERT INTO tb_member(prefix, firstname, lastname, telephone, address, email, username, password, status) 
                     VALUES ('$prefix', '$fname', '$lname', '$phone', '$address', '$email', '$username', '$hashed_password', '0')";

    $result = mysqli_query($conn, $insert_query);

    if ($result) {
        // ดึงข้อมูลผู้ใช้ที่เพิ่งสมัครใหม่
        $user_query = "SELECT * FROM tb_member WHERE username='$username' LIMIT 1";
        $result_user = mysqli_query($conn, $user_query);
        $user = mysqli_fetch_assoc($result_user);

        // ตั้งค่าตัวแปรเซสชันสำหรับผู้ใช้ที่เข้าสู่ระบบ
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['prefix'] = $user['prefix'];
        $_SESSION['fname'] = $user['firstname'];
        $_SESSION['lname'] = $user['lastname'];
        $_SESSION['phone'] = $user['telephone'];
        $_SESSION['address'] = $user['address'];
        $_SESSION['status'] = $user['status'];

        // เปลี่ยนเส้นทางไปยังหน้าหลักหรือแดชบอร์ด
        $_SESSION['reg_success'] = "สมัครสำเร็จ";
        header('Location: index.php');
        exit();
    } else {
        // แสดงข้อความข้อผิดพลาดของ MySQL
        $_SESSION['Error'] = "เกิดข้อผิดพลาดในการลงทะเบียน: " . mysqli_error($conn);
        echo "Error: " . $insert_query . "<br>" . mysqli_error($conn); // เพิ่มการแสดงผลข้อผิดพลาด
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['Error'] = "Invalid request method.";
    header('Location: index.php');
    exit();
}

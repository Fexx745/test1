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
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['conpsw']);


    // Check for existing username
    $username_check_query = "SELECT * FROM tb_member WHERE username='$username' LIMIT 1";
    $result_username_check = mysqli_query($conn, $username_check_query);
    if (mysqli_num_rows($result_username_check) > 0) {
        $_SESSION['Error'] = "<p>ชื่ผู้ใช้นี้ถูกใช้ไปแล้ว!!</p>";
        header('Location: reg.php'); // Redirect back to registration page
        exit();
    }

    // Check for existing phone number
    $phone_check_query = "SELECT * FROM tb_member WHERE telephone='$phone' LIMIT 1";
    $result_phone_check = mysqli_query($conn, $phone_check_query);
    if (mysqli_num_rows($result_phone_check) > 0) {
        $_SESSION['Error'] = "<p>เบอร์โทรศัพท์นี้ถูกใช้ไปแล้ว!!</p>";
        header('Location: reg.php');
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['Error'] = "รหัสผ่านไม่ตรงกัน";
        header('Location: reg.php');
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $insert_query = "INSERT INTO tb_member(prefix, firstname, lastname, telephone, address, email, username, password, status) 
                     VALUES ('$prefix', '$fname', '$lname', '$phone', '$address', '$email', '$username', '$hashed_password', '0')";

    $result = mysqli_query($conn, $insert_query);

    if ($result) {
        $_SESSION['reg_success'] = "สมัครสมาชิกสำเร็จ";
        header('Location: reg.php');
        exit();
    } else {
        $_SESSION['Error'] = "Registration failed. Please try again.";
        header('Location: reg.php');
        exit();
    }

} else {
    $_SESSION['Error'] = "Invalid request method.";
    header('Location: reg.php');
    exit();
}


?>

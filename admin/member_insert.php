<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection file
    session_start();
    include('condb.php');

    // Collect user registration data from the form
    $prefix = $_POST['prefix'];
    $status = $_POST['status'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $telephone = $_POST['telephone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['psw'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    // Check if telephone number is valid
    if (strlen($telephone) > 10) {
        $_SESSION['Error'] = "หมายเลขโทรศัพท์ต้องไม่เกิน 10 ตัวอักษร";
        header('Location: member_List.php');
        exit();
    }

    // Check if telephone number is unique
    $checkTelephoneQuery = "SELECT * FROM tb_member WHERE telephone = '$telephone'";
    $resultTelephone = mysqli_query($conn, $checkTelephoneQuery);

    if (mysqli_num_rows($resultTelephone) > 0) {
        $_SESSION['Error'] = "หมายเลขโทรศัพท์นี้ถูกใช้แล้ว";
        header('Location: member_List.php');
        exit();
    }

    // Check if username is unique
    $checkUsernameQuery = "SELECT * FROM tb_member WHERE username = '$username'";
    $resultUsername = mysqli_query($conn, $checkUsernameQuery);

    if (mysqli_num_rows($resultUsername) > 0) {
        $_SESSION['Error'] = "ชื่อผู้ใช้นี้ถูกใช้แล้ว!";
        header('Location: member_List.php');
        exit();
    }

    // Check if email is unique
    $checkEmailQuery = "SELECT * FROM tb_member WHERE email = '$email'";
    $resultEmail = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($resultEmail) > 0) {
        $_SESSION['Error'] = "อีเมลนี้ถูกใช้แล้ว!";
        header('Location: member_List.php');
        exit();
    }

    // Insert user data into the database
    $insertUserQuery = "INSERT INTO tb_member (prefix, firstname, lastname, telephone, address, email, username, password, status)
                        VALUES ('$prefix', '$fname', '$lname', '$telephone', '$address', '$email', '$username', '$hashedPassword', '$status')";

    if (mysqli_query($conn, $insertUserQuery)) {
        $_SESSION['addaccount'] = "เพิ่มผู้ใช้";
        header('Location: member_add.php');
        exit();
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลงทะเบียน!');</script>";
    }

    mysqli_close($conn);
} else {
    echo "Invalid request method.";
}
?>

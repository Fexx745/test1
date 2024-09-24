<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    include('condb.php');

    // Retrieve values from the form
    $id = $_POST['id'];
    $prefix = $_POST['prefix'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $status = $_POST['status'];

    // Check if the new phone number is unique
    $checkPhoneQuery = "SELECT * FROM tb_member WHERE telephone='$phone' AND id != $id";
    $resultPhone = $conn->query($checkPhoneQuery);

    if ($resultPhone->num_rows > 0) {
        $_SESSION['Error'] = "หมายเลขโทรศัพท์นี้ถูกใช้แล้ว!";
        header('Location: member_edit.php?id=' . $id);
        exit();
    }

    // Update the record in the database
    $sql = "UPDATE tb_member SET 
        prefix='$prefix', 
        firstname='$fname', 
        lastname='$lname', 
        telephone='$phone', 
        address='$address', 
        status='$status' 
        WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['editaccount'] = "แก้ไขผู้ใช้เรียบร้อยแล้ว";
        header('Location: member_List.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>

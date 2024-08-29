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

    // Update the record in the database
    $sql = "UPDATE tb_member SET prefix='$prefix', firstname='$fname', lastname='$lname', telephone='$phone', address='$address', status='$status' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['editaccount'] = "แก้ไขผู้ใช้";
        header('Location: member_List.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>

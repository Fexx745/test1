<?php
session_start();
include('condb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission

    // Sanitize input data
    $pid = mysqli_real_escape_string($conn, $_POST['pid']);
    $pname = mysqli_real_escape_string($conn, $_POST['pname']);
    $detail = mysqli_real_escape_string($conn, $_POST['detail']);
    $typeprd = mysqli_real_escape_string($conn, $_POST['typeprd']);
    $unittype = mysqli_real_escape_string($conn, $_POST['unittype']);
    $brandprd = mysqli_real_escape_string($conn, $_POST['brandprd']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);

    // Check if the new product name already exists
    $checkQuery = "SELECT * FROM product WHERE p_name = '$pname' AND p_id != '$pid'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $_SESSION['error'] = "ชื่อสินค้านี้มีอยู่แล้ว กรุณาใช้ชื่อใหม่";
        header('Location: product_edit.php?id=' . $pid);
        exit();
    }

    // Check if a new image file is uploaded
    if ($_FILES['image']['name']) {
        // Process image upload
        $targetDir = "../assets/images/product/";
        $targetFile = $targetDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);

        // Update database with new image filename
        $image = mysqli_real_escape_string($conn, $_FILES['image']['name']);
        $updateImageQuery = "UPDATE product SET image = '$image' WHERE p_id = '$pid'";
        mysqli_query($conn, $updateImageQuery);
    }

    // Update product information in the database
    $updateQuery = "UPDATE product SET p_name = '$pname', detail = '$detail', type_id = '$typeprd', unit_id = '$unittype', brand_id = '$brandprd', amount = '$amount' WHERE p_id = '$pid'";
    $result = mysqli_query($conn, $updateQuery);

    if ($result) {
        $_SESSION['edit_product'] = "แก้ไขสินค้าเรียบร้อยแล้ว";
        header('Location: product_edit.php?id=' . $pid);
        exit();
    } else {
        echo "Error updating product information: " . mysqli_error($conn);
    }
} else {
    // Redirect if accessed directly without form submission
    header("Location: product_List.php");
    exit();
}
?>

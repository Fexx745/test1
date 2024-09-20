<?php
session_start();
include('condb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p_name = $_POST['p_name'];
    $detail = $_POST['detail'];
    $typeprd = $_POST['typeprd'];
    $unittype = $_POST['unittype'];
    $brandprd = $_POST['brandprd'];
    $price = $_POST['price'];
    $amount = $_POST['amount'];

    if ($_FILES['fileimage']['name']) {
        $targetDir = "../assets/images/product/";
        $targetFile = $targetDir . basename($_FILES['fileimage']['name']);
        move_uploaded_file($_FILES['fileimage']['tmp_name'], $targetFile);
        $image = $_FILES['fileimage']['name'];
    } else {
        $image = '';
    }

    $insertProductQuery = "INSERT INTO product (p_name, detail, type_id, unit_id, brand_id, amount, image) VALUES ('$p_name', '$detail', '$typeprd', '$unittype', '$brandprd', '$amount', '$image')";
    $resultProduct = mysqli_query($conn, $insertProductQuery);

    if ($resultProduct) {
        $productId = mysqli_insert_id($conn);
        $insertPriceQuery = "INSERT INTO price_history (p_id, from_date, price) VALUES ('$productId', NOW(), '$price')";
        $resultPrice = mysqli_query($conn, $insertPriceQuery);

        if ($resultPrice) {
            $_SESSION['addproduct'] = "เพิ่มสินค้าสำเร็จ";
            header('Location: product_add.php');
            exit();
        } else {
            echo "Error inserting price information: " . mysqli_error($conn);
        }
    } else {
        echo "Error inserting product information: " . mysqli_error($conn);
    }


} else {
    header("Location: index.php");
    exit();
}
?>
<?php
session_start();
if (isset($_GET['id'])) {
    include('condb.php');

    $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Delete associated records in price_history table
    $delete_price_query = "DELETE FROM price_history WHERE p_id = '$product_id'";
    mysqli_query($conn, $delete_price_query);

    // Delete the product record
    $delete_query = "DELETE FROM product WHERE p_id = '$product_id'";

    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['delete_product'] = "ลบสินค้า";
        header('Location: prd_show_product.php');
        exit();
    } else {
        echo "<script> alert('เกิดข้อผิดพลาดในการลบสินค้า!'); 
            window.location='prd_show_product.php'; </script>";
    }

    mysqli_close($conn);
} else {
    echo "Product ID not provided.";
}

?>

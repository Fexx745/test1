<?php
session_start();
if (isset($_GET['id'])) {
    include('condb.php');

    $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Check if the product is in the tb_order_detail table
    $check_order_detail_query = "SELECT COUNT(*) AS count FROM tb_order_detail WHERE p_id = '$product_id'";
    $result = mysqli_query($conn, $check_order_detail_query);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        $_SESSION['error_delete'] = "ไม่สามารถลบสินค้าได้ เนื่องจากมีรายการสั่งซื้อสินค้ารายการนี้!";
        header('Location: product_List.php');
        exit();
    } else {
        // Get the image file name from the product table
        $get_image_query = "SELECT image FROM product WHERE p_id = '$product_id'";
        $image_result = mysqli_query($conn, $get_image_query);
        $image_row = mysqli_fetch_assoc($image_result);

        // Set the image path
        $image_path = '../assets/images/product/' . $image_row['image'];

        // Check if the image file exists and delete it
        if (file_exists($image_path)) {
            unlink($image_path);  // Delete the image file
        }

        // Delete associated records in price_history table
        $delete_price_query = "DELETE FROM price_history WHERE p_id = '$product_id'";
        mysqli_query($conn, $delete_price_query);

        // Delete the product record
        $delete_query = "DELETE FROM product WHERE p_id = '$product_id'";

        if (mysqli_query($conn, $delete_query)) {
            $_SESSION['delete_product'] = "ลบสินค้าและรูปภาพเรียบร้อยแล้ว";
            header('Location: product_List.php');
            exit();
        } else {
            echo "<script> alert('เกิดข้อผิดพลาดในการลบสินค้า!'); 
                   window.location='product_List.php'; </script>";
        }
    }

    mysqli_close($conn);
} else {
    echo "Product ID not provided.";
}
?>

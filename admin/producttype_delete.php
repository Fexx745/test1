<?php
session_start();
if (isset($_GET['id'])) {
    include('condb.php');

    $type_id = $_GET['id'];

    // Check if there are related products
    $check_query = "SELECT COUNT(*) AS count FROM product WHERE type_id = '$type_id'";
    $result = mysqli_query($conn, $check_query);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        $_SESSION['delete_error'] = "Cannot delete product type: related products exist.";
        header('Location: producttype_List.php');
    } else {
        // Delete the product type
        $delete_query = "DELETE FROM product_type WHERE type_id = '$type_id'";
        if (mysqli_query($conn, $delete_query)) {
            $_SESSION['delete_typeproduct'] = "ลบประเภทสินค้า";
            header('Location: producttype_List.php');
        } else {
            echo "Error deleting product type: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
} else {
    echo "Product type ID not provided.";
}
?>

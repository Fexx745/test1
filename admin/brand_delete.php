<?php
session_start();
if (isset($_GET['id'])) {
    include('condb.php');

    $brand_id = $_GET['id'];

    // Check if there are related products
    $check_query = "SELECT COUNT(*) AS count FROM product WHERE brand_id = '$brand_id'";
    $result = mysqli_query($conn, $check_query);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        $_SESSION['delete_error'] = "Cannot delete brand: related products exist.";
        header('Location: brand_add.php');
        exit();
    } else {
        // Delete the brand type
        $delete_query = "DELETE FROM brand_type WHERE brand_id = '$brand_id'";
        if (mysqli_query($conn, $delete_query)) {
            $_SESSION['deletebrand'] = "ลบยี่ห้อสินค้า";
            header('Location: brand_add.php');
            exit();
        } else {
            echo "Error deleting brand: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
} else {
    echo "Brand ID not provided.";
}
?>

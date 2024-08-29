<?php
session_start();
if (isset($_GET['id'])) {
    include('condb.php');

    $unit_id = $_GET['id'];

    // Check if there are related products
    $check_query = "SELECT COUNT(*) AS count FROM product WHERE unit_id = '$unit_id'";
    $result = mysqli_query($conn, $check_query);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        // ลบไม่ได้เนื่องจากมีหน่วยนี้ในสินค้าไหนสินค้าหนึ่ง กรุณาไปเปลี่ยนหน่วยสินค้านั้นเป็นหน่วยอื่น
        $_SESSION['error_unit_delete'] = "ลบหน่วยสินค้าไม่สำเร็จ";
        header('Location: unit_add.php');
    } else {
        $delete_query = "DELETE FROM unit_type WHERE unit_id = '$unit_id'";
        if (mysqli_query($conn, $delete_query)) {
            $_SESSION['deleteunit'] = "ลบหน่วยสินค้า";
            header('Location: unit_add.php');
            exit();
        } else {
            echo "Error deleting unit: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
} else {
    echo "unit ID not provided.";
}
?>

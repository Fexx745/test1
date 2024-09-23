<?php
include('condb.php');

if (isset($_POST['typename'])) {
    $typename = mysqli_real_escape_string($conn, $_POST['typename']);

    // Check if the product type exists
    $query = "SELECT * FROM product_type WHERE type_name = '$typename'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Product type exists
        echo "exists";
    } else {
        // Product type is available
        echo "available";
    }
}
?>

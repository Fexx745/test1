<?php
include('condb.php');

if (isset($_POST['p_name'])) {
    $p_name = $_POST['p_name'];

    // Query to check if the product name exists
    $query = "SELECT * FROM product WHERE p_name = '$p_name'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Name already exists
        echo "taken";
    } else {
        // Name is available
        echo "available";
    }
}
?>

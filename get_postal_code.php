<?php
include('condb.php');

if (isset($_POST['subdistrict_id'])) {
    $subdistrict_id = $_POST['subdistrict_id'];
    $subdistrict_id = mysqli_real_escape_string($conn, $subdistrict_id);

    $query = "SELECT zip_code FROM districts WHERE id = '$subdistrict_id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $row = mysqli_fetch_assoc($result);
    echo $row['zip_code'];

    mysqli_close($conn);
} else {
    echo "Postal code not found";
}
?>

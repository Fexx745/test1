<?php
include('condb.php'); // Ensure this file contains the correct DB connection code

// Check if province_id is set in POST request
if (isset($_POST['province_id'])) {
    $province_id = $_POST['province_id'];

    // Escape the input to prevent SQL injection
    $province_id = mysqli_real_escape_string($conn, $province_id);

    // Query to fetch districts (amphures) based on province_id
    $query = "SELECT * FROM amphures WHERE province_id = '$province_id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Generate dropdown options
    echo "<option value='' selected disabled hidden>** เลือกอำเภอ **</option>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "<option value='".$row['id']."'>".$row['name_th']."</option>"; // Displaying the English name of the district
    }

    // Close the connection
    mysqli_close($conn);
} else {
    echo "<option value='' selected disabled hidden>** เลือกอำเภอ **</option>";
}
?>

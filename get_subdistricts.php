<?php
include('condb.php'); // Ensure this file contains the correct DB connection code

if (isset($_POST['district_id'])) {
    $amphure_id = $_POST['district_id'];
    $amphure_id = mysqli_real_escape_string($conn, $amphure_id);

    // Fetch subdistricts based on selected district_id
    $query = "SELECT * FROM districts WHERE amphure_id = '$amphure_id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    echo "<option value='' selected disabled hidden>** เลือกตำบล **</option>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "<option value='".$row['id']."'>".$row['name_th']."</option>";
    }

    mysqli_close($conn);
} else {
    echo "<option value='' selected disabled hidden>** เลือกตำบล **</option>";
}
?>

<?php
// Connect to database
include('condb.php');
// Get the input value from the request
$fname = $_POST['fname'];

// Check if the input value already exists in the database
$query = "SELECT * FROM tb_member WHERE firstname = '$fname'";
$result = mysqli_query($conn, $query);

// Check if the query returned any results
if (mysqli_num_rows($result) > 0) {
    echo "duplicate";
} else {
    echo "unique";
}

// Close the database connection
mysqli_close($conn);
?>
<?php
include('condb.php'); // Your database connection

$query = "SELECT * FROM provinces ORDER BY name_th COLLATE utf8_general_ci";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Loop through the result and generate options
echo "<option value='' selected disabled hidden>** เลือกจังหวัด **</option>"; // Default option
while($row = mysqli_fetch_assoc($result)) {
    echo "<option value='".$row['id']."'>".$row['name_th']."</option>";
}

// Close the connection
mysqli_close($conn);
?>

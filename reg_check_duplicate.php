<?php
include('condb.php');

$input_value = mysqli_real_escape_string($conn, $_POST['input_value']);
$input_name = mysqli_real_escape_string($conn, $_POST['input_name']);

$query = "";

if ($input_name == "email") {
    $query = "SELECT * FROM tb_member WHERE email='$input_value'";
} elseif ($input_name == "phone") {
    $query = "SELECT * FROM tb_member WHERE telephone='$input_value'";
} elseif ($input_name == "username") {
    $query = "SELECT * FROM tb_member WHERE username='$input_value'";
}

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo "duplicate";
} else {
    echo "unique";
}

mysqli_close($conn);
?>

<?php
if (isset($_GET['id']) && isset($_GET['name'])) {
    $id = $_GET['id'];
    $name = $_GET['name'];

    // ปรับการเชื่อมต่อฐานข้อมูลตามความเหมาะสม
    include('condb.php');

    $sql = "UPDATE shipping_type SET shipping_type_name = '$name' WHERE shipping_type_id = $id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }

    mysqli_close($conn);
}
?>

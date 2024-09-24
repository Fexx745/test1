<?php
include('condb.php');
session_start();

if (isset($_GET['type']) && isset($_GET['value'])) {
    $type = $_GET['type'];
    $value = $_GET['value'];

    if ($type === 'email') {
        $query = "SELECT * FROM tb_member WHERE email = ?";
    } elseif ($type === 'phone') {
        $query = "SELECT * FROM tb_member WHERE telephone = ?";
    } else {
        echo json_encode(['exists' => false]);
        exit();
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $value);
    $stmt->execute();
    $result = $stmt->get_result();

    echo json_encode(['exists' => $result->num_rows > 0]);
    exit();
}
?>

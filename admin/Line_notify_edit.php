<?php
session_start();
include('condb.php');

if (!isset($_SESSION['username']) || $_SESSION['status'] !== '1') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id']) && isset($_GET['name'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $newToken = mysqli_real_escape_string($conn, $_GET['name']);

    // Update the token in the database
    $sql = "UPDATE tb_tokens SET token = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newToken, $id);

    if ($stmt->execute()) {
        // $_SESSION['edittoken'] = true;
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

mysqli_close($conn);

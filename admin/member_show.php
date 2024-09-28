<?php
session_start();
include('condb.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['status'] !== '1') {
    header('Location: ../login.php');
    exit();
}

// Get the member ID from the request
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input

    // Update the member's status to 3 (hidden)
    $sql = "UPDATE tb_member SET status = 0 WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['showaccount'] = true; // Set a session variable to show success message
    }
}

// Redirect back to the member management page
header('Location: member_List.php');
exit();
?>

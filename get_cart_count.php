<?php
session_start();

// Get the current cart count from the session
$count = isset($_SESSION['inPro']) ? $_SESSION['inPro'] : 0;

// Return the cart count as a JSON response
echo json_encode($count);

?>
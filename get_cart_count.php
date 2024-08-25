<?php
session_start();
$count = isset($_SESSION['inPro']) ? $_SESSION['inPro'] : 0;
echo json_encode($count);
?>
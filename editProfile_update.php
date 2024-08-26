<?php
include('condb.php');
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
} else if ($_SESSION['status'] !== '0') {
    header('Location: login.php');
    exit();
}

$message = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $id = $_POST['id'];
    $prefix = $_POST['prefix'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];

    // Validate and sanitize inputs
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $prefix = filter_var($prefix, FILTER_SANITIZE_STRING);
    $fname = filter_var($fname, FILTER_SANITIZE_STRING);
    $lname = filter_var($lname, FILTER_SANITIZE_STRING);
    $phone = filter_var($phone, FILTER_SANITIZE_STRING);
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Update the user data in the database using prepared statements
    $sql = "UPDATE tb_member SET prefix=?, firstname=?, lastname=?, telephone=?, address=?, email=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $prefix, $fname, $lname, $phone, $address, $email, $id);

    if ($stmt->execute()) {
        // Update session variables
        $_SESSION['prefix'] = $prefix;
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
        $_SESSION['phone'] = $phone;
        $_SESSION['address'] = $address;
        $_SESSION['email'] = $email;

        // Store custom session variable
        $_SESSION['submit_edit_profile'] = true;

        // Redirect to edit-profile.php
        header("Location: editProfile.php?id=" . htmlspecialchars($id));
        exit();
    } else {
        $message = "Error updating record: " . $conn->error;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account</title>
</head>
<body>
<?php
    // Display error message if any
    if ($message) {
        echo "<script>alert('$message');</script>";
    }
?>
</body>
</html>

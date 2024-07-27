<?php
include('condb.php');
session_start();

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
    $username = $_POST['username'];

    // Validate and sanitize inputs
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $prefix = filter_var($prefix, FILTER_SANITIZE_STRING);
    $fname = filter_var($fname, FILTER_SANITIZE_STRING);
    $lname = filter_var($lname, FILTER_SANITIZE_STRING);
    $phone = filter_var($phone, FILTER_SANITIZE_STRING);
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $username = filter_var($username, FILTER_SANITIZE_STRING);

    // Ensure required fields are not empty
    // if (empty($id) || empty($prefix) || empty($fname) || empty($lname) || empty($phone) || empty($address) || empty($email) || empty($username)) {
    //     echo "All fields are required!";
    //     exit();
    // }

    // Update the user data in the database using prepared statements
    $sql = "UPDATE tb_member SET prefix=?, firstname=?, lastname=?, telephone=?, address=?, email=?, username=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $prefix, $fname, $lname, $phone, $address, $email, $username, $id);

    if ($stmt->execute()) {
        // Update session variables
        $_SESSION['prefix'] = $prefix;
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
        $_SESSION['phone'] = $phone;
        $_SESSION['address'] = $address;
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $username;

        $message = "Account updated successfully!";
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
    <script>
        window.onload = function() {
            var message = "<?php echo $message; ?>";
            if (message) {
                alert(message);
                window.location.href = "edit-profile.php?id=<?php echo htmlspecialchars($id); ?>";
            }
        }
    </script>
</head>
<body>
</body>
</html>

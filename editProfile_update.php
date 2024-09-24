<?php
include('condb.php');
session_start();

// Redirect user if not logged in or not an active user
if (!isset($_SESSION['username']) || $_SESSION['status'] !== '0') {
    header('Location: login.php');
    exit();
}

$message = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // Check for unique phone number
    $phoneCheckSql = "SELECT COUNT(*) FROM tb_member WHERE telephone = ? AND id != ?";
    $phoneCheckStmt = $conn->prepare($phoneCheckSql);
    $phoneCheckStmt->bind_param("si", $phone, $id);
    $phoneCheckStmt->execute();
    $phoneCheckStmt->bind_result($phoneCount);
    $phoneCheckStmt->fetch();
    $phoneCheckStmt->close();

    // Check for unique email
    $emailCheckSql = "SELECT COUNT(*) FROM tb_member WHERE email = ? AND id != ?";
    $emailCheckStmt = $conn->prepare($emailCheckSql);
    $emailCheckStmt->bind_param("si", $email, $id);
    $emailCheckStmt->execute();
    $emailCheckStmt->bind_result($emailCount);
    $emailCheckStmt->fetch();
    $emailCheckStmt->close();

    // If phone or email already exists, set error message
    if ($phoneCount > 0) {
        $_SESSION['message'] = "เบอร์โทรศัพท์นี้ถูกใช้ไปแล้ว";
    } elseif ($emailCount > 0) {
        $_SESSION['message'] = "อีเมลล์นี้ถูกใช้ไปแล้ว";
    } else {
        // Prepare the SQL statement for updating user data
        $sql = "UPDATE tb_member SET prefix=?, firstname=?, lastname=?, telephone=?, address=?, email=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        
        // Check for statement preparation errors
        if ($stmt === false) {
            $_SESSION['message'] = "Error preparing statement: " . htmlspecialchars($conn->error);
        } else {
            $stmt->bind_param("ssssssi", $prefix, $fname, $lname, $phone, $address, $email, $id);

            // Execute the statement
            if ($stmt->execute()) {
                // Update session variables
                $_SESSION['prefix'] = $prefix;
                $_SESSION['fname'] = $fname;
                $_SESSION['lname'] = $lname;
                $_SESSION['phone'] = $phone;
                $_SESSION['address'] = $address;
                $_SESSION['email'] = $email;

                // Store custom session variable for success feedback
                $_SESSION['submit_edit_profile'] = true;

                // Redirect to edit-profile.php
                header("Location: editProfile.php?id=" . htmlspecialchars($id));
                exit();
            } else {
                $_SESSION['message'] = "Error updating record: " . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
if (isset($_SESSION['message'])) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'ข้อผิดพลาด',
            text: '" . addslashes($_SESSION['message']) . "',
            willClose: () => {
                window.location.href = 'editProfile.php?id=" . htmlspecialchars($id) . "';
            }
        });
    </script>";
    unset($_SESSION['message']); // Clear message after displaying
}
?>
</body>
</html>

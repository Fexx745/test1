<?php
include('condb.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $old_psw = $_POST['old_psw'];
    $new_psw = $_POST['new_psw'];
    $confirm_new_psw = $_POST['confirm_new_psw'];

    // Fetch the current password from the database
    $sql = "SELECT password FROM tb_member WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Verify the old password
        if (password_verify($old_psw, $user['password'])) {
            // Check if new passwords match
            if ($new_psw === $confirm_new_psw) {
                // Hash the new password
                $hashed_new_psw = password_hash($new_psw, PASSWORD_DEFAULT);

                // Update the password in the database
                $update_sql = "UPDATE tb_member SET password = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $hashed_new_psw, $user_id);
                if ($update_stmt->execute()) {
                    $_SESSION['submit_edit_psw'] = true;
                    header("Location: changepassword.php?id=" . $user_id);
                    exit();
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                $_SESSION['psw_do_not_found'] = "รหัสผ่านไม่ตรงกัน";
                header("Location: changepassword.php?id=" . $user_id);
            }
        } else {
            $_SESSION['psw_old_incorrect'] = "รหัสผ่านเก่าผิด";
            header("Location: changepassword.php?id=" . $user_id);
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
}

$conn->close();
?>

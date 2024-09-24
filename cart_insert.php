<?php
session_start();
include('condb.php');

// ตรวจสอบไฟล์สลิป
$allowed_extensions = ['jpg', 'jpeg', 'png'];
$slip_image = $_FILES['slip_image']['name'];
$extension = strtolower(pathinfo($slip_image, PATHINFO_EXTENSION));

// ตรวจสอบประเภทไฟล์
if (!in_array($extension, $allowed_extensions)) {
    $_SESSION['upload_error'] = 'Only JPG, JPEG, and PNG files are allowed.';
    header('Location: cart.php');
    exit();
}

// อัปโหลดไฟล์สลิปไปยังโฟลเดอร์ที่กำหนด
$target_dir = "assets/images/slip_images/";
$target_file = $target_dir . basename($_FILES["slip_image"]["name"]);
if (!move_uploaded_file($_FILES["slip_image"]["tmp_name"], $target_file)) {
    $_SESSION['upload_error'] = 'Error: Unable to upload slip image.';
    header('Location: cart.php');
    exit();
}

// ดึงข้อมูลการเข้าสู่ระบบของผู้ใช้ (เช่นชื่อผู้ใช้หรืออีเมล)
$userID = $_SESSION['user_id']; // หรือใช้ข้อมูลการเข้าสู่ระบบที่คุณมีอยู่

// ดึงข้อมูล member_id จากตาราง tb_member
$sql_member = "SELECT id AS member_id, firstname, lastname FROM tb_member WHERE id = '$userID'";
$result_member = mysqli_query($conn, $sql_member);
$row_member = mysqli_fetch_assoc($result_member);
$memberID = $row_member['member_id'];
$fname = $row_member['firstname'];
$lname = $row_member['lastname'];

// ทำการ INSERT INTO ในตาราง tb_order โดยใช้ member_id ที่ได้จากขั้นตอนก่อนหน้า
$sql_insert_order = "INSERT INTO tb_order(total_price, order_status, member_id)
                    VALUES('" . $_SESSION["sum_price"] . "', '1', '$memberID')";
mysqli_query($conn, $sql_insert_order);
$orderID = mysqli_insert_id($conn);
$_SESSION["order_id"] = $orderID;

// Fetch sToken from the database
$sql_token = "SELECT token FROM tb_tokens ORDER BY id DESC LIMIT 1";
$result_token = mysqli_query($conn, $sql_token);
$row_token = mysqli_fetch_assoc($result_token);
$sToken = $row_token['token'];

$sMessage = "มีรายการสั่งซื้อเข้าใหม่!\n";
$sMessage .= "เลขที่สั่งซื้อ: " . sprintf("%010d", $_SESSION["order_id"]) . "\n";
$sMessage .= "ชื่อผู้สั่งซื้อ: " . $fname . " " . $lname . "\n";
$sMessage .= "ราคาสุทธิ: " . number_format($_SESSION["sum_price"], 2) . " บาท\n";

$chOne = curl_init();
curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($chOne, CURLOPT_POST, 1);
curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . urlencode($sMessage));
$headers = array(
    'Content-type: application/x-www-form-urlencoded',
    'Authorization: Bearer ' . $sToken,
);
curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($chOne);

$logStatus = 'unknown'; // ตั้งค่าเริ่มต้นให้กับ $logStatus
$logMessage = '';
if (curl_error($chOne)) {
    $logStatus = 'fail';
    $logMessage = 'cURL error: ' . curl_error($chOne);
    echo $logMessage;
} else {
    $result_ = json_decode($result, true);
    $logStatus = $result_['status'] == 200 ? 'success' : 'fail';
    $logMessage = $result_['message'];
    if ($result_['status'] == 200) {
        echo "Notification sent successfully.";
    } else {
        echo "Error sending notification: " . $result_['message'];
    }
    echo "Status: " . $result_['status'] . "<br>";
    echo "Message: " . $result_['message'];
}
curl_close($chOne);

// เพิ่มข้อมูลลงในตาราง log_notify
$sql_insert_log = "INSERT INTO log_notify (member_id, orderID, message, status) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql_insert_log);
$stmt->bind_param("iiss", $userID, $orderID, $sMessage, $logStatus);
if ($stmt->execute()) {
    echo "Log inserted successfully.";
} else {
    echo "Error inserting log: " . $stmt->error;
}
$stmt->close();

// เพิ่มข้อมูลการชำระเงินลงในตาราง tb_payment
$sql_payment = "INSERT INTO tb_payment (orderID, payment_method, slip_image)
                VALUES ('$orderID', 'Transfer', '$slip_image')";
if (mysqli_query($conn, $sql_payment)) {
    echo "<script> alert('บันทึกข้อมูลการชำระเงินเรียบร้อยแล้ว') </script>";
} else {
    echo "<script> alert('Error: Unable to insert payment information.'); </script>";
}

// เพิ่มข้อมูล order_details
for ($i = 0; $i <= (int) $_SESSION["intLine"]; $i++) {
    if (!empty($_SESSION["strProductID"][$i])) {
        $sql_product = "SELECT p.*, ph.price 
                     FROM product p
                     JOIN price_history ph ON p.p_id = ph.p_id
                     WHERE p.p_id = '" . $_SESSION["strProductID"][$i] . "'";

        $result_product = mysqli_query($conn, $sql_product);

        if ($result_product && $row_product = mysqli_fetch_array($result_product)) {
            $price = $row_product['price'];
            $total = $_SESSION["strQty"][$i] * $price;

            $sql_order_detail = "INSERT INTO tb_order_detail(orderID, p_id, orderQTY, Total)
                VALUES('$orderID', '" . $_SESSION["strProductID"][$i] . "', '" . $_SESSION["strQty"][$i] . "', '$total')";

            if (mysqli_query($conn, $sql_order_detail)) {
                // Update product stock
                $sql_update_stock = "UPDATE product SET amount = amount - '" . $_SESSION["strQty"][$i] . "'
                    WHERE p_id = '" . $_SESSION["strProductID"][$i] . "'";
                mysqli_query($conn, $sql_update_stock);

                // Check stock level
                $sql_check_stock = "SELECT amount FROM product WHERE p_id = '" . $_SESSION["strProductID"][$i] . "'";
                $result_check = mysqli_query($conn, $sql_check_stock);
                if ($result_check && $row_check = mysqli_fetch_array($result_check)) {
                    $stock_amount = $row_check['amount'];
                    if ($stock_amount < 10) {
                        // Send notification for low stock
                        $low_stock_message = "สินค้า '" . $row_product['p_name'] . "' จำนวนคงเหลือต่ำกว่า 10 ชิ้นในสต็อก: " . $stock_amount . " ชิ้น";
                        
                        // Prepare to send Line notification for low stock
                        $low_stock_ch = curl_init();
                        curl_setopt($low_stock_ch, CURLOPT_URL, "https://notify-api.line.me/api/notify");
                        curl_setopt($low_stock_ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($low_stock_ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($low_stock_ch, CURLOPT_POST, 1);
                        curl_setopt($low_stock_ch, CURLOPT_POSTFIELDS, "message=" . urlencode($low_stock_message));
                        $low_stock_headers = array(
                            'Content-type: application/x-www-form-urlencoded',
                            'Authorization: Bearer ' . $sToken,
                        );
                        curl_setopt($low_stock_ch, CURLOPT_HTTPHEADER, $low_stock_headers);
                        curl_setopt($low_stock_ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_exec($low_stock_ch);
                        curl_close($low_stock_ch);
                    }
                }
            } else {
                echo "<script> alert('Error: Unable to insert order details.'); </script>";
            }
        } else {
            echo "<script> alert('Error: Product information not available.'); </script>";
        }
    }
}

mysqli_close($conn);
unset($_SESSION["intLine"]);
unset($_SESSION["strProductID"]);
unset($_SESSION["strQty"]);
unset($_SESSION["sum_price"]);
// unset($_SESSION["dePro"]);
// session_destroy(); // ถ้าใส่มันจะทำลาย SESSION ทั้งหมด ไม่ให้ส่งไปหน้าอื่น

$_SESSION['cart-success'] = "สั่งซื้อสินค้าสำเร็จ";
header('Location: cart.php');
unset($_SESSION["intLine"]);
unset($_SESSION["strProductID"]);
unset($_SESSION["strQty"]);
unset($_SESSION["sum_price"]);
unset($_SESSION["inPro"]);
unset($_SESSION["dePro"]);
exit();
?>
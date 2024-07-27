<?php
session_start();
include('condb.php');

// ดึงข้อมูลการเข้าสู่ระบบของผู้ใช้ (เช่นชื่อผู้ใช้หรืออีเมล)
$userID = $_SESSION['user_id']; // หรือใช้ข้อมูลการเข้าสู่ระบบที่คุณมีอยู่

// ดึงข้อมูล member_id จากตาราง tb_member
$sql_member = "SELECT id AS member_id FROM tb_member WHERE id = '$userID'";
$result_member = mysqli_query($conn, $sql_member);
$row_member = mysqli_fetch_assoc($result_member);
$memberID = $row_member['member_id'];

// ทำการ INSERT INTO ในตาราง tb_order โดยใช้ member_id ที่ได้จากขั้นตอนก่อนหน้า
$sql_insert_order = "INSERT INTO tb_order(total_price, order_status, member_id)
                    VALUES('" . $_SESSION["sum_price"] . "', '1', '$memberID')";
mysqli_query($conn, $sql_insert_order);
$orderID = mysqli_insert_id($conn);
$_SESSION["order_id"] = $orderID;

// เพิ่มข้อมูลการชำระเงิน
$slip_image = $_FILES['slip_image']['name']; // ชื่อไฟล์สลิป

// อัปโหลดไฟล์สลิปไปยังโฟลเดอร์ที่กำหนด
$target_dir = "assets/images/slip_images/";
$target_file = $target_dir . basename($_FILES["slip_image"]["name"]);
move_uploaded_file($_FILES["slip_image"]["tmp_name"], $target_file);

// เพิ่มข้อมูลการชำระเงินลงในตาราง tb_payment_slip
$sql_payment = "INSERT INTO tb_payment (orderID, payment_method, slip_image)
                VALUES ('$orderID', 'Transfer', '$slip_image')";
if (mysqli_query($conn, $sql_payment)) {
    echo "<script> alert('บันทึกข้อมูลการชำระเงินเรียบร้อยแล้ว') </script>";
} else {
    echo "<script> alert('Error: Unable to insert payment information.'); </script>";
}

// เพิ่มข้อมูล order_details
for ($i = 0; $i <= (int)$_SESSION["intLine"]; $i++) {
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
                $_SESSION['cart-success'] = "สั่งซื้อสินค้าสำเร็จ";
                header('Location: cart.php');
                exit();
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
// session_destroy(); // ถ้าใส่มันจะทำลาย SESSION ทั้งหมด ไม่ให้ส่งไปหน้าอื่น
?>

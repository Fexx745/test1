<?php
session_start();
include 'condb.php';

if (isset($_GET['id']) && isset($_GET['quantity'])) {
    $productId = $_GET['id'];
    $quantity = $_GET['quantity'];
    $maxQuantity = getStockQuantity($productId);

    if ($quantity > $maxQuantity) {
        echo "ไม่มีสินค้าเพียงพอในสต็อก";
        exit;
    }

    if (!isset($_SESSION["intLine"])) {
        $_SESSION["intLine"] = 0;
        $_SESSION["strProductID"][0] = $productId;
        $_SESSION["strQty"][0] = $quantity;
        header("location:cart.php");
    } else {
        $key = array_search($productId, $_SESSION["strProductID"]);
        if ((string)$key !== "") {
            // ถ้า id มีอยู่ในตะกร้า
            if ($_SESSION["strQty"][$key] + $quantity <= $maxQuantity) {
                $_SESSION["strQty"][$key] += $quantity;
            } else {
                // ถ้าสินค้าในตะกร้าเต็มแล้ว
                echo "สินค้าในตะกร้าเต็มแล้ว";
            }
        } else {
            // ถ้า id ไม่มีในตะกร้า
            if ($_SESSION["intLine"] < $maxQuantity) {
                $_SESSION["intLine"]++;
                $intNewLine = $_SESSION["intLine"];
                $_SESSION["strProductID"][$intNewLine] = $productId;
                $_SESSION["strQty"][$intNewLine] = $quantity;
            } else {
                // ถ้า stock มีน้อยกว่าที่ต้องการเพิ่ม
                echo "สินค้าหมด";
            }
        }
        header("location:cart.php");
    }
}

function getStockQuantity($productId)
{
    include 'condb.php';
    // ดึงข้อมูลจำนวน stock จากตาราง product ในฐานข้อมูล
    // ในที่นี้เป็นตัวอย่างเท่านั้น คุณต้องปรับเปลี่ยนตามโครงสร้างฐานข้อมูลของคุณ
    $sql = "SELECT amount FROM product WHERE p_id = $productId";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['amount'];
    } else {
        return 0; // หรือค่าอื่นที่คุณต้องการเมื่อไม่สามารถดึงข้อมูลได้
    }
}
?>
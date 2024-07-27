<?php
session_start();
include 'condb.php';

if (!isset($_SESSION["intLine"])) {
    $_SESSION["intLine"] = 0;
    $_SESSION["strProductID"][0] = $_GET["id"];
    $_SESSION["strQty"][0] = 1;
    header("location:cart.php");
} else {
    $key = array_search($_GET["id"], $_SESSION["strProductID"]);
    if ((string)$key !== "") {
        // ถ้า id มีอยู่ในตะกร้า
        if ($_SESSION["strQty"][$key] < getStockQuantity($_GET["id"])) {
            $_SESSION["strQty"][$key]++;
        } else {
            // ถ้าสินค้าในตะกร้าเต็มแล้ว
            echo "สินค้าในตะกร้าเต็มแล้ว";
        }
    } else {
        // ถ้า id ไม่มีในตะกร้า
        if ($_SESSION["intLine"] < getStockQuantity($_GET["id"])) {
            $_SESSION["intLine"]++;
            $intNewLine = $_SESSION["intLine"];
            $_SESSION["strProductID"][$intNewLine] = $_GET["id"];
            $_SESSION["strQty"][$intNewLine] = 1;
        } else {
            // ถ้า stock มีน้อยกว่าที่ต้องการเพิ่ม
            echo "สินค้าหมด";
        }
    }
    header("location:cart.php");
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

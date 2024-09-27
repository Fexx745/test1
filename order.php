<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
} else if ($_SESSION['status'] !== '0') {
    header('Location: login.php');
    exit();
}

include 'condb.php';

// ตรวจสอบว่าเซสชันสำหรับตะกร้าถูกสร้างแล้วหรือยัง
if (!isset($_SESSION["strProductID"])) {
    $_SESSION["strProductID"] = array(); // สร้าง array สำหรับเก็บ ID ของสินค้า
    $_SESSION["strQty"] = array(); // สร้าง array สำหรับเก็บจำนวนสินค้า
    $_SESSION["intLine"] = 0; // ตัวนับจำนวนสินค้าในตะกร้า
}

// ตรวจสอบค่า ID ของสินค้า
echo "ID ของสินค้า: " . $_GET['id'];

// ตรวจสอบค่า ID ของสินค้าในเซสชัน
echo "ID ของสินค้าในเซสชัน: ";
print_r($_SESSION["strProductID"]);

if (isset($_GET['id']) && isset($_GET['quantity'])) {
    $productId = (int)$_GET['id']; // แปลง ID เป็นจำนวนเต็ม
    $quantity = (int)$_GET['quantity']; // แปลงจำนวนเป็นจำนวนเต็ม
    $maxQuantity = getStockQuantity($productId);

    if ($quantity > $maxQuantity) {
        echo "ไม่มีสินค้าเพียงพอในสต็อก";
        exit;
    }

    // ตรวจสอบว่ามีสินค้าหรือไม่ในตะกร้า
    $key = array_search($productId, $_SESSION["strProductID"]);

    if ($key !== false) {
        // ถ้า id มีอยู่ในตะกร้า
        if ($_SESSION["strQty"][$key] + $quantity <= $maxQuantity) {
            $_SESSION["strQty"][$key] += $quantity;
        } else {
            echo "สินค้าในตะกร้าเต็มแล้ว";
        }
    } else {
        // ถ้า id ไม่มีอยู่ในตะกร้า
        if ($_SESSION["intLine"] < 100) { // สมมุติว่าจำนวนสินค้าสูงสุดในตะกร้าเป็น 100
            $_SESSION["strProductID"][$_SESSION["intLine"]] = $productId;
            $_SESSION["strQty"][$_SESSION["intLine"]] = $quantity;
            $_SESSION["intLine"]++;
        } else {
            echo "สินค้าหมด";
        }
    }
    header("location:cart.php");
}

function getStockQuantity($productId)
{
    include 'condb.php';

    // ใช้การเตรียมคำสั่ง SQL เพื่อป้องกัน SQL Injection
    $stmt = $conn->prepare("SELECT amount FROM product WHERE p_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return (int)$row['amount']; // แปลงเป็นจำนวนเต็ม
    } else {
        return 0; // ถ้าไม่พบสินค้า ให้คืนค่า 0
    }
}

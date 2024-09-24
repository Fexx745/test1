<?php
require('assets/pdf/fpdf.php');
// require('assets/pdf/makefont/makefont.php'); // ใช้ MakeFont ถ้าคุณยังไม่ได้แปลงฟอนต์

// ทำการแปลงฟอนต์ถ้ายังไม่ได้ทำ
// MakeFont('assets/pdf/font/THSarabun Bold Italic.ttf', 'cp874');

// สร้างคลาส PDF ที่สืบทอดจาก FPDF
class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('THSarabunb', '', 16); // ใช้ฟอนต์ที่เพิ่มเข้ามา
        $this->Cell(0, 10, 'Receipt', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('THSarabunb', '', 12);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function ReceiptBody($row, $orderDetails)
    {
        $this->SetFont('THSarabunb', '', 14); // ใช้ฟอนต์ที่เพิ่มเข้ามา
        $this->Cell(0, 10, 'Order ID: ' . $row['orderID'], 0, 1);

        // ใช้ฟอนต์ที่รองรับภาษาไทย
        $Customer = iconv('UTF-8', 'TIS-620//IGNORE', 'Customer: '. $row['prefix'] . " " . $row['firstname'] . " " . $row['lastname']);
        $this->Cell(0, 10, $Customer, 0, 1);
        $address = iconv('UTF-8', 'TIS-620//IGNORE', 'Shipping Address: ' . $row['address']);
        $this->Cell(0, 10, $address, 0, 1);
        $this->Cell(0, 10, 'Phone: ' . $row['telephone'], 0, 1);
        $this->Cell(0, 10, 'Total Price: ' . number_format($row['total_price'], 2), 0, 1);
        $this->Cell(0, 10, 'Order Date: ' . $row['reg'], 0, 1);

        // Table headers
        $this->Ln(10);
        $this->SetFont('THSarabunb', '', 14);
        $this->Cell(60, 10, 'Item', 1);
        $this->Cell(30, 10, 'Quantity', 1);
        $this->Cell(30, 10, 'Unit Price', 1);
        $this->Cell(40, 10, 'Total', 1);
        $this->Ln();

        // Display each product from the order details
        foreach ($orderDetails as $detail) {
            $this->Cell(60, 10, iconv('UTF-8', 'TIS-620//IGNORE', $detail['p_name']), 1);  // Product name
            $this->Cell(30, 10, $detail['orderQty'], 1);  // Quantity
            $this->Cell(30, 10, number_format($detail['unit_price'], 2), 1);  // Unit price
            $this->Cell(40, 10, number_format($detail['total'], 2), 1);  // Total
            $this->Ln();
        }
    }
}

// รับ orderID จาก URL
$orderID = $_GET['id'];
include('condb.php');

// Query เพื่อดึงข้อมูลจาก tb_order และ tb_member
$sql = "SELECT o.*, m.prefix, m.firstname, m.lastname, m.address, m.telephone 
        FROM tb_order o 
        INNER JOIN tb_member m ON o.member_id = m.id 
        WHERE o.orderID = '$orderID'";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Error in SQL query: ' . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

// Query เพื่อดึงข้อมูลจาก tb_order_detail
$sql_details = "SELECT d.orderQty, p.p_name, p.amount AS unit_price, 
               (d.orderQty * p.amount) AS total
               FROM tb_order_detail d 
               INNER JOIN product p ON d.p_id = p.p_id 
               WHERE d.orderID = '$orderID'";

$result_details = mysqli_query($conn, $sql_details);

if (!$result_details) {
    die('Error in SQL query: ' . mysqli_error($conn));
}

$orderDetails = [];
while ($detail = mysqli_fetch_assoc($result_details)) {
    $orderDetails[] = $detail;
}

$pdf = new PDF();
$pdf->AddFont('THSarabunb', '', 'THSarabunb.php');  // เพิ่มฟอนต์ที่แปลงแล้ว
$pdf->AddPage();
$pdf->ReceiptBody($row, $orderDetails);  // ส่งข้อมูล order details ไปด้วย
$pdf->Output();

?>

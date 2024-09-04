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

    function ReceiptBody($row)
    {
        $this->SetFont('THSarabunb', '', 14); // ใช้ฟอนต์ที่เพิ่มเข้ามา
        $this->Cell(0, 10, 'Order ID: ' . $row['orderID'], 0, 1);

        // ใช้ฟอนต์ที่รองรับภาษาไทย
        
        $Customer = iconv('UTF-8', 'TIS-620//IGNORE', 'Customer: '. $row['prefix'] . " " . $row['firstname'] . " " . $row['lastname']);
        $this->Cell(0, 10, $Customer, 0, 1);
        // $this->Cell(0, 10, 'Customer: ' . $row['firstname'], 0, 1);
        $address = iconv('UTF-8', 'TIS-620//IGNORE', 'Shipping Address: ' . $row['address']);
        $this->Cell(0, 10, $address, 0, 1);
        // $this->Cell(0, 10, 'Shipping Address: ' . $row['address'], 0, 1);
        $this->Cell(0, 10, 'Phone: ' . $row['telephone'], 0, 1);
        $this->Cell(0, 10, 'Total Price: ' . number_format($row['total_price'], 2), 0, 1);
        $this->Cell(0, 10, 'Order Date: ' . $row['reg'], 0, 1);

        // Table
        $this->Ln(10);
        $this->SetFont('THSarabunb', '', 14);
        $this->Cell(40, 10, 'Item', 1);
        $this->Cell(30, 10, 'Quantity', 1);
        $this->Cell(30, 10, 'Unit Price', 1);
        $this->Cell(40, 10, 'Total', 1);
        $this->Ln();

        // Sample product data (replace with actual data)
        $this->SetFont('THSarabunb', '', 14);
        $this->Cell(40, 10, 'Product A', 1);
        $this->Cell(30, 10, '2', 1);
        $this->Cell(30, 10, '500', 1);
        $this->Cell(40, 10, '1000', 1);
        $this->Ln();
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

$pdf = new PDF();
$pdf->AddFont('THSarabunb', '', 'THSarabunb.php');  // เพิ่มฟอนต์ที่แปลงแล้ว
$pdf->AddPage();
$pdf->ReceiptBody($row);
$pdf->Output();

?>
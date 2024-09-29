<?php
require('assets/pdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        // เพิ่มพื้นหลังจาง
        $this->Image('assets/background/bg1.png', 0, 0, 208, 297); // กำหนดขนาดให้ครอบคลุมทั้งหน้า A4

        // Company Logo (optional)
        $this->Image('assets/logo/logo3.jpg', 25, 5, 40);

        // Title
        $this->SetFont('THSarabun Bold', '', 24);
        $this->Cell(0, 10, iconv('UTF-8', 'TIS-620//IGNORE', 'ใบเสร็จรับเงิน'), 0, 1, 'C');
        $this->SetFont('THSarabun', '', 18);
        $this->Cell(0, 10, iconv('UTF-8', 'TIS-620//IGNORE', 'เว็บไซต์ขายผลิตภัณฑ์เพื่อการเกษตร'), 0, 1, 'C');  // Replace with your company name
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('THSarabun', '', 12);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    // ส่วนของเนื้อหา (เหมือนเดิม)
    function ReceiptBody($row, $orderDetails)
    {
        // Order Details Section
        $this->Ln(5);
        $this->SetFont('THSarabun Bold', '', 16);
        $this->SetX(25);
        $Customer = iconv('UTF-8', 'TIS-620//IGNORE', 'รหัสคำสั่งซื้อ: ' . $row['orderID']);
        $this->Cell(0, 10, $Customer, 0, 1, 'L');

        // Customer Details
        $this->SetFont('THSarabun Bold', '', 14);
        $this->SetX(25);
        $Customer = iconv('UTF-8', 'TIS-620//IGNORE', 'ชื่อลูกค้า: ' . $row['prefix'] . " " . $row['firstname'] . " " . $row['lastname']);
        $this->Cell(0, 10, $Customer, 0, 1, 'L');

        $address = iconv('UTF-8', 'TIS-620//IGNORE', 'ที่อยู่จัดส่ง: ' . $row['address']);
        $this->SetX(25);
        $this->Cell(0, 10, $address, 0, 1, 'L');
        $this->SetX(25);
        $this->Cell(0, 10, iconv('UTF-8', 'TIS-620//IGNORE', 'เบอร์โทรศัพท์: ' . $row['telephone']), 0, 1, 'L');
        $this->SetX(25);
        $this->Cell(0, 10, iconv('UTF-8', 'TIS-620//IGNORE', 'ยอดรวม: ' . number_format($row['total_price'], 2)) . iconv('UTF-8', 'TIS-620//IGNORE', ' บาท'), 0, 1, 'L');
        $this->SetX(25);
        $this->Cell(0, 10, iconv('UTF-8', 'TIS-620//IGNORE', 'วันที่สั่งซื้อ: ' . $row['reg']), 0, 1, 'L');

        // Table Headers
        $this->Ln(10);
        $this->SetFillColor(230, 230, 230);  // Light grey background for header
        $this->SetFont('THSarabun Bold', '', 14);

        // Center the table
        $tableWidth = 160;
        $this->SetX(($this->w - $tableWidth) / 2);

        // Create Table Header
        $this->Cell(60, 10, iconv('UTF-8', 'TIS-620//IGNORE', 'สินค้า'), 1, 0, 'C', true);
        $this->Cell(30, 10, iconv('UTF-8', 'TIS-620//IGNORE', 'จำนวน'), 1, 0, 'C', true);
        $this->Cell(30, 10, iconv('UTF-8', 'TIS-620//IGNORE', 'ราคาต่อหน่วย'), 1, 0, 'C', true);
        $this->Cell(40, 10, iconv('UTF-8', 'TIS-620//IGNORE', 'รวม'), 1, 1, 'C', true);

        // Product Details
        $this->SetFont('THSarabun', '', 14);
        foreach ($orderDetails as $detail) {
            $this->SetX(($this->w - $tableWidth) / 2);
            $this->Cell(60, 10, iconv('UTF-8', 'TIS-620//IGNORE', $detail['p_name']), 1);
            $this->Cell(30, 10, $detail['orderQty'], 1, 0, 'C');
            $this->Cell(30, 10, number_format($detail['unit_price'], 2), 1, 0, 'R');
            $this->Cell(40, 10, number_format($detail['total'], 2), 1, 1, 'R');
        }

        // Total Price at the bottom
        $this->SetFont('THSarabun Bold', '', 14);
        $this->SetX(($this->w - $tableWidth) / 2);
        $this->Cell(120, 10, iconv('UTF-8', 'TIS-620//IGNORE', 'รวมทั้งหมด'), 1, 0, 'R');
        $this->Cell(40, 10, number_format($row['total_price'], 2) . iconv('UTF-8', 'TIS-620//IGNORE', ' บาท'), 1, 1, 'R');
    }
}

// Retrieve order ID from URL
$orderID = $_GET['id'];
include('condb.php');

// Query for order and customer details
$sql = "SELECT o.*, m.prefix, m.firstname, m.lastname, m.address, m.telephone 
        FROM tb_order o 
        INNER JOIN tb_member m ON o.member_id = m.id 
        WHERE o.orderID = '$orderID'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Query for order details
$sql_details = "SELECT p.p_name, d.orderQty, ph.price AS unit_price, (d.orderQty * ph.price) AS total
FROM tb_order_detail d
INNER JOIN product p ON d.p_id = p.p_id
INNER JOIN price_history ph ON ph.p_id = p.p_id
WHERE d.orderID = '$orderID'";
$result_details = mysqli_query($conn, $sql_details);

$orderDetails = [];
while ($detail = mysqli_fetch_assoc($result_details)) {
    $orderDetails[] = $detail;
}

// Generate PDF
$pdf = new PDF();
$pdf->AddFont('THSarabun Italic', '', 'THSarabun Italic.php');
$pdf->AddFont('THSarabun Bold', '', 'THSarabun Bold.php');
$pdf->AddFont('THSarabun', '', 'THSarabun.php');
$pdf->AddPage();
$pdf->ReceiptBody($row, $orderDetails);
$pdf->Output();

<?php
require('assets/pdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->AddFont('THSarabun','','THSarabun.php');
        $this->SetFont('THSarabun','',16);
        $this->Cell(0, 10, 'ใบเสร็จรับเงิน', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('THSarabun','',12);
        $this->Cell(0, 10, 'หน้า ' . $this->PageNo(), 0, 0, 'C');
    }

    function ReceiptBody($orderID, $customerName, $address, $telephone, $totalPrice, $orderDate)
    {
        $this->AddFont('THSarabun','','THSarabun.php');
        $this->SetFont('THSarabun','',14);
        $this->Cell(0, 10, 'เลขที่ใบสั่งซื้อ: ' . $orderID, 0, 1);
        $this->Cell(0, 10, 'ลูกค้า: ' . $customerName, 0, 1);
        $this->Cell(0, 10, 'ที่อยู่จัดส่งสินค้า: ' . $address, 0, 1);
        $this->Cell(0, 10, 'เบอร์โทรศัพท์: ' . $telephone, 0, 1);
        $this->Cell(0, 10, 'ราคารวมสุทธิ: ' . $totalPrice, 0, 1);
        $this->Cell(0, 10, 'วันที่สั่งซื้อ: ' . $orderDate, 0, 1);
    }
}

$orderID = $_GET['id'];
// เชื่อมต่อฐานข้อมูลและดึงข้อมูลคำสั่งซื้อจากฐานข้อมูล
include('condb.php');
$sql = "SELECT * FROM tb_order WHERE orderID = '$orderID'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$pdf = new PDF();
$pdf->AddPage();
$pdf->ReceiptBody($row['orderID'], $row['customer_name'], $row['address'], $row['telephone'], $row['total_price'], $row['order_date']);
$pdf->Output();
?>

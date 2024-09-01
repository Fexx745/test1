<?php
require('assets/pdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Receipt', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 12);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function ReceiptBody($row)
    {
        $this->SetFont('Arial', '', 14);
        $this->Cell(0, 10, 'Order ID: ' . $row['orderID'], 0, 1);
        $this->Cell(0, 10, 'Customer: ' . $row['firstname'], 0, 1);
        $this->Cell(0, 10, 'Shipping Address: ' . $row['address'], 0, 1);
        $this->Cell(0, 10, 'Phone: ' . $row['telephone'], 0, 1);
        $this->Cell(0, 10, 'Total Price: ' . number_format($row['total_price'], 2), 0, 1);
        $this->Cell(0, 10, 'Order Date: ' . $row['reg'], 0, 1);

        // Table
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(40, 10, 'Item', 1);
        $this->Cell(30, 10, 'Quantity', 1);
        $this->Cell(30, 10, 'Unit Price', 1);
        $this->Cell(40, 10, 'Total', 1);
        $this->Ln();

        // Sample product data (replace with actual data)
        $this->SetFont('Arial', '', 14);
        $this->Cell(40, 10, 'Product A', 1);
        $this->Cell(30, 10, '2', 1);
        $this->Cell(30, 10, '500', 1);
        $this->Cell(40, 10, '1000', 1);
        $this->Ln();
    }
}

$orderID = $_GET['id'];
include('condb.php');

// Query to retrieve data from tb_order and tb_member tables
$sql = "SELECT o.*, m.firstname, m.address, m.telephone 
        FROM tb_order o 
        INNER JOIN tb_member m ON o.member_id = m.id 
        WHERE o.orderID = '$orderID'";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Error in SQL query: ' . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

$pdf = new PDF();
$pdf->AddPage();
$pdf->ReceiptBody($row);
$pdf->Output();

?>

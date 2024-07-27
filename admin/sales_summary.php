<?php
// เชื่อมต่อฐานข้อมูล
include('condb.php');

// รับค่าจากฟอร์ม
$dt1 = $_POST['dt1'];
$dt2 = $_POST['dt2'];

// ตรวจสอบว่ามีการส่งค่า dt1 และ dt2 มาจากฟอร์มหรือไม่
if (!empty($dt1) && !empty($dt2)) {
    // สร้างคำสั่ง SQL เพื่อตรวจสอบยอดขายในช่วงวันที่กำหนด
    $sql = "SELECT p.p_name, u.unit_name, SUM(od.orderQty) AS total_quantity, SUM(od.Total) AS total_price
            FROM tb_order o
            INNER JOIN tb_order_detail od ON o.orderID = od.orderID
            INNER JOIN product p ON od.p_id = p.p_id
            INNER JOIN unit_type u ON p.unit_id = u.unit_id
            WHERE o.reg BETWEEN '$dt1' AND '$dt2'
            GROUP BY p.p_name, u.unit_name
            ORDER BY total_price DESC"; // เรียงลำดับตามยอดขายสุทธิมากที่สุด

    $result = mysqli_query($conn, $sql);
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>สรุปยอดขายรายเดือน</title>
        <!-- <?php include('script-css.php'); ?> -->
        <style>
            body {
                font-family: Arial, sans-serif;
            }

            .row {
                margin-bottom: 15px;
            }

            .form-control {
                width: 100%;
                padding: 10px;
                margin: 5px 0;
                box-sizing: border-box;
            }

            .btn-primary {
                background-color: #007bff;
                color: white;
                padding: 10px 20px;
                border: none;
                cursor: pointer;
            }

            .btn-primary:hover {
                background-color: #0056b3;
            }

            .table-container {
                margin-top: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            table,
            th,
            td {
                border: 1px solid black;
            }

            th,
            td {
                padding: 15px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
        </style>
    </head>

    <body>
        <div class="table-container">
            <?php if (mysqli_num_rows($result) > 0) {
                $grand_total = 0; // ตัวแปรเก็บยอดรวมทั้งหมด 
            ?>
                <h2>สรุปยอดขายระหว่างวันที่ <?php echo $dt1; ?> ถึงวันที่ <?php echo $dt2; ?></h2>
                <table>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>จำนวนที่ขายได้</th>
                        <th>ยอดขายสุทธิ</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)) {
                        $grand_total += $row['total_price']; // รวมยอดขายสุทธิเพื่อสรุปยอดรวม 
                    ?>
                        <tr>
                            <td><?php echo $row['p_name']; ?></td>
                            <td><?php echo $row['total_quantity'] . ' ' . $row['unit_name']; ?></td>
                            <td><?php echo number_format($row['total_price'], 2); ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2" align="right"><strong>ยอดรวมสุทธิ</strong></td>
                        <td><strong><?php echo number_format($grand_total, 2); ?></strong></td>
                    </tr>
                </table>
            <?php } else { ?>
                <p>ไม่พบข้อมูลการขายในช่วงวันที่ที่กำหนด</p>
            <?php } ?>
            <a href="index.php" class="btn btn-warning">ย้อนกลับ</a>
        </div>
    </body>

    </html>
<?php
} else {
    echo "กรุณาระบุช่วงวันที่ที่ต้องการค้นหา";
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>

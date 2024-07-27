<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit(); // คำสั่งออกจากการทำงานทันทีหลังจาก redirect
} else if ($_SESSION['status'] !== '0') {
    header('Location: login.php');
    exit(); // คำสั่งออกจากการทำงานทันทีหลังจาก redirect
}
include('condb.php');
$sql = "SELECT * FROM tb_order as t INNER JOIN tb_member as tm ON t.member_id=tm.id WHERE orderID='" . $_SESSION["order_id"] . "'";
$result = mysqli_query($conn, $sql);
$rs = mysqli_fetch_array($result);
$total_price = $rs['total_price']; //รวมเงิน

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('script-css.php'); ?>
    <title>Print</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'K2D', sans-serif;
        }
    </style>
</head>

<body>

    <?php include('nav.php'); ?>
    <div class="container" style="margin-top: 100px;">
        <div class="row">
            <!-- <div class="p-3 my-5 text-white rounded-4" style="background: #6c757d;"> -->
                <div class="d-flex my-5"><h1>การสั่งซื้อสินค้นสำเร็จ</h1><h1 style="color: red;"></h1></div>
            <!-- </div> -->
        </div>
        <div class="row">
            <div class="col-md-6">
                <p><b>เลขที่การสั่งซื้อ:</b>
                    <span class="p-2 text-white rounded-5" style="background: #6f42c1;">
                        <?= $rs['orderID'] ?>
                    </span>
                </p>
                <p><b>ชื่อ-นามสกุล:</b>
                        <?= $rs['firstname'] . ' ' . $rs['lastname'] ?>
                </p>
                <p>
                    <b>ที่อยู่การจัดส่ง:</b>
                    <?= $rs['address'] ?>
                </p>
                <p>
                    <b>เบอร์โทรศัพท์:</b>
                    <?= $rs['telephone'] ?>
                </p>
            </div>

            <div class="col-md-6">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">รหัสสินค้า</th>
                            <th scope="col">ชื่อสินค้า</th>
                            <th scope="col">ราคา</th>
                            <th scope="col">จำนวน</th>
                            <th scope="col">ราคารวม</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $sql1 = "SELECT * FROM tb_order_detail AS od 
                                INNER JOIN product AS p ON od.p_id = p.p_id AND od.orderID = '" . $_SESSION["order_id"] . "'
                                INNER JOIN price_history AS ph ON p.p_id = ph.p_id";
                        $result1 = mysqli_query($conn, $sql1);
                        while ($row = mysqli_fetch_array($result1)) {
                            // ทำอะไรสักอย่างกับข้อมูลที่ดึงมาได้ตามต้องการ
                            // เช่น แสดงผลหรือประมวลผลต่อไป

                        ?>

                            <tr>
                                <td>
                                    <?= $row['p_id'] ?>
                                </td>
                                <td>
                                    <?= $row['p_name'] ?>
                                </td>
                                <td>
                                    <?= $row['price'] ?>
                                </td>
                                <td>
                                    <?= $row['orderQty'] ?>
                                </td>
                                <!-- <td><?= $row['Total'] ?></td> -->
                                <td>
                                    <?= number_format($row['Total'], 2) ?>
                                </td>
                            </tr>
                        </tbody>
                        <?php
                        }
                        unset($_SESSION["intLine"]);
                        unset($_SESSION["strProductID"]);
                        unset($_SESSION["strQty"]);
                        unset($_SESSION["sum_price"]);
                        unset($_SESSION["inPro"]);
                        unset($_SESSION["dePro"]);
                        // unset($_SESSION["dePro"]);

                        ?>
                </table>
            </div>

            <div class="col-md-12">
                <h6 class="text-end fs-5"> รวมเป็น <b class="text-danger">
                        <?= number_format($total_price, 2) ?>
                    </b> บาท</h6>
            </div>
        </div> <!-- row -->
        <div class="text-start" style="margin-bottom: 150px;">

            <a class="btn btn-primary" href="index.php">หน้าหลัก</a>
            <button onclick="window.print()" class="btn btn-success">พิมพ์</button>
        </div>
    </div> <!-- container -->

    <?php //include('footer.php'); ?>
</body>

</html>

<?php include('script-js.php'); ?>

<!-- Bootrap -->
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="bootstrap/js/bootstrap.bundle.min.js"> </script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
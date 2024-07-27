<?php
include('condb.php');
// session_start();
// if (!isset($_SESSION['username'])) {
//     header('Location: login.php');
//     exit(); // คำสั่งออกจากการทำงานทันทีหลังจาก redirect
// } else if ($_SESSION['status'] !== '0') {
//     header('Location: login.php');
//     exit(); // คำสั่งออกจากการทำงานทันทีหลังจาก redirect
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include('script-css.php'); ?>
</head>

<body>
    <?php include('nav.php'); ?>
    <div class="container" style="margin: 150px auto 200px auto;">


        <?php
        $p_id = $_GET['id'];
        $sql = "SELECT *,
                (SELECT COUNT(*) FROM tb_order_detail WHERE tb_order_detail.p_id = p.p_id) AS sales_count
                FROM product as p 
                INNER JOIN product_type as c ON p.type_id = c.type_id AND p.p_id='$p_id'
                INNER JOIN price_history as ph ON p.p_id = ph.p_id
                INNER JOIN unit_type as u ON p.unit_id = u.unit_id
                INNER JOIN brand_type as b ON p.brand_id = b.brand_id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        $check_amount = $row['amount'];

        $sql2 = "UPDATE product SET p_view=p_view+1 WHERE p_id='$p_id'";
        $result2 = mysqli_query($conn, $sql2);
        // $row2 = mysqli_fetch_array($result2); 
        ?>




        <div class="row">
            <div class="col-md-12">
                <div class="category-box">
                    <div class="cate1">
                        <img src="assets/images/other/logo.png" alt="" width="90%">
                    </div>
                    <?php
                    // คำสั่ง SQL เพื่อดึงข้อมูลทั้งหมดจากตาราง product_type
                    $sql_pd = "SELECT * FROM product_type";
                    $result_pd = mysqli_query($conn, $sql_pd);
                    ?>
                    <div class="cate2">
                        <div class="cate2-tagname">
                            <b>ค้นหาจากประเภทสินค้า</b>
                        </div>
                        <div class="cate2-typepd">
                            <ul>
                                <?php
                                while ($row_pd = mysqli_fetch_array($result_pd)) {
                                    ?>
                                    <li><a href="#"><img src="assets/images/type_product/<?= $row_pd['type_image'] ?>"
                                                alt="">
                                            <?= $row_pd['type_name'] ?>
                                        </a></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>

                    </div>
                </div> <!-- category-box -->
            </div> <!-- col-md-12 -->

            <div class="show-dt-box">
                <div class="row">
                    <div class="col-md-4">
                        <div class="show-dt-img">
                            <img src="assets/images/product/<?= $row['image'] ?>" alt="" class="mb-3">
                        </div>
                    </div>

                    <!-- <div class="col-md-8" style="background: #f8f9fa; border-radius: 0 0 0 50px; box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);"> -->
                    <div class="col-md-8">
                        <div class="show-dt-product">
                            <div class="productname">
                                <b>
                                    <?= $row['p_name'] ?>
                                </b>
                            </div>
                            <div class="detail_pd">
                                <div class="detail_name">
                                    <i class='bx bx-notepad'></i>&nbsp;<b>รายละเอียดสินค้า</b>
                                </div>
                                <div class="detailss">
                                    <p>
                                        <?= $row['detail'] ?>
                                    </p>
                                </div>
                            <hr>
                                <!-- <hr> -->
                            </div>
                            <div class="card-price">
                                <div>
                                    <p>ราคาต่อ<span class="divider" style="color: #0d6efd;">
                                            <?= $row['unit_name']; ?>
                                        </span>&nbsp;ยี่ห้อ&nbsp;<span style="color: #0d6efd;">
                                            <?= $row['brand_name']; ?>
                                        </span></p>
                                </div>
                                <div>
                                    <span><i class='bx bx-box'></i>&nbsp;เหลือสินค้าจำนวน<b
                                            style="color: <?= $row['amount'] == 0 ? '#fd7e14' : 'green' ?>">
                                            <?= $row['amount'] ?>
                                        </b>
                                        <span>
                                            <?= $row['unit_name']; ?>
                                        </span></span>
                                </div>
                                <div class="mt-1">
                                    <span style="color: "><i class='bx bxs-plane-take-off'></i> ค่าจัดส่ง : 30฿</span>
                                </div>
                                <div><span style="font-size: 20px;">ราคา</span><b style="color: red; font-size: 30px;">
                                        <?= number_format($row['price'], 2) ?>
                                    </b><span  style="font-size: 20px;">฿</span>
                                </div>
                            </div>

                            <!-- <div class="input-group my-2">
                                <span class="input-group-text"><i class='bx bx-purchase-tag-alt'></i></span>
                                <input type="text" class="form-control" name="codepromotion" placeholder="กรอกโค้ดส่วนลด...">
                            </div> -->
                            <div class="card-button">
                                <a class="btn w-50 rounded-4 fs-5" style="background: #0d6efd; color: #fff;" href="index.php"><i class='bx bx-arrow-back' ></i>&nbsp;ย้อนกลับ</a>
                                <?php if($check_amount > 0) {

                                ?>
                                <a class="btn btn-success w-50 rounded-4 fs-5"
                                    href="order.php?id=<?= $row['p_id'] ?>"><i class='bx bx-cart-add' ></i>&nbsp;เพิ่มลงตะกร้า</a>
                                <?php } else {
                                ?>
                                    <a class="btn rounded-4  w-50 fs-5 disabled" style="background: #dc3545; color: #fff;"><i class="bx bx-error-alt" ></i>&nbsp;สินค้าหมด</a>
                                <?php
                                } ?>
                            </div> <!-- card-button -->
                            <!-- จำนวนเข้าชมสินค้า -->
                            <div class="card-view-product">
                                <span><i class='bx bx-low-vision'></i> ยอดเข้าชมสินค้า <?= $row['p_view'] ?> ครั้ง</span>
                            </div>
                            <!-- จำนวนสินค้าที่ขายแล้ว -->
                            <div class="card-sold">
                                <span>ถูกซื้อแล้ว
                                    <?= $row['sales_count']; ?>
                                    ครั้ง
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- showdetail-box -->
        </div> <!-- row -->
    </div> <!-- container -->
    <?php include_once('footer.php'); ?>
</body>

</html>
<?php include_once('script-js.php'); ?>
<!-- CSS -->
<link rel="stylesheet" href="assets/css/index.css">
<link rel="stylesheet" href="assets/css/nav.css">
<link rel="stylesheet" href="assets/css/footer.css">

<!-- Bootrap -->
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="bootstrap/js/bootstrap.bundle.min.js"> </script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
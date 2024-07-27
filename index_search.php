<?php
include('config.php');
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

    <div class="container">

        <!-- Slideshow -->
        <?php include('index_slideimg.php'); ?>
        <div class="col-md-12">
            <div class="category-box">
                <div class="cate1">
                    <img src="assets/images/other/logo.png" alt="" width="90%">
                </div>
                <?php
                // คำสั่ง SQL เพื่อดึงข้อมูลทั้งหมดจากตาราง product_type
                $sql = "SELECT * FROM product_type";
                $result = mysqli_query($conn, $sql);
                ?>
                <div class="cate2">
                    <div class="cate2-tagname">
                        <b>ค้นหาจากประเภทสินค้า</b>
                    </div>
                    <div class="cate2-typepd">
                        <ul>
                            <?php
                            while ($row = mysqli_fetch_array($result)) {
                                ?>
                                <li><a href="#"><img src="assets/images/type_product/<?= $row['type_image'] ?>" alt="">
                                        <?= $row['type_name'] ?>
                                    </a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>

                </div>
            </div> <!-- category-box -->
        </div> <!-- col-md-12 -->
        <div class="product-box-search">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $search = mysqli_real_escape_string($conn, $_POST['search']);

                $sql = "SELECT *, 
                (SELECT COUNT(*) FROM tb_order_detail WHERE tb_order_detail.p_id = product.p_id) AS sales_count
                FROM product 
                INNER JOIN unit_type ON product.unit_id = unit_type.unit_id
                LEFT JOIN price_history ON product.p_id = price_history.p_id
                WHERE product.p_name LIKE '%$search%'
                ORDER BY product.p_id";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        $check_amount = $row['amount'];

                        echo '<div class="product-card">';
                        echo '<img src="assets/images/product/' . $row['image'] . '" alt="" class="mb-3">';
                        echo '<div class="productname">';
                        echo '<span><b>' . $row['p_name'] . '</b></ห>';
                        echo '</div>'; //productname
                        echo '<div class="card-price">';
                        // echo '<span>ราคาต่อ <span style="color: blue;">' . $row['unit_name'] . '</span></span>';
                        echo '<span>เหลือสินค้าจำนวน ';
                        if ($row['amount'] > 0) {
                            echo '<b style="color: green;">' . $row['amount'] . '</b> <span style="color: black;">' . $row['unit_name'] . '</span>';
                        } else {
                            echo '<b style="color: #fd7e14;">' . $row['amount'] . '</b> <span style="color: black;">' . $row['unit_name'] . '</span>';
                        }
                        echo '</span>';
                        echo '<span>ราคา <b style="color: red;">' . number_format($row['price'], 2) . '</b>&nbsp;บาท</span>';
                        echo '</div>'; //card-price
                        echo '<div class="card-button" style="margin-bottom: 20px;">';
                        if ($check_amount <= 0) {
                            echo '<a class="btn rounded-4" style="margin-right: 5px; background: #198754; color: #fff;" href="show_pd_detail.php?id=' . $row['p_id'] . '"><i class="bx bx-low-vision" ></i></a>';
                            echo '<a class="btn rounded-4 disabled" style="background: #dc3545; color: #fff;"><i class="bx bx-error-alt" ></i> สินค้าหมด</a>';
                        } else {
                            echo '<a class="btn rounded-4" style="margin-right: 5px; background: #198754; color: #fff;" href="show_pd_detail.php?id=' . $row['p_id'] . '"><i class="bx bx-low-vision" ></i></a>';
                            echo '<a class="btn rounded-4" style="background: #0d6efd; color: #fff;" href="order.php?id=' . $row['p_id'] . '"><i class="bx bx-cart-add" ></i> เพิ่มลงตะกร้า</a>';
                        }
                        echo '</div>'; // ปิด div ของ card-button
                        echo '<div class="card-view-product">';
                        echo '<span><i class="bx bx-low-vision"></i>' . $row['p_view'] . '</span>';
                        echo '</div>';
                        echo '<div class="card-sold">';
                        echo '<span>ถูกซื้อแล้ว ' . $row['sales_count'] . ' ครั้ง</span>';
                        echo '</div>';
                        echo '</div>'; // ปิด div ของ product-card                        
                    }
                } else {
                    echo '<div class="product-not-found">';
                    echo '<img src="assets/images/other/nopd.jpg">';
                    echo '<p>ไม่พบสินค้าที่คุณกำลังค้นหา ...</p>';
                    echo '</div>';
                }
            }
            ?>
            <div class="back">
                <a href="index.php" class="btn btn-info rounded-5"><i class='bx bx-arrow-back'></i>&nbsp;ย้อนกลับ</a>
            </div>
        </div> <!-- products-box -->
        <?php mysqli_close($conn) ?>
    </div> <!-- container -->
    <?php include('footer.php'); ?>
</body>

</html>
<?php include('script-js.php'); ?>
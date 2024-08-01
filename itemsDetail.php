<?php
include('condb.php');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สวัสดี</title>
    <?php include('script-css.php'); ?>

</head>

<body>
    <?php include('nav.php'); ?>
    <section class="body-container">
        <?php
        include('bc-menu.php');
        ?>

        <?php
        // ตรวจสอบว่ามีการส่งค่า id มาหรือไม่
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];

            // คำสั่ง SQL เพื่อดึงข้อมูลสินค้าตาม id
            $sql = "SELECT p.*, ph.price, u.unit_name
            FROM product p
            LEFT JOIN price_history ph ON p.p_id = ph.p_id
            LEFT JOIN unit_type u ON p.unit_id = u.unit_id
            WHERE p.p_id = $product_id";
            $result = mysqli_query($conn, $sql);

            // ตรวจสอบว่าพบข้อมูลสินค้าหรือไม่
            if ($row = mysqli_fetch_array($result)) {
        ?>
                <div class="bc-showDetail">
                    <div class="bc-showDetail-left">
                        <div class="bc-showDetail-photo">
                            <img src="assets/images/product/<?= $row['image'] ?>" alt="<?= $row['p_name'] ?>">
                        </div>
                    </div>

                    <div class="bc-showDetail-right">
                        <h1>
                            <?= $row['p_name'] ?>
                        </h1>
                        <div class="bc-showDetail-price">
                            <h1>ราคา</h1>
                            <h1>
                                <?= number_format($row['price'], 2) ?> ฿
                            </h1>
                        </div>
                        <div class="bc-showDetail-count">
                            <p>จำนวน</p>
                            <input type="number" name="quantity" id="quantity" class="txt txt-count">
                            <p>ชิ้น</p>
                        </div>
                        <div class="btn-control-buy">
                            <a href="order.php?id=<?php echo $row['p_id']; ?>" class="btn btn-cart" style="text-decoration: none; color: #333; font-size: 13px;">เพิ่มใส่ตะกร้า</a>
                            <button class="btn btn-buy">ซื้อสินค้า</button>
                        </div>
                    </div>
                </div>
        <?php
            } else {
                echo "ไม่พบข้อมูลสินค้า";
            }
        } else {
            echo "ไม่มีการส่งค่า id มาหรือข้อมูลสินค้าไม่ถูกต้อง";
        }
        ?>

    </section>








</body>

</html>

<?php include('script-js.php'); ?>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
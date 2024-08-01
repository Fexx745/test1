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
        <?php include('bc-menu.php'); ?>

        <?php
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];

            $sql = "SELECT p.*, ph.price, u.unit_name
                    FROM product p
                    INNER JOIN product_type as c ON p.type_id = c.type_id AND p.p_id='$product_id'
                    LEFT JOIN price_history ph ON p.p_id = ph.p_id
                    LEFT JOIN unit_type u ON p.unit_id = u.unit_id
                    LEFT JOIN brand_type as b ON p.brand_id = b.brand_id
                    WHERE p.p_id = $product_id";
            $result = mysqli_query($conn, $sql);

            $sql2 = "UPDATE product SET p_view=p_view+1 WHERE p_id='$product_id'";
            $result2 = mysqli_query($conn, $sql2);

            // ตรวจสอบว่าพบข้อมูลสินค้าหรือไม่
            if ($row = mysqli_fetch_array($result)) {
                $product_quantity = $row['amount']; // เก็บจำนวนคงเหลือในตัวแปร PHP
        ?>
                <div class="bc-showDetail">
                    <div class="bc-showDetail-left">
                        <div class="bc-showDetail-photo">
                            <img src="assets/images/product/<?= $row['image'] ?>" alt="<?= $row['p_name'] ?>">
                        </div>
                    </div>

                    <div class="bc-showDetail-right">
                        <h2>
                            <?= $row['p_name'] ?>
                        </h2>
                        <div class="bc-showDetail-price">
                            <h3>
                                <?= number_format($row['price'], 2) ?> ฿
                            </h3>
                        </div>
                        <div class="bc-showTextDetail">
                            <p><?= $row['detail'] ?></p>
                        </div>
                        <div class="bc-showDetail-count">
                            <p>จำนวน</p>
                            <button type="button" class="btn-decrement">-</button>
                            <input type="number" name="quantity" id="quantity" class="txt txt-count" value="1" min="1">
                            <button type="button" class="btn-increment">+</button>
                            <span>เหลือ <?= $row['amount'] ?> <?= $row['unit_name'] ?></span>
                        </div>

                        <div class="btn-control-buy">
                            <?php if ($product_quantity > 0) { ?>
                                <a href="order.php?id=<?php echo $row['p_id']; ?>" class="btn-me btn-cart" style="color: #333; font-size: 13px;"><i class='bx bx-cart-add'></i> &nbsp;เพิ่มใส่ตะกร้า</a>
                                <a href="order.php?id=<?php echo $row['p_id']; ?>" class="btn-me btn-buy">ซื้อสินค้า</a>
                            <?php } else { ?>
                                <button class="btn-me btn-out-of-stock me-2" disabled>สินค้าหมด</button>
                                <a href="index.php" class="btn btn-light">ย้อนกลับ</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <script>
                    var maxQuantity = <?php echo $product_quantity; ?>; // จำนวนคงเหลือจากฐานข้อมูล

                    document.querySelector('.btn-decrement').addEventListener('click', function() {
                        var quantityInput = document.getElementById('quantity');
                        var currentValue = parseInt(quantityInput.value);
                        if (currentValue > parseInt(quantityInput.min)) {
                            quantityInput.value = currentValue - 1;
                        }
                    });

                    document.querySelector('.btn-increment').addEventListener('click', function() {
                        var quantityInput = document.getElementById('quantity');
                        var currentValue = parseInt(quantityInput.value);
                        if (currentValue < maxQuantity) {
                            quantityInput.value = currentValue + 1;
                        }
                    });
                </script>
        <?php
            } else {
                echo "ไม่พบข้อมูลสินค้า";
            }
        } else {
            echo "ไม่มีการส่งค่า id มาหรือข้อมูลสินค้าไม่ถูกต้อง";
        }
        ?>
    </section>

    <?php include('script-js.php');
    include('footer.php'); ?>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
</body>

</html>
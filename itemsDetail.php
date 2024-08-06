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

            // ดึงข้อมูลสินค้า
            $sql = "SELECT p.*, ph.price, u.unit_name, c.type_name, b.brand_name
                    FROM product p
                    INNER JOIN product_type c ON p.type_id = c.type_id
                    LEFT JOIN price_history ph ON p.p_id = ph.p_id
                    LEFT JOIN unit_type u ON p.unit_id = u.unit_id
                    LEFT JOIN brand_type b ON p.brand_id = b.brand_id
                    WHERE p.p_id = '$product_id'";
            $result = mysqli_query($conn, $sql);

            // ดึงคอมเมนต์และคะแนนจากฐานข้อมูล
            $sql_reviews = "SELECT r.*, u.username, u.firstname, u.lastname FROM product_reviews r
            INNER JOIN tb_member u ON r.member_id = u.id
            WHERE r.p_id = '$product_id'
            ORDER BY r.created_at DESC";
            $result_reviews = mysqli_query($conn, $sql_reviews);

            // อัปเดตจำนวนการเข้าชม
            $sql2 = "UPDATE product SET p_view=p_view+1 WHERE p_id='$product_id'";
            $result2 = mysqli_query($conn, $sql2);

            // ตรวจสอบว่าพบข้อมูลสินค้าหรือไม่
            if ($row = mysqli_fetch_array($result)) {
                $product_quantity = $row['amount']; // เก็บจำนวนคงเหลือในตัวแปร PHP
                // คำนวณราคาหลังจากลดราคา
                $discounted_price = $row['price'] * (1 - $row['discount'] / 100);
        ?>
                <div class="bc-showDetail">
                    <div class="bc-showDetail-top">
                        <div class="bc-showDetail-left">
                            <div class="bc-showDetail-photo">
                                <img src="assets/images/product/<?= $row['image'] ?>" alt="<?= $row['p_name'] ?>">
                            </div>
                        </div>

                        <div class="bc-showDetail-right">
                            <h2><?= $row['p_name'] ?></h2>
                            <div class="bc-showDetail-price">
                                <?php if ($row['discount'] > 0) { ?>
                                    <h3><del><?= number_format($row['price'], 2) ?> ฿</del> <span><?= number_format($discounted_price, 2) ?> ฿</span></h3>
                                <?php } else { ?>
                                    <h3><?= number_format($row['price'], 2) ?> ฿</h3>
                                <?php } ?>
                            </div>
                            <div class="bc-showTextDetail">
                                <div style="margin-bottom: 20px;">
                                    <span><?= $row['detail'] ?></span>
                                </div>
                            </div>
                            <div class="bc-showDetail-count">
                                <p>จำนวน</p>
                                <button type="button" class="btn-decrement">-</button>
                                <input type="text" name="quantity" id="quantity" class="txt txt-count" value="1" min="1" readonly>
                                <button type="button" class="btn-increment">+</button>
                                <span>เหลือ <?= $row['amount'] ?> <?= $row['unit_name'] ?></span>
                            </div>

                            <div class="btn-control-buy">
                                <?php if ($product_quantity > 0) { ?>
                                    <a href="order.php?id=<?= $row['p_id'] ?>" class="btn-me btn-cart" style="color: #333; font-size: 13px;"><i class='bx bx-cart-add'></i> &nbsp;เพิ่มใส่ตะกร้า</a>
                                    <a href="order.php?id=<?= $row['p_id'] ?>" class="btn-me btn-buy">ซื้อสินค้า</a>
                                <?php } else { ?>
                                    <button class="btn-me btn-out-of-stock me-2" disabled>สินค้าหมด</button>
                                <?php } ?>
                            </div>
                        </div>

                    </div> <!-- bc-showDetail-top -->
                    <section class="reviews-section">
                        <div class="bc-showDetail-bottom">
                            <!-- ฟอร์มสำหรับเพิ่มคอมเมนต์ -->
                            <?php if (isset($_SESSION['username'])) { ?>
                                <form action="add_review.php" method="POST" id="reviewForm">
                                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                    <input type="hidden" name="rating" id="rating" value="1"> <!-- เก็บค่าคะแนนที่เลือก -->
                                    <div>
                                        <label for="rating">ให้คะแนน และรีวิวสินค้า</label>
                                        <div id="rating-stars">
                                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                <i class='bx bxs-star' data-value="<?= $i ?>" onclick="setRating(<?= $i ?>)"></i>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="bc-showDetail-comment">
                                        <textarea name="comment" id="comment" required></textarea>
                                    </div>
                                    <a href="index.php" class="btn btn-dark">ย้อนกลับ</a>
                                    <button class="btn btn-danger" type="submit"><i class='bx bx-send'></i> ส่งคอมเมนต์</button>
                                </form>
                            <?php } ?>
                        </div>
                        <div class="reviews-container">
                            <?php if (mysqli_num_rows($result_reviews) > 0) { ?>
                                <?php while ($review = mysqli_fetch_array($result_reviews)) { ?>
                                    <div class="review">
                                        <div class="review-header">
                                            <strong><?= htmlspecialchars($review['firstname'] . ' ' . $review['lastname']) ?></strong>
                                            <span class="review-rating">
                                                <?php for ($i = 0; $i < $review['rating']; $i++) { ?>
                                                    <i class='bx bxs-star'></i>
                                                <?php } ?>
                                                <?php for ($i = $review['rating']; $i < 5; $i++) { ?>
                                                    <i class='bx bx-star'></i>
                                                <?php } ?>
                                            </span>
                                        </div>
                                        <div class="review-body">
                                            <p><?= htmlspecialchars($review['comment']) ?></p>
                                        </div>
                                        <div class="review-footer">
                                            <small>วันที่รีวิว: <?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></small>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </section>
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

    <?php include('script-js.php'); ?>
    <?php include('footer.php'); ?>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
</body>

</html>

<script>
    function setRating(rating) {
        document.getElementById('rating').value = rating;
        const stars = document.querySelectorAll('#rating-stars .bxs-star');
        stars.forEach(star => {
            if (star.getAttribute('data-value') <= rating) {
                star.classList.add('selected');
            } else {
                star.classList.remove('selected');
            }
        });
    }

    document.querySelectorAll('#rating-stars .bxs-star').forEach(star => {
        star.addEventListener('mouseover', function() {
            const rating = this.getAttribute('data-value');
            document.querySelectorAll('#rating-stars .bxs-star').forEach(star => {
                if (star.getAttribute('data-value') <= rating) {
                    star.classList.add('hover');
                } else {
                    star.classList.remove('hover');
                }
            });
        });
        star.addEventListener('mouseout', function() {
            document.querySelectorAll('#rating-stars .bxs-star').forEach(star => {
                star.classList.remove('hover');
            });
        });
    });
</script>

<script>
    var maxQuantity = <?= $product_quantity; ?>;

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

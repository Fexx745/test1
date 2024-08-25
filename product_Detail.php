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
        <?php include('index_Menu.php'); ?>

        <?php
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];

            // ดึงข้อมูลสินค้า
            $sql = "SELECT p.*, ph.price, u.unit_name, c.type_name, b.brand_name,p.p_view
                    FROM product p
                    INNER JOIN product_type c ON p.type_id = c.type_id
                    LEFT JOIN price_history ph ON p.p_id = ph.p_id
                    LEFT JOIN unit_type u ON p.unit_id = u.unit_id
                    LEFT JOIN brand_type b ON p.brand_id = b.brand_id
                    WHERE p.p_id = '$product_id'";
            $result = mysqli_query($conn, $sql);

            // ดึงคอมเมนต์และคะแนนจากฐานข้อมูล
            $reviews_per_page = 4; // Number of reviews per page
            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($current_page - 1) * $reviews_per_page;

            $sql_reviews = "SELECT r.*, u.username, u.firstname, u.lastname 
                            FROM product_reviews r
                            INNER JOIN tb_member u ON r.member_id = u.id
                            WHERE r.p_id = '$product_id'
                            ORDER BY r.created_at DESC
                            LIMIT $reviews_per_page OFFSET $offset";
            $result_reviews = mysqli_query($conn, $sql_reviews);

            // คำนวณคะแนนดาวเฉลี่ย
            $sql_average_rating = "SELECT AVG(rating) AS average_rating FROM product_reviews WHERE p_id = '$product_id'";
            $result_average_rating = mysqli_query($conn, $sql_average_rating);
            $row_average_rating = mysqli_fetch_assoc($result_average_rating);
            $average_rating = $row_average_rating['average_rating'];


            $sql_total_reviews = "SELECT COUNT(*) AS total FROM product_reviews WHERE p_id = '$product_id'";
            $result_total_reviews = mysqli_query($conn, $sql_total_reviews);
            $row_total_reviews = mysqli_fetch_assoc($result_total_reviews);
            $total_reviews = $row_total_reviews['total'];
            $total_pages = ceil($total_reviews / $reviews_per_page);

            // อัปเดตจำนวนการเข้าชม
            $sql2 = "UPDATE product SET p_view=p_view+1 WHERE p_id='$product_id'";
            $result2 = mysqli_query($conn, $sql2);

            // ตรวจสอบว่าพบข้อมูลสินค้าหรือไม่
            if ($row = mysqli_fetch_array($result)) {
                $product_quantity = $row['amount']; // เก็บจำนวนคงเหลือในตัวแปร PHP
        ?>
                <div class="bc-showDetail">
                    <div class="bc-showDetail-top">
                        <div class="bc-showDetail-left">
                            <div class="bc-showDetail .bc-showDetail-viewphoto">
                                <img src="assets/images/product/<?= $row['image'] ?>" alt="<?= $row['p_name'] ?>">
                            </div>
                        </div>

                        <div class="bc-showDetail-right">
                            <h2><?= $row['p_name'] ?></h2>
                            <div class="bc-showDetail-price">
                                <h3><?= number_format($row['price'], 2) ?> ฿</h3>
                            </div>
                            <div class="bc-showTextDetail">
                                <div style="margin-bottom: 20px;">
                                    <span><?= $row['detail'] ?></span>
                                    <p class="mt-2"><strong>ยี่ห้อสินค้า:</strong> <?= $row['brand_name'] ?></p>
                                </div>
                            </div>
                            <div class="bc-showDetail-count">
                                <p>จำนวน</p>
                                <button type="button" class="btn-decrement">-</button>
                                <input type="number" name="quantity" id="quantity" class="txt txt-count" value="1" min="1" max="<?= $row['amount'] ?>">
                                <button type="button" class="btn-increment">+</button>
                                <span>เหลือ <?= $row['amount'] ?> <?= $row['unit_name'] ?></span>
                            </div>

                            <div class="btn-control-buy">
                                <?php if ($product_quantity > 0) { ?>
                                    <a href="#" class="btn-me btn-cart" style="color: #333; font-size: 13px;" onclick="addToCart(<?= $row['p_id'] ?>)"><i class='bx bx-cart-add'></i> &nbsp;เพิ่มใส่ตะกร้า</a>
                                    <a href="#" class="btn-me btn-buy" onclick="buyNow(<?= $row['p_id'] ?>)">ซื้อสินค้า</a>
                                <?php } else { ?>
                                    <button class="btn-me btn-out-of-stock me-2" onclick="window.location.href='index.php';">ย้อนกลับ</button>
                                    <button class="btn-me btn-out-of-stock me-2" disabled>สินค้าหมด</button>
                                <?php } ?>
                            </div>
                        </div>

                    </div> <!-- bc-showDetail-top -->
                    <section class="reviews-section">
                        <div class="bc-showDetail-bottom">
                            <!-- Form for adding comments -->
                            <?php if (isset($_SESSION['username'])) { ?>
                                <form action="add_review.php" method="POST" id="reviewForm">
                                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                    <input type="hidden" name="rating" id="rating" value="1">
                                    <div>
                                        <label for="rating">ให้คะแนนสินค้า:</label>
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
                                    <button class="btn btn-danger" type="submit">คอมเมนต์</button>
                                </form>
                            <?php } ?>
                        </div>
                        <!-- Rating filter UI -->
                        <div class="rating-filter">
                            <div class="rating-filter-avg">
                                <label><?= number_format($average_rating, 1) ?> <span>เต็ม 5</span></label>
                                <div id="ratingStars">
                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                        <i class='bx bxs-star <?= $i <= round($average_rating) ? 'selected' : '' ?>'></i>
                                    <?php } ?>
                                </div>
                            </div>
                            <div id="ratingFilterButtons">
                                <button class="rating-button" onclick="filterReviews(0)">คอมเมนต์ทั้งหมด</button>
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                    <button class="rating-button" onclick="filterReviews(<?= $i ?>)"><?= $i ?> <i class='bx bxs-star'></i></button>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="reviews-container">
                            <?php if (mysqli_num_rows($result_reviews) > 0) { ?>
                                <?php while ($review = mysqli_fetch_array($result_reviews)) { ?>
                                    <div class="review" data-rating="<?= $review['rating'] ?>">
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
                                            <small>Review Date: <?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></small>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <?php
                        // Pagination code
                        echo '<ul class="pagination">';
                        if ($current_page > 1) {
                            echo '<li class="page-item">';
                            echo '<a class="page-link" href="?id=' . $product_id . '&page=' . ($current_page - 1) . '" aria-label="Previous">';
                            echo '<span aria-hidden="true"><i class="bx bx-chevron-left"></i></span>';
                            echo '</a>';
                            echo '</li>';
                        }
                        for ($i = 1; $i <= $total_pages; $i++) {
                            echo '<li class="page-item ' . ($i == $current_page ? 'active' : '') . '">';
                            echo '<a class="page-link" href="?id=' . $product_id . '&page=' . $i . '">' . $i . '</a>';
                            echo '</li>';
                        }
                        if ($current_page < $total_pages) {
                            echo '<li class="page-item">';
                            echo '<a class="page-link" href="?id=' . $product_id . '&page=' . ($current_page + 1) . '" aria-label="Next">';
                            echo '<span aria-hidden="true"><i class="bx bx-chevron-right"></i></span>';
                            echo '</a>';
                            echo '</li>';
                        }
                        echo '</ul>';
                        ?>
                    </section>

                </div> <!-- bc-showDetail -->

        <?php
            } else {
                echo "<h3>ไม่พบสินค้าที่คุณกำลังค้นหา</h3>";
            }
        } else {
            echo "<h3>ไม่พบสินค้าที่คุณกำลังค้นหา</h3>";
        }
        ?>
    </section>
    <?php include('footer.php'); ?>


    <script>
        // ฟังก์ชันสำหรับตั้งค่าคะแนน
        function setRating(rating) {
            document.getElementById('rating').value = rating;
            var stars = document.querySelectorAll('#rating-stars .bxs-star');
            stars.forEach(function(star, index) {
                if (index < rating) {
                    star.classList.add('selected');
                } else {
                    star.classList.remove('selected');
                }
            });
        }

        // ฟังก์ชันสำหรับกรองคอมเมนต์
        function filterReviews(rating) {
            const reviews = document.querySelectorAll('.review');
            reviews.forEach(review => {
                if (rating == 0 || review.getAttribute('data-rating') == rating) {
                    review.style.display = 'block';
                } else {
                    review.style.display = 'none';
                }
            });
        }

        function addToCart(productId) {
            const quantity = document.getElementById('quantity').value;
            const maxQuantity = <?= $row['amount'] ?>;
            if (quantity > maxQuantity) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ไม่มีสินค้าเพียงพอในสต็อก',
                    text: 'โปรดเลือกจำนวนที่น้อยกว่าหรือเท่ากับ ' + maxQuantity,
                    confirmButtonText: 'ตกลง'
                });
            } else {
                window.location.href = `order.php?id=${productId}&quantity=${quantity}`;
            }
        }

        function buyNow(productId) {
            const quantity = document.getElementById('quantity').value;
            const maxQuantity = <?= $row['amount'] ?>;
            if (quantity > maxQuantity) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ไม่มีสินค้าเพียงพอในสต็อก',
                    text: 'โปรดเลือกจำนวนที่น้อยกว่าหรือเท่ากับ ' + maxQuantity,
                    confirmButtonText: 'ตกลง'
                });
            } else {
                window.location.href = `order.php?id=${productId}&quantity=${quantity}`;
            }
        }

        // เพิ่มเหตุการณ์สำหรับปุ่ม increment และ decrement
        document.querySelector('.btn-decrement').addEventListener('click', function() {
            var quantity = document.getElementById('quantity');
            if (quantity.value > 1) {
                quantity.value--;
            }
        });

        document.querySelector('.btn-increment').addEventListener('click', function() {
            var quantity = document.getElementById('quantity');
            var maxQuantity = <?= $row['amount'] ?>;
            if (quantity.value < maxQuantity) {
                quantity.value++;
            }
        });
    </script>
</body>

</html>
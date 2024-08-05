<?php
// เริ่มต้น session
session_start();
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
<?php include('nav.php'); ?>

<?php include('index_slideimg.php'); ?>

<section class="body-container">
    <?php include('bc-menu.php'); ?>

    <div class="bc-show">
        <?php
        $limit = 16; // จำนวนสินค้าที่แสดงในแต่ละหน้า
        $page = isset($_GET['page']) ? $_GET['page'] : 1; // หมายเลขหน้าที่ต้องการแสดง
        $start = ($page - 1) * $limit; // คำนวณเริ่มต้นแสดงผลสินค้า
        $sql = "SELECT p.*, ph.price, u.unit_name,
            (SELECT COUNT(*) FROM tb_order_detail WHERE tb_order_detail.p_id = p.p_id) AS sales_count,
            (SELECT AVG(rating) FROM product_reviews WHERE product_reviews.p_id = p.p_id) AS average_rating,
            (SELECT COUNT(*) FROM product_reviews WHERE product_reviews.p_id = p.p_id) AS review_count,
            p.discount
        FROM product p
        LEFT JOIN price_history ph ON p.p_id = ph.p_id
        LEFT JOIN unit_type u ON p.unit_id = u.unit_id
        LEFT JOIN brand_type b ON p.brand_id = b.brand_id
        LEFT JOIN product_type pt ON p.type_id = pt.type_id
        ORDER BY p.p_id LIMIT $start, $limit";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_array($result)) {
            // คำนวณคะแนนเฉลี่ย (ถ้ามีรีวิว)
            $average_rating = round($row['average_rating']);

            // คำนวณราคาหลังจากลดราคา
            $discounted_price = $row['price'] * (1 - $row['discount'] / 100);
        ?>
            <a href="itemsDetail.php?id=<?= $row['p_id'] ?>" class="bc-show-items">
                <div class="bc-show-items-img">
                    <img src="assets/images/product/<?= $row['image'] ?>" alt="<?= $row['p_name'] ?>">
                </div>
                <p><?= $row['p_name'] ?></p>
                <div class="bc-show-items-detail">
                    <span>ถูกซื้อแล้ว&nbsp;</span> <b><?= $row['sales_count'] ?>&nbsp</b> <span>ครั้ง</span>
                </div>
                <div class="bc-show-items-view">
                    <div class="bc-show-items-view-product">
                        <span>
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                <?php if ($i <= $average_rating) { ?>
                                    <i class='bx bxs-star'></i>
                                <?php } else { ?>
                                    <i class='bx bx-star'></i>
                                <?php } ?>
                            <?php } ?>
                            <b>(<?= $row['review_count'] ?> รีวิว)</b>
                        </span>
                    </div>
                    <div class="bc-show-items-price">
                        <h5>
                            <?php if ($row['discount'] > 0) { ?>
                                <div class="bc-show-item-discount">
                                    <span>-<?= $row['discount'] ?>%</span>
                                </div>
                                ฿<?= number_format($discounted_price, 2) ?>
                            <?php } else { ?>
                                ฿<?= number_format($row['price'], 2) ?>
                            <?php } ?>
                        </h5>
                    </div>
                </div>
            </a>
        <?php
        }
        ?>
        <div class="pagination">
            <?php
            $sql = "SELECT COUNT(*) FROM product";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_row($result);
            $total_records = $row[0];
            $total_pages = ceil($total_records / $limit);
            $current_page = $page;

            // ป้องกันการเลือกหน้าที่ไม่ถูกต้อง
            if ($page > $total_pages) {
                header('Location: index.php?page=' . $total_pages);
                exit;
            }

            echo '<ul class="pagination">';

            // แสดงปุ่ม "Previous"
            if ($current_page > 1) {
                echo '<li class="page-item">';
                echo '<a class="page-link" href="?page=' . ($current_page - 1) . '" aria-label="Previous">';
                echo '<span aria-hidden="true"><i class="bx bx-chevron-left"></i></span>';
                echo '</a>';
                echo '</li>';
            }

            // แสดงปุ่มเลขหน้า
            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<li class="page-item ' . ($i == $current_page ? 'active' : '') . '">';
                echo '<a class="page-link" href="?page=' . $i . '">' . $i . '</a>';
                echo '</li>';
            }

            // แสดงปุ่ม "Next"
            if ($current_page < $total_pages) {
                echo '<li class="page-item">';
                echo '<a class="page-link" href="?page=' . ($current_page + 1) . '" aria-label="Next">';
                echo '<span aria-hidden="true"><i class="bx bx-chevron-right"></i></span>';
                echo '</a>';
                echo '</li>';
            }

            echo '</ul>';
            mysqli_close($conn);
            ?>
        </div>

    </div>
</section>

<?php include('footer.php'); ?>

</body>

</html>

<?php include('script-js.php'); ?>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<?php
if (isset($_SESSION['reg_success'])) {
?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "ลงทะเบียนสำเร็จ!",
            text: "ยินดีด้วยคุณได้สมัครสมาชิกเรียบร้อย",
            showConfirmButton: false,
            timer: 1500
        }).then(function() {
            window.location.href = 'index.php';
        });
    </script>
<?php
    unset($_SESSION['reg_success']);
}
?>

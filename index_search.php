<?php
include('config.php');
include('condb.php');
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

    <div class="body-container">

        <!-- Slideshow -->
        <?php include('index_Menu.php'); ?>
        <div class="bc-show">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $search = mysqli_real_escape_string($conn, $_POST['search']);
            } else {
                $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
            }

            $limit = 8; // จำนวนสินค้าที่แสดงในแต่ละหน้า
            $page = isset($_GET['page']) ? $_GET['page'] : 1; // หมายเลขหน้าที่ต้องการแสดง
            $start = ($page - 1) * $limit; // คำนวณเริ่มต้นแสดงผลสินค้า

            $sql = "SELECT product.*, unit_type.unit_name, price_history.price, 
            (SELECT COUNT(*) FROM tb_order_detail WHERE tb_order_detail.p_id = product.p_id) AS sales_count,
            (SELECT AVG(rating) FROM product_reviews WHERE product_reviews.p_id = product.p_id) AS average_rating,
            (SELECT COUNT(*) FROM product_reviews WHERE product_reviews.p_id = product.p_id) AS review_count
            FROM product 
            INNER JOIN unit_type ON product.unit_id = unit_type.unit_id
            LEFT JOIN price_history ON product.p_id = price_history.p_id
            WHERE product.p_name LIKE '%$search%'
            ORDER BY product.p_id LIMIT $start, $limit";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $average_rating = round($row['average_rating'], 1); // ปรับให้เป็นทศนิยม 1 ตำแหน่ง
                    $full_stars = floor($average_rating); // จำนวนดาวเต็ม
                    $half_star = ($average_rating - $full_stars >= 0.5) ? true : false; // ตรวจสอบว่ามีครึ่งดาวหรือไม่
                    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0); // จำนวนดาวที่ว่างเปล่า
            ?>
                    <a href="product_Detail.php?id=<?= $row['p_id'] ?>" class="bc-show-items">
                        <div class="bc-show-items-img">
                            <img src="assets/images/product/<?= $row['image'] ?>" alt="<?= $row['p_name'] ?>">
                        </div>
                        <p><?= $row['p_name'] ?></p>
                        <div class="bc-show-items-price">
                            <h5>
                                ฿<?= number_format($row['price'], 2) ?>
                            </h5>
                        </div>

                        <div class="bc-show-items-view">
                            <div class="bc-show-items-view-product">
                                <span>
                                    <b><i class='bx bxs-star'></i><?= $average_rating ?></b>
                                </span>
                            </div>
                            <div class="bc-show-items-detail">
                                <span>ขายแล้ว&nbsp;<?= $row['sales_count'] ?>&nbsp;<?= $row['unit_name'] ?></span>
                            </div>
                        </div>
                    </a>
            <?php
                }

                // Fetch total number of records to calculate total pages
                $sql = "SELECT COUNT(*) FROM product WHERE product.p_name LIKE '%$search%'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_row($result);
                $total_records = $row[0];
                $total_pages = ceil($total_records / $limit);
                $current_page = $page;

                // Display pagination only if more than one page exists
                if ($total_pages > 1) {
                    echo '<div class="pagination">';
                    echo '<ul class="pagination">';

                    // แสดงปุ่ม "Previous"
                    if ($current_page > 1) {
                        echo '<li class="page-item">';
                        echo '<a class="page-link" href="?page=' . ($current_page - 1) . '&search=' . $search . '" aria-label="Previous">';
                        echo '<span aria-hidden="true"><i class="bx bx-chevron-left"></i></span>';
                        echo '</a>';
                        echo '</li>';
                    }

                    // แสดงปุ่มเลขหน้า
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<li class="page-item ' . ($i == $current_page ? 'active' : '') . '">';
                        echo '<a class="page-link" href="?page=' . $i . '&search=' . $search . '">' . $i . '</a>';
                        echo '</li>';
                    }

                    // แสดงปุ่ม "Next"
                    if ($current_page < $total_pages) {
                        echo '<li class="page-item">';
                        echo '<a class="page-link" href="?page=' . ($current_page + 1) . '&search=' . $search . '" aria-label="Next">';
                        echo '<span aria-hidden="true"><i class="bx bx-chevron-right"></i></span>';
                        echo '</a>';
                        echo '</li>';
                    }

                    echo '</ul>';
                    echo '</div>';
                }

            } else {
                echo '<div class="product-not-found">';
                echo '<img src="assets/images/other/nopd.jpg">';
                echo '<p>ไม่พบสินค้าที่คุณกำลังค้นหา ...</p>';
                echo '</div>';
            }
            mysqli_close($conn);
            ?>
        </div>
    </div> <!-- container -->

    <?php include('footer.php'); ?>
</body>

</html>
<?php include('script-js.php'); ?>

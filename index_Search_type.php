<?php
session_start();
include('condb.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Results</title>
    <?php include('script-css.php'); ?>
</head>

<body>

    <?php include('nav.php'); ?>
    <div class="body-container">

        <?php include('index_Menu.php'); ?>

        <?php
        // รับค่า type_id จาก URL และตรวจสอบความถูกต้อง
        $type_id = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;

        // รับค่าหน้าปัจจุบันจาก URL และตรวจสอบความถูกต้อง
        $page = isset($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
        $limit = 8; // จำนวนรายการต่อหน้า
        $offset = ($page - 1) * $limit; // คำนวณตำแหน่งเริ่มต้นของรายการในแต่ละหน้า

        // นับจำนวนสินค้าทั้งหมดในประเภทที่เลือก
        $count_query = "
            SELECT COUNT(*) AS total
            FROM product p
            JOIN price_history ph ON p.p_id = ph.p_id
            WHERE p.type_id = $type_id AND ph.to_date IS NULL
        ";
        $count_result = mysqli_query($conn, $count_query);
        $total_products = mysqli_fetch_assoc($count_result)['total'];
        $total_pages = ceil($total_products / $limit); // คำนวณจำนวนหน้าทั้งหมด

        // ดึงข้อมูลสินค้าสำหรับหน้าปัจจุบัน
        $query = "
            SELECT 
                p.p_id, 
                p.p_name, 
                p.detail, 
                p.amount, 
                p.p_view, 
                p.image, 
                ph.price, 
                pt.type_name, 
                ut.unit_name,
                COALESCE((SELECT COUNT(*) FROM tb_order_detail WHERE tb_order_detail.p_id = p.p_id), 0) AS sales_count,
                COALESCE((SELECT AVG(rating) FROM product_reviews WHERE product_reviews.p_id = p.p_id), 0) AS average_rating
            FROM 
                product p 
            JOIN 
                price_history ph ON p.p_id = ph.p_id 
            JOIN 
                product_type pt ON p.type_id = pt.type_id
            JOIN 
                unit_type ut ON p.unit_id = ut.unit_id
            WHERE 
                p.type_id = $type_id 
                AND ph.to_date IS NULL 
            ORDER BY 
                p.p_name
            LIMIT $limit OFFSET $offset
        ";

        $result = mysqli_query($conn, $query);
        ?>

        <div class="bc-show">

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $average_rating = round($row['average_rating'], 1);
                    $sales_count = $row['sales_count'];
            ?>
                    <a href="product_Detail.php?id=<?= $row['p_id'] ?>" class="bc-show-items">
                        <div class="bc-show-items-img">
                            <img src="assets/images/product/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['p_name']) ?>">
                        </div>
                        <p><?= htmlspecialchars($row['p_name']) ?></p>
                        <div class="bc-show-items-price">
                            <h5>
                                ฿<?= number_format($row['price'], 2) ?>
                            </h5>
                        </div>

                        <div class="bc-show-items-view">
                            <div class="bc-show-items-view-product">
                                <span>
                                    <b><i class='bx bxs-star'></i> <?= $average_rating ?></b>
                                </span>
                            </div>
                            <div class="bc-show-items-detail">
                                <span>ขายแล้ว&nbsp;<?= $sales_count ?>&nbsp;<?= htmlspecialchars($row['unit_name']) ?></span>
                            </div>
                        </div>
                    </a>
            <?php
                }
            } else {
                echo "<p>ไม่พบข้อมูลสินค้าในประเภทนี้</p>";
            }
            mysqli_close($conn);
            ?>
            <!-- แสดงปุ่มการแบ่งหน้า -->
            <?php if ($total_pages > 1): ?>
                <!-- แสดงปุ่มการแบ่งหน้า -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php
                        // คำนวณจำนวนหน้าทั้งหมดตามประเภทสินค้า
                        $total_pages = ceil($total_products / $limit);
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
                            echo '<a class="page-link" href="?type_id=' . $type_id . '&page=' . ($current_page - 1) . '" aria-label="Previous">';
                            echo '<span aria-hidden="true"><i class="bx bx-chevron-left"></i></span>';
                            echo '</a>';
                            echo '</li>';
                        }

                        // แสดงปุ่มเลขหน้า
                        for ($i = 1; $i <= $total_pages; $i++) {
                            echo '<li class="page-item ' . ($i == $current_page ? 'active' : '') . '">';
                            echo '<a class="page-link" href="?type_id=' . $type_id . '&page=' . $i . '">' . $i . '</a>';
                            echo '</li>';
                        }

                        // แสดงปุ่ม "Next"
                        if ($current_page < $total_pages) {
                            echo '<li class="page-item">';
                            echo '<a class="page-link" href="?type_id=' . $type_id . '&page=' . ($current_page + 1) . '" aria-label="Next">';
                            echo '<span aria-hidden="true"><i class="bx bx-chevron-right"></i></span>';
                            echo '</a>';
                            echo '</li>';
                        }

                        echo '</ul>';
                        ?>
                    </div>

                <?php endif; ?>

            <?php endif; ?>
        </div> <!-- bc-show -->



    </div>

    <?php include('footer.php'); ?>
</body>

</html>
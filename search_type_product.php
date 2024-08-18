<?php
session_start();
include ('condb.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Results</title>
    <?php include ('script-css.php'); ?>
</head>

<body>

    <?php include ('nav.php'); ?>
    <div class="body-container">

        <?php
        include ('bc-menu.php');

        $type_id = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;

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
                (SELECT COUNT(*) FROM tb_order_detail WHERE tb_order_detail.p_id = p.p_id) AS sales_count,
                (SELECT AVG(rating) FROM product_reviews WHERE product_reviews.p_id = p.p_id) AS average_rating
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
        ";

        $result = mysqli_query($conn, $query);
        ?>

        <div class="bc-show">

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    // ตรวจสอบและกำหนดค่าพื้นฐานให้กับตัวแปร
                    $average_rating = isset($row['average_rating']) ? round($row['average_rating'], 1) : 0; // คำนวณคะแนนเฉลี่ย (ถ้ามีรีวิว)
                    $full_stars = floor($average_rating); // จำนวนดาวเต็ม
                    $half_star = ($average_rating - $full_stars >= 0.5) ? true : false; // ตรวจสอบว่ามีครึ่งดาวหรือไม่
                    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0); // จำนวนดาวที่ว่างเปล่า
            
                    ?>
                    <a href="itemsDetail.php?id=<?= $row['p_id'] ?>" class="bc-show-items">
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
                                    <!-- <?php for ($i = 1; $i <= $full_stars; $i++) { ?>
                                        <i class='bx bxs-star'></i>
                                    <?php } ?>
                                    <?php if ($half_star) { ?>
                                        <i class='bx bxs-star-half'></i>
                                    <?php } ?>
                                    <?php for ($i = 1; $i <= $empty_stars; $i++) { ?>
                                        <i class='bx bx-star'></i>
                                    <?php } ?> -->
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
            } else {
                echo "ไม่พบข้อมูลสินค้าในประเภทนี้";
            }
            mysqli_close($conn);
            ?>

        </div>

    </div>
</body>

</html>
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

        <?php
        include('bc-menu.php');

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
                pt.type_name 
            FROM 
                product p 
            JOIN 
                price_history ph ON p.p_id = ph.p_id 
            JOIN 
                product_type pt ON p.type_id = pt.type_id 
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
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <a href="itemsDetail.php?id=<?= $row['p_id'] ?>" class="bc-show-items">
                        <div class="bc-show-items-img">
                            <img src="assets/images/product/<?= $row['image'] ?>" alt="<?= $row['p_name'] ?>">
                        </div>
                        <p><?= $row['p_name'] ?></p>
                        <div class="bc-show-items-detail">
                            <!-- <span>ถูกซื้อแล้ว&nbsp;</span> <b><?= $row['sales_count'] ?>&nbsp</b> <span>ครั้ง</span> -->
                        </div>
                        <div class="bc-show-items-view">
                            <div class="bc-show-items-view-product">
                                <span><i class='bx bx-low-vision'></i>
                                    <?= $row['p_view'] ?>
                                </span>
                            </div>
                            <h5><?= number_format($row['price'], 2) ?> ฿</h5>
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
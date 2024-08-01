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
    <?php include('nav.php'); ?>

    <?php include('index_slideimg.php'); ?>

    <section class="body-container">
        <?php
        include('bc-menu.php');
        ?>

        <div class="bc-show">
            <?php
            $limit = 16; // จำนวนสินค้าที่แสดงในแต่ละหน้า
            $page = isset($_GET['page']) ? $_GET['page'] : 1; // หมายเลขหน้าที่ต้องการแสดง
            $start = ($page - 1) * $limit; // คำนวณเริ่มต้นแสดงผลสินค้า
            $sql = "SELECT p.*, ph.price, u.unit_name,
            (SELECT COUNT(*) FROM tb_order_detail WHERE tb_order_detail.p_id = p.p_id) AS sales_count
        FROM product p
        LEFT JOIN price_history ph ON p.p_id = ph.p_id
        LEFT JOIN unit_type u ON p.unit_id = u.unit_id
        LEFT JOIN brand_type b ON p.brand_id = b.brand_id
        LEFT JOIN product_type pt ON p.type_id = pt.type_id
        ORDER BY p.p_id LIMIT $start, $limit";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)) {
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
                            <span><i class='bx bx-low-vision'></i>
                                <?= $row['p_view'] ?>
                            </span>
                        </div>
                        <h5><?= number_format($row['price'], 2) ?> ฿</h5>
                    </div>

                </a>
            <?php
            }
            ?>
            <div class="pagination">
                <?php
                include('condb.php');
                $limit = 16; // จำนวนสินค้าที่แสดงในแต่ละหน้า
                $page = isset($_GET['page']) ? $_GET['page'] : 1; // หมายเลขหน้าที่ต้องการแสดง
                $start = ($page - 1) * $limit; // คำนวณเริ่มต้นแสดงผลสินค้า

                $sql = "SELECT COUNT(*) FROM product";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_row($result);
                $total_records = $row[0];
                $total_pages = ceil($total_records / $limit);
                // ป้องกันการเลือกหน้าที่ไม่ถูกต้อง
                if ($page > $total_pages) {
                    header('Location: index.php?page=' . $total_pages);
                    exit;
                }
                // แสดงปุ่ม "Previous"
                if ($page > 1) {
                    echo "<a href='index.php?page=" . ($page - 1) . "'><i class='bx bx-first-page' ></i></a>";
                }
                // แสดงปุ่มเลขหน้า
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<a href='index.php?page=" . $i . "'>" . $i . "</a> ";
                }
                // แสดงปุ่ม "Next"
                if ($page < $total_pages) {
                    echo "<a href='index.php?page=" . ($page + 1) . "'><i class='bx bx-last-page' ></i></a>";
                }
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
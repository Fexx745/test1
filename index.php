<?php
include ('condb.php');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include ('script-css.php'); ?>

</head>

<body>
    <?php include ('nav.php'); ?>

    <div class="container">
        <?php include ('index_slideimg.php'); ?>

        <div class="row">


            <div class="col-md-12">
                <div class="category-box">
                    <div class="cate1">
                        <img src="assets/images/other/logo.png" alt="" width="90%">
                    </div>
                    <?php
                    // คำสั่ง SQL เพื่อดึงข้อมูลทั้งหมดจากตาราง product_type
                    $sql = "SELECT * FROM product_type";
                    $result = mysqli_query($conn, $sql);
                    ?>
                    <?php
                    // คำสั่ง SQL เพื่อดึงข้อมูลทั้งหมดจากตาราง product_type
                    $sql = "SELECT * FROM product_type";
                    $result = mysqli_query($conn, $sql);
                    ?>
                    <div class="cate2">
                        <div class="cate2-tagname">
                            <b>ค้นหาจากประเภทสินค้า</b>
                        </div>
                        <div class="cate2-tagname">
                            <b>ค้นหาจากประเภทสินค้า</b>
                        </div>
                        <form action="search_type_product.php" method="get">
                            <div class="cate2-typepd">
                                <select id="productTypeSelect" name="type_id">
                                    <?php
                                    while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                        <option value="<?= $row['type_id'] ?>"
                                            data-image="assets/images/type_product/<?= $row['type_image'] ?>">
                                            <?= $row['type_name'] ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <div id="productTypeImage">
                                    <img src="" alt="Product Type Image" id="typeImage">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">ค้นหา</button>
                        </form>

                        <script>
                            document.getElementById('productTypeSelect').addEventListener('change', function () {
                                var selectedOption = this.options[this.selectedIndex];
                                var imageSrc = selectedOption.getAttribute('data-image');
                                document.getElementById('typeImage').src = imageSrc;
                            });

                            // Trigger change event on page load to display the initial image
                            document.getElementById('productTypeSelect').dispatchEvent(new Event('change'));
                        </script>


                        <?php
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }
                        if (isset($_SESSION['username'])) {
                            ?>
                            <div class="edit-profile">
                                <a href="edit-profile.php?id=<?php echo htmlspecialchars($_SESSION['user_id']); ?>"
                                    class="btn btn-info">แก้ไขโปรไฟล์</a>
                                <a href="view-order-history.php" class="btn btn-primary">ดูประวัติการสั่งซื้อ</a>
                            </div>

                            <?php
                        } else {
                            ?>
                            <div class="edit-profile">
                                <a href="Login.php" class="btn btn-warning">เข้าสู่ระบบ</a>
                            </div>
                            <?php
                        }
                        ?>



                    </div>

                </div>
            </div>




            <div class="col-md-12">
                <div class="product-box-index my-2">
                    <?php
                    $limit = 15; // จำนวนสินค้าที่แสดงในแต่ละหน้า
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
                        $check_amount = $row['amount'];
                        ?>
                        <div class="product-card">
                            <img src="assets/images/product/<?= $row['image'] ?>" alt="" class="mb-3">
                            <div class="productname">
                                <span><b>
                                        <?= $row['p_name'] ?>
                                    </b></span>
                            </div>
                            <div class="card-price">
                                <!-- <div>
                                    <span>ราคาต่อ <span style="color: blue;">
                                            <?= $row['unit_name']; ?>
                                        </span></span>
                                </div> -->
                                <div>
                                    <span>เหลือสินค้าจำนวน
                                        <b style="color: <?= $row['amount'] == 0 ? '#d63384' : 'green' ?>">
                                            <?= $row['amount'] ?>
                                        </b>
                                        <span>
                                            <?= $row['unit_name']; ?>
                                        </span>
                                    </span>
                                </div>

                                <div>
                                    <span>ราคา</span>
                                    <b style="color: red; font-size: 18px;">
                                        <?= number_format($row['price'], 2) ?>
                                    </b>
                                    <span>฿</span>
                                </div>
                            </div>

                            <div class="card-button">
                                <?php
                                if ($check_amount <= 0) {
                                    ?>
                                    <a class="btn rounded-4" style="margin-right: 5px; background: #0d6efd; color: #fff;"
                                        href="show_pd_detail.php?id=<?php echo $row['p_id']; ?>">
                                        <i class="bx bx-low-vision"></i>
                                    </a>
                                    <a class="btn rounded-4 disabled" style="background: #dc3545; color: #fff;">
                                        <i class="bx bx-error-alt"></i> สินค้าหมด
                                    </a>

                                    <?php
                                } else {
                                    ?>
                                    <a class="btn rounded-4" style="margin-right: 5px; background: #0d6efd; color: #fff;"
                                        href="show_pd_detail.php?id=<?php echo $row['p_id']; ?>">
                                        <i class="bx bx-low-vision"></i>
                                    </a>
                                    <a class="btn rounded-4" style="background: #198754; color: #fff;"
                                        href="order.php?id=<?php echo $row['p_id']; ?>">
                                        <i class="bx bx-cart-add"></i> สั่งซื้อ
                                    </a>

                                    <?php
                                }
                                ?>
                            </div>
                            <!-- จำนวนเข้าชมสินค้า -->
                            <div class="card-view-product">
                                <span><i class='bx bx-low-vision'></i>
                                    <?= $row['p_view'] ?>
                                </span>
                            </div>
                            <!-- จำนวนสินค้าที่ขายแล้ว -->
                            <div class="card-sold">
                                <span>ถูกซื้อแล้ว
                                    <?= $row['sales_count']; ?>
                                    ครั้ง
                                </span>
                            </div>

                        </div> <!-- product-card -->
                        <?php
                    }
                    mysqli_close($conn);
                    ?>
                    <!-- ตำแหน่งปุ่มเปลี่ยนหน้า -->
                    <div class="pagination">
                        <?php
                        include ('condb.php');
                        $limit = 15; // จำนวนสินค้าที่แสดงในแต่ละหน้า
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
                </div> <!-- products-box -->
            </div>
        </div> <!-- row -->
    </div> <!-- container -->

    <?php include ('footer.php'); ?>


</body>

</html>

<?php include ('script-js.php'); ?>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectElement = document.getElementById('productTypeSelect');
        const imageElement = document.getElementById('typeImage');

        selectElement.addEventListener('change', function () {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const imageUrl = selectedOption.getAttribute('data-image');
            imageElement.src = imageUrl;
        });

        // Initialize with the first option's image
        const firstOption = selectElement.options[selectElement.selectedIndex];
        imageElement.src = firstOption.getAttribute('data-image');
    });
</script>
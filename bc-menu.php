<div class="bc-menu">
    <div class="bc-menu-profile">
        <i class='bx bxs-user-detail'></i>
        <h5>ข้อมูลส่วนตัว</h5>
    </div>
    <ul style="margin-bottom: 20px;">
        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['username'])) {
        ?>
            <li><a href="edit-profile.php?id=<?php echo $_SESSION['user_id']; ?>"><i class='bx bxs-user-circle'></i>&nbsp;ตั้งค่าผู้ใช้</a></li>
            <li><a href="view-order-history.php"><i class='bx bx-history'></i>&nbsp;ดูประวัติการสั่งซื้อ</a></li>
            <!-- <li><a href="logout.php"><i class='bx bx-log-out'></i>&nbsp;ออกจากระบบ</a></li> -->

        <?php } else {  ?>

            <li><a href="login.php">เข้าสู่ระบบ</a></li>
            <li><a href="reg.php">สมัครสมาชิก</a></li>
        <?php
        } ?>
    </ul>
    <form action="search_type_product.php" method="get">
        <?php
        $sql = "SELECT * FROM product_type";
        $result = mysqli_query($conn, $sql);
        ?>
        <div class="bc-menu-category">
            <i class='bx bxs-category'></i>
            <h5>หมวดหมู่สินค้า</h5>
        </div>
        <ul>
            <?php
            mysqli_data_seek($result, 0);
            while ($row = mysqli_fetch_array($result)) {
            ?>
                <li><a href="search_type_product.php?type_id=<?= $row['type_id'] ?>">
                        <img src="assets/images/type_product/<?= $row['type_image'] ?>" alt="" style="width: 20px; height: 20px; margin-right: 10px;">
                        <?= $row['type_name'] ?>
                    </a></li>
            <?php
            }
            ?>
        </ul>
    </form>
</div>
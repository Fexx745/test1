<div class="bc-menu">
    <div class="bc-menu-profile">
        <i class='bx bx-menu-alt-left'></i>
        <h5>เมนูทั่วไป</h5>
    </div>
    <ul style="margin-bottom: 20px;">
        <?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['username'])) {
            ?>
            <li><a href="editProfile.php?id=<?php echo $_SESSION['user_id']; ?>"><i
                        class='bx bxs-user-circle'></i>&nbsp;ตั้งค่าผู้ใช้</a></li>
            <li><a href="product_View_Order.php"><i class='bx bx-history'></i>&nbsp;ดูประวัติการสั่งซื้อ</a></li>
            <li><a href="changepassword.php?id=<?php echo $_SESSION['user_id']; ?>"><span class="material-symbols-outlined">
                        vpn_key
                    </span>&nbsp;เปลี่ยนรหัสผ่าน</a></li>

        <?php } else { ?>

            <li><a href="#" data-bs-toggle="modal" data-bs-target="#loginModal"><i class='bx bx-log-in'></i>&nbsp;เข้าสู่ระบบ</a></li>
            <li><a href="reg.php"><i class='bx bx-user-plus'></i>&nbsp;สมัครสมาชิก</a></li>
            <?php
        } ?>
    </ul>
    <form action="index_search_type.php" method="get">
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
                <li><a href="index_search_type.php?type_id=<?= $row['type_id'] ?>">
                        <img src="assets/images/type_product/<?= $row['type_image'] ?>" alt=""
                            style="width: 15px; height: 15px; margin-right: 5px;">
                        <?= $row['type_name'] ?>
                    </a></li>
                <?php
            }
            ?>
        </ul>
    </form>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['username'])) {
        ?>
        <div class="bc-menu-logout">
            <i class='bx bxs-log-out'></i>
            <h5>ออกจากระบบ</h5>
        </div>
        <ul style="margin-bottom: 20px;">
            <li><a href="logout.php" onclick="confirmLogout(event)"><i class='bx bx-log-out'></i>&nbsp;ออกจากระบบ</a></li>


        <?php } else { ?>

            <?php
    } ?>
    </ul>
</div>

<script>
    function confirmLogout(event) {
        event.preventDefault(); // ป้องกันการโหลดหน้าต่อไปทันที
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการออกจากระบบหรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ออกจากระบบ!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'logout.php';
            }
        });
    }
</script>

<?php include('script-js.php'); ?>
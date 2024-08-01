<?php
include('condb.php');
session_start();
// ตรวจสอบว่ามี id ใน URL หรือไม่
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    // $sql = "SELECT * FROM tb_member WHERE id='$id'";
    // $result = mysqli_query($conn, $sql);
    // $row = mysqli_fetch_array($result);

    // if (!$row) {
    //     echo "User not found";
    //     exit();
    // }
} else {
    echo "No user ID specified";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <?php include ('script-css.php'); ?>
</head>

<body>
    <?php include('nav.php'); ?>

    <div class="container">
    <?php include ('index_slideimg.php'); ?>
        <div class="container-card2">
            <div class="categories-box">
                <div class="cate1">
                    <h6><i class='bx bxs-notepad'></i>&nbsp;ข้อมูลส่วนตัว</h6>
                </div>

                <?php
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                if (isset($_SESSION['username'])) {
                ?>

                    <p class="alert alert-dark m-5">ชื่อผู้ใช้ <?php echo htmlspecialchars($_SESSION['username']); ?></p>

                    <div class="grid gap-0 row-gap-3 text-center">
                        <div class="p-2" style="margin: 0 40px 0 40px; background: #b1b1b1;">
                            <img src="assets/images/other/user-no-img.jpg" alt=""
                                style="width: 115px; height: 100px; object-fit: cover;">
                        </div>
                        <div class="p-2 g-col-6 d-flex justify-content-center">
                            <a href="edit-profile.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-danger" style="margin-right: 10px"><i class='bx bxs-user-badge'></i>&nbsp;แก้ไข</a>
                            <a href="#" class="btn btn-warning"><i class='bx bxs-car'></i>&nbsp;ติดตามการสั่งซื้อ</a>
                        </div>
                    </div>
                <?php } else { ?>
                    <p class='text-center'>กรุณาเข้าสู่ระบบ</p>
                <?php } ?>

            </div> <!-- end categories-box -->

            <div class="edit-profile">
                <div class="row">
                    <form method="POST" action="update_account.php" enctype="multipart/form-data">
                        <div class="mb-3 mt-3">
                            <input type="hidden" class="form-control alert alert-success" name="id"
                                value="<?= htmlspecialchars($_SESSION['user_id']); ?>" readonly>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class='bx bxs-user'></i></span>
                            <select class="form-select" name="prefix">
                                <option value="นาย" <?= ($_SESSION['prefix'] == 'นาย') ? 'selected' : ''; ?>>นาย</option>
                                <option value="นาง" <?= ($_SESSION['prefix'] == 'นาง') ? 'selected' : ''; ?>>นาง</option>
                                <option value="นางสาว" <?= ($_SESSION['prefix'] == 'นางสาว') ? 'selected' : ''; ?>>นางสาว</option>
                            </select>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class='bx bxs-user-account'></i></span>
                            <input type="text" class="form-control" name="fname" value="<?= htmlspecialchars($_SESSION['fname']); ?>">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class='bx bxs-user-account'></i></span>
                            <input type="text" class="form-control" name="lname" value="<?= htmlspecialchars($_SESSION['lname']); ?>">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class='bx bxs-phone-call'></i></span>
                            <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($_SESSION['phone']); ?>">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class='bx bxs-location-plus'></i></span>
                            <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($_SESSION['address']); ?>">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class='bx bxs-envelope'></i></span>
                            <input type="text" class="form-control" name="email" value="<?= htmlspecialchars($_SESSION['email']); ?>">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class='bx bxs-user-circle'></i></span>
                            <input type="text" class="form-control" name="username"
                                value="<?= htmlspecialchars($_SESSION['username']); ?>">
                        </div>

                        <div class="mt-4">
                            <a href="index.php" class="btn btn-primary">ย้อนกลับ</a>
                            <button class="btn btn-success" type="submit">ตกลง</button>
                        </div>
                    </form>
                </div>
            </div> <!-- end edit-profile -->
        </div> <!-- end container-card2 -->
    </div> <!-- end container -->

    <?php //include('footer.php'); ?>

</body>

</html>

<?php include ('script-js.php'); ?>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
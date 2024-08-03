<?php
include('condb.php');
session_start();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
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
    <?php include('script-css.php'); ?>
</head>

<body>
    <?php include('nav.php'); ?>

    <div class="body-container">

        <?php include('bc-menu.php'); ?>
        <div class="edit-profile">
            <div class="row">
                <div class="col-mb-12 mt-2">
                    <h3><img src="assets/images/other/User-Profile-PNG.png" alt="" style="width: 50px; height: 50px; margin-top: -20px;">&nbsp;<?= htmlspecialchars($_SESSION['username']); ?> </h3>
                </div>
                <form method="POST" action="update_account.php" enctype="multipart/form-data">
                    <div class="mb-3 mt-3">
                        <input type="hidden" class="form-control alert alert-success" name="id" value="<?= htmlspecialchars($_SESSION['user_id']); ?>" readonly>
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
                        <input type="text" class="form-control" name="fname" placeholder="ชื่อ" value="<?= htmlspecialchars($_SESSION['fname']); ?>">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bxs-user-account'></i></span>
                        <input type="text" class="form-control" name="lname" placeholder="นามสกุล" value="<?= htmlspecialchars($_SESSION['lname']); ?>">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bxs-phone-call'></i></span>
                        <input type="text" class="form-control" name="phone" placeholder="เบอร์โทรศัพท์" value="<?= htmlspecialchars($_SESSION['phone']); ?>">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bxs-envelope'></i></span>
                        <input type="text" class="form-control" name="email" placeholder="อีเมลล์" value="<?= htmlspecialchars($_SESSION['email']); ?>">
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="ที่อยู่ ..." id="floatingTextarea" name="address" style="height: 200px; resize: none;"><?= htmlspecialchars($_SESSION['address']); ?></textarea>
                        <label for="floatingTextarea"><i class='bx bx-location-plus' ></i> ที่อยู่ ...</label>
                    </div>
                    <input type="hidden" class="form-control" name="username" placeholder="ชื่อผู้ใช้" value="<?= htmlspecialchars($_SESSION['username']); ?>" readonly>

                    <div class="previous-button">
                        <a href="index.php" class="btn btn-dark">ย้อนกลับ</a>
                        <button class="btn btn-danger" type="submit">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div> <!-- end edit-profile -->
    </div> <!-- end container-card2 -->

    <?php include('footer.php'); ?>

</body>

</html>

<?php include('script-js.php'); ?>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<?php
if (isset($_SESSION['submit_edit_profile'])) {
?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "ทำรายการสำเร็จ",
            text: "แก้ไขข้อมูลเรียบร้อยแล้ว!",
            showConfirmButton: false,
            timer: 1500
        });
    </script>

<?php
    unset($_SESSION['submit_edit_profile']);
}
?>
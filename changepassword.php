<?php
include('condb.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1p1o_UWQ8xlqEa--uBtZz5FEGn7O7k_M"></script>
</head>

<body>
    <?php include('nav.php'); ?>
    <div class="body-container">
        <?php include('index_Menu.php'); ?>
        <div class="edit-profile">
            <div class="row">
                <div class="col-mb-12 mt-2">
                    <div class="mb-3">
                        <h3><img src="assets/images/other/lock_key.png" alt=""
                                style="width: 50px; height: 50px; margin-top: -20px;">&nbsp;เปลี่ยนรหัสผ่าน</h3>
                    </div>
                    <!-- <h3><img src="assets/images/other/User-Profile-PNG.png" alt=""
                            style="width: 50px; height: 50px; margin-top: -20px;">&nbsp;<?= htmlspecialchars($_SESSION['fname'] . ' ' . $_SESSION['lname']) ?>
                    </h3> -->
                </div>
                <form method="POST" action="changepassword_update.php" enctype="multipart/form-data">
                    <div class="mb-3 mt-3">
                        <input type="hidden" class="form-control alert alert-success" name="id"
                            value="<?= htmlspecialchars($_SESSION['user_id']); ?>" readonly>
                    </div>
                    <!-- Old Password -->
                    <!-- <label for="old_psw">รหัสผ่านปัจจุบัน</label> -->
                    <div class="input-group mb-3">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control" id="old_psw" name="old_psw"
                            placeholder="รหัสผ่านปัจจุบัน">
                    </div>

                    <!-- New Password -->
                    <!-- <label for="new_psw">รหัสผ่านใหม่</label> -->
                    <div class="input-group mb-3">
                        <span class="input-group-text">
                            <i class="fas fa-unlock-alt"></i>
                        </span>
                        <input type="password" class="form-control" id="new_psw" name="new_psw"
                            placeholder="รหัสผ่านใหม่">
                    </div>

                    <!-- Confirm New Password -->
                    <!-- <label for="confirm_new_psw">ยืนยันรหัสผ่านใหม่</label> -->
                    <div class="input-group mb-3">
                        <span class="input-group-text">
                            <i class="fas fa-unlock-alt"></i>
                        </span>
                        <input type="password" class="form-control" id="confirm_new_psw" name="confirm_new_psw"
                            placeholder="ยืนยันรหัสผ่านใหม่อีกครั้ง">
                    </div>

                    <div class="text-start my-4">
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
$alerts = [
    'submit_edit_psw' => [
        'icon' => 'success',
        'title' => 'ดำเนินการสำเร็จ',
        'text' => 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว'
    ],
    'psw_old_incorrect' => [
        'icon' => 'info',
        'title' => 'โปรดลองอีกครั้ง',
        'text' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง'
    ],
    'psw_do_not_found' => [
        'icon' => 'warning',
        'title' => 'รหัสผ่านใหม่ไม่ตรงกัน',
        'text' => 'โปรดยืนยันรหัสผ่านใหม่ให้ถูกต้อง'
    ]
];

foreach ($alerts as $key => $alert) {
    if (isset($_SESSION[$key])) {
        ?>
        <script>
            Swal.fire({
                icon: "<?= $alert['icon'] ?>",
                title: "<?= $alert['title'] ?>",
                text: "<?= $alert['text'] ?>",
                showConfirmButton: false,
                timer: 3000
            });
        </script>
        <?php
        unset($_SESSION[$key]);
    }
}
?>
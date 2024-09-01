<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบเพื่อช้อปออนไลน์พร้อมรับดีลสุดพิเศษได้ที่นี่ | RMUTI</title>
    <!-- Toastr.js
    <link rel="stylesheet" href="assets/toastr-master/build/toastr.min.css">
    <link rel="stylesheet" href="assets/toastr-master/build/toastr.min.js"> -->
    <link rel="stylesheet" href="assets/css/nav-reg.css">
    <?php include('script-css.php'); ?>
</head>

<body>

    <?php include('nav_login.php'); ?>

    <div class="container-fluid p-5" style="background: #fff; margin-bottom: 310px;">
        <div class="row mt-5">
            <div class="col-lg-4 bg-white m-auto rounded-top wrapper">
                <h3 class="text-center pt-3">เข้าสู่ระบบ</h3>
                <p class="text-center text-muted lead mb-3">
                    <?php
                    if (!empty($_SESSION["error"])) {
                        echo "<div id='errorMessage' class='errorMessage'>";
                        echo "<i class='fas fa-times-circle' style='color: red;'></i> "; // Add the icon here
                        echo $_SESSION["error"];
                        echo "</div>";
                        echo "<script>
            setTimeout(function() {
                var errorMessage = document.getElementById('errorMessage');
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
            }, 5000); // นับเวลา 5 วินาทีแล้วซ่อนข้อความ
        </script>";
                        unset($_SESSION["error"]); // ลบค่า $_SESSION["error"] ออกจาก session
                    }
                    ?>

                </p>

                <form action="login_check_index.php" method="POST">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-user-circle'></i></span>
                        <input type="text" class="form-control" name="username" placeholder="ชื่อผู้ใช้งาน" required>
                    </div>
                    <div class="input-group mb-1">
                        <span class="input-group-text"><i class='bx bx-lock-alt'></i></span>
                        <input type="password" class="form-control" id="psw" name="psw" placeholder="รหัสผ่าน" required>
                    </div>

                    <div class="d-flex justify-content-end mb-3">
                        <a href="#" onclick="togglePasswordVisibility()"> <span id="togglePasswordIcon" class="material-symbols-outlined" style="color: #000; font-size: 18px;">visibility_off</span></a>
                    </div>

                    <div class="d-grid">
                        <button class="btn-submit" type="submit">ล็อคอิน</button>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="mt-2"><span style="color: #6c757d;">ยังไม่มีบัญชี?</span> <a href="reg.php">สมัครสมาชิก</a></p>
                        <a href="forgot.php" id="forgotpsw" class="mt-2">ลืมรหัสผ่าน ?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

</body>

</html>

<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("psw");
        var passwordIcon = document.getElementById("togglePasswordIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passwordIcon.textContent = "visibility";
        } else {
            passwordInput.type = "password";
            passwordIcon.textContent = "visibility_off";
        }
    }
</script>

<?php
if (isset($_SESSION['success']) || isset($_SESSION['success-admin'])) {
    $icon = "success";
    $title = "เข้าสู่ระบบสำเร็จ!";
    $timer = 1500;
    $redirectUrl = isset($_SESSION['success-admin']) ? 'admin/index.php' : 'index.php';

    echo "<script>
        Swal.fire({
            icon: '$icon',
            title: '$title',
            showConfirmButton: false,
            timer: $timer
        }).then(function() {
            if ('$icon' === 'success') {
                window.location.href = '$redirectUrl';
            }
        });
    </script>";

    unset($_SESSION['success'], $_SESSION['success-admin']);
}
?>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบเพื่อช้อปออนไลน์พร้อมรับดีลสุดพิเศษได้ที่นี่ | RMUTI</title>

    <!-- #bootrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"> </script>
    <script src="assets/dist/sweetalert2.all.min.js"></script>

    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .wrapper {
            border-top: 3px solid #00C300;

            & a {
                text-decoration: none;
                color: #00C300;
            }

            & a:hover {
                opacity: 0.6;
                transition: .3s;
                color: #00C300;
            }
        }
    </style>
</head>

<body>

    <?php include('nav-reg.php'); ?>

    <div class="container-fluid p-5" style="background: #fff; margin-bottom: 310px;">
        <div class="row mt-5">
            <div class="col-lg-4 bg-white m-auto rounded-top wrapper">
                <h3 class="text-center pt-3">เข้าสู่ระบบ</h3>
                <p class="text-center text-muted lead mb-3">
                    <?php
                    // session_start();
                    // if (!empty($_SESSION["Error"])) {
                    //     echo "<h5 id='errorMessage' class='alert alert-danger'>";
                    //     echo $_SESSION["Error"];
                    //     echo "</h5>";
                    //     echo "<script>
                    //             setTimeout(function() {
                    //                 var errorMessage = document.getElementById('errorMessage');
                    //                 if (errorMessage) {
                    //                     errorMessage.style.display = 'none';
                    //                 }
                    //             }, 5000); // นับเวลา 5 วินาทีแล้วซ่อนข้อความ
                    //         </script>";
                    //     unset($_SESSION["Error"]); // ลบค่า $_SESSION["Error"] ออกจาก session
                    // }
                    ?>
                </p>

                <form action="login_check2.php" method="POST">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-user-circle'></i></span>
                        <input type="text" class="form-control" name="username" placeholder="ชื่อผู้ใช้งาน">
                    </div>
                    <div class="input-group mb-1">
                        <span class="input-group-text"><i class='bx bx-lock-alt'></i></span>
                        <input type="password" class="form-control" id="psw" name="psw" placeholder="รหัสผ่าน">
                    </div>

                    <div class="d-flex justify-content-end mb-3">
                        <a href="#" onclick="togglePasswordVisibility()"> <span id="togglePasswordIcon" class="material-symbols-outlined" style="color: #000; font-size: 18px;">visibility_off</span></a>
                    </div>

                    <div class="d-grid">
                        <button class="btn" style="background: #00C300; color: #fff;" type="submit">ล็อคอิน</button>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="mt-2"><span style="color: #6c757d;">ยังไม่มีบัญชี?</span> <a href="reg.php">สมัครสมาชิก</a></p>
                        <a href="forgot-form.php" id="forgotpsw" class="mt-2">ลืมรหัสผ่าน ?</a>
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
if (isset($_SESSION['success']) || isset($_SESSION['success-admin']) || isset($_SESSION['error'])) {
    $icon = isset($_SESSION['error']) ? "error" : "success";
    $title = isset($_SESSION['error']) ? "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!" : "เข้าสู่ระบบสำเร็จ!";
    $text = isset($_SESSION['error']) ? "กรุณาลองใหม่อีกครั้ง" : "";
    $timer = isset($_SESSION['error']) ? 1800 : 1500;
    $redirectUrl = isset($_SESSION['success-admin']) ? 'admin/index.php' : 'index.php';

    echo "<script>
        Swal.fire({
            icon: '$icon',
            title: '$title',
            text: '$text',
            showConfirmButton: false,
            timer: $timer
        }).then(function() {
            if ('$icon' === 'success') {
                window.location.href = '$redirectUrl';
            }
        });
    </script>";

    unset($_SESSION['success'], $_SESSION['success-admin'], $_SESSION['error']);
}
?>

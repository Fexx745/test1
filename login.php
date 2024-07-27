<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- #bootrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"> </script>
    <script src="assets/dist/sweetalert2.all.min.js"></script>

    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* body {
            background-color: #e9ecef;
        } */

        .container {
            margin: 200px auto;
        }

        .wrapper {
            border-top: 3px solid green;

            & a {
                text-decoration: none;
                color: green;
            }

            & a:hover {
                opacity: 0.5;
                transition: .3s;
            }
        }

        #forgotpsw {
            color: grenn;
        }

        #forgotpsw:hover {
            opacity: 0.5;
            transition: .3s;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-4 bg-white m-auto rounded-top wrapper">
                <h2 class="text-center pt-3">Login</h2>
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

                <form action="login_check.php" method="POST">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-user-circle'></i></span>
                        <input type="text" class="form-control" name="username" placeholder="Username">
                    </div>
                    <div class="input-group mb-1">
                        <span class="input-group-text"><i class='bx bx-lock-alt'></i></span>
                        <input type="password" class="form-control" id="psw" name="psw" placeholder="Password">
                    </div>

                    <div class="d-flex justify-content-end mb-3">
                        <a href="#" onclick="togglePasswordVisibility()"><i id="togglePasswordIcon"
                                class='bx bxs-low-vision fs-4'></i></a>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-success" type="submit">Login</button>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="mt-2">ยังไม่มีบัญชี? <a href="reg.php">สมัครสมาชิก</a></p>
                        <a href="forgot-form.php" id="forgotpsw" class="mt-2">ลืมรหัสผ่าน ?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("psw");
        var passwordIcon = document.getElementById("togglePasswordIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passwordIcon.classList.remove("bxs-low-vision");
            passwordIcon.classList.add("bx-low-vision");
        } else {
            passwordInput.type = "password";
            passwordIcon.classList.remove("bx-low-vision");
            passwordIcon.classList.add("bxs-low-vision");
        }
    }
</script>


<?php
if (isset($_SESSION['success'])) {
    ?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "เข้าสู่ระบบสำเร็จ!",
            text: "Login successful",
            showConfirmButton: false,
            timer: 1500
        }).then(function () {
            window.location.href = 'index.php';
        });
    </script>

    <?php
    unset($_SESSION['success']);
}
?>

<?php
if (isset($_SESSION['success-admin'])) {
    ?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "เข้าสู่ระบบสำเร็จ!",
            text: "Login successful",
            showConfirmButton: false,
            timer: 1500
        }).then(function () {
            window.location.href = 'admin/index.php';
        });
    </script>

    <?php
    unset($_SESSION['success-admin']);
}
?>


<?php
if (isset($_SESSION['error'])) {
    ?>
    <script>
        Swal.fire({
            // position: "top-end",
            icon: "error",
            title: "ชื่อผู้ใช้หรือรหัสผ่านผิด!",
            text: "unsuccessfully",
            showConfirmButton: false,
            timer: 1800
        });
    </script>

    <?php
    unset($_SESSION['error']);
}
?>

<?php
if (isset($_SESSION['psw_suc'])) {
    ?>
    <script>
        Swal.fire({
            icon: "success",
            title: "เปลี่ยนรหัสสำเร็จ!",
            text: "Change password successfully.",
            // footer: '<span style="color: blue;">กรุณาตรวจสอบ Email ของคุณ</span>',
            showConfirmButton: false,
            timer: 1500
        })
    </script>
    <?php
    unset($_SESSION['psw_suc']);
}
?>
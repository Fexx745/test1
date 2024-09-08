<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link rel="stylesheet" href="./assets/css/nav.css">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="logo" href="index.php">SHOP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                    <li class="nav-item">
                        <a class="nav-link" style="color: #e5e5e5;" href="index.php"><i class='bx bxs-home'></i> หน้าหลัก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color: #e5e5e5;" href="#"><i class='bx bxs-phone-call'></i> ติดต่อ</a>
                    </li>
                </ul>
                <form class="d-flex me-3 position-relative" action="index_search.php" method="POST">
                    <input class="form-control me-2" type="search" placeholder="ค้นหาชื่อสินค้า..." id="search" name="search" autocomplete="off" required>
                    <button class="btn btn-outline-success" type="submit" name="submit" style="width: 50px; padding: 10px; border: none; background-color: #333; color: #dfdfdf; border-radius: 0 5px 5px 0; cursor: pointer; font-size: 16px;">
                        <i class='bx bx-search-alt'></i>
                    </button>
                    <div class="list-group position-absolute w-100" id="show-list" style="z-index: 1000;"></div>
                </form>

                <?php
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION['username'])) {
                ?>
                    <!-- Logged in -->
                    <div class="dropdown my-2">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none; border: none;">
                            <img src="assets/images/other/man.png" alt="" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 5px;">
                            <span class="text-white"><?php echo $_SESSION['username']; ?></span>
                        </button>
                        <!-- <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="box-shadow: 0 3px 2px rgba(0, 0, 0, .8); color: #fff;">
                            <img src="assets/images/other/profile.png" alt="" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 5px;">
                        </button> -->
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="editProfile.php?id=<?php echo $_SESSION['user_id']; ?>">
                                    <i class='bx bx-cog'></i> ตั้งค่าผู้ใช้
                                </a></li>
                            <li><a class="dropdown-item" href="cart.php">
                                    <i class='bx bx-cart'></i> ตะกร้าสินค้า
                                </a></li>
                            <li><a class="dropdown-item" href="logout.php" onclick="confirmLogout(event)">
                                    <i class='bx bx-log-out'></i> ออกจากระบบ
                                </a></li>
                        </ul>
                    </div>
                <?php } else { ?>
                    <!-- Not logged in -->
                    <div class="dropdown my-3">
                        <a href="#" class="btn ms-2" style="background: #eda500; color: #fff;" data-bs-toggle="modal" data-bs-target="#loginModal">เข้าสู่ระบบ</a>
                        <a href="reg.php" class="btn ms-2" style="background: #30b566; color: #fff;">สมัครสมาชิก</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </nav>


    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">เข้าสู่ระบบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="login_check.php" method="POST">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class='bx bx-user-circle'></i></span>
                            <input type="text" class="form-control" name="username" placeholder="Username" required>
                        </div>
                        <div class="input-group mb-1">
                            <span class="input-group-text"><i class='bx bx-lock-alt'></i></span>
                            <input type="password" class="form-control" id="psw" name="psw" placeholder="Password"
                                required>
                        </div>

                        <div class="d-flex justify-content-between mb-3 align-items-center">
                            <a href="forgot.php" id="forgotpsw" class="mt-2"
                                style="text-decoration: none; color: #0046ab; font-size: 14px;">ลืมรหัสผ่าน ?</a>
                            <a href="#" onclick="togglePasswordVisibility()"> <span id="togglePasswordIcon"
                                    class="material-symbols-outlined"
                                    style="color: #000; font-size: 20px; font-weight: 800;">visibility_off</span></a>
                        </div>
                        <div class="d-grid">
                            <button class="btn" type="submit" style="background: #eda500; color: #fff;">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        function validatePasswords() {
            var password = document.getElementById("reg_psw").value;
            var confirmPassword = document.getElementById("confirm_psw").value;
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        }

        function toggleRegPasswordVisibility() {
            var passwordInput = document.getElementById("reg_psw");
            var confirmPasswordInput = document.getElementById("confirm_psw");
            var passwordIcon = document.getElementById("toggleRegPasswordIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                confirmPasswordInput.type = "text";
                passwordIcon.classList.remove("bxs-low-vision");
                passwordIcon.classList.add("bx-low-vision");
            } else {
                passwordInput.type = "password";
                confirmPasswordInput.type = "password";
                passwordIcon.classList.remove("bx-low-vision");
                passwordIcon.classList.add("bxs-low-vision");
            }
        }
    </script>

    <!-- Add your scripts at the bottom -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="search.js"></script>

    <script>
        $(document).ready(function() {
            $("#navUserToggle").hide();
            $("#cartShopToggle").hide();

            $("#navToggleProfile").click(function() {
                $("#navUserToggle").slideToggle();
            });
            $("#navUserToggle").mouseleave(function() {
                $(this).slideUp();
            });

            // เช็คว่ามีสินค้าในตะกร้าหรือไม่
            // if ($(".cart-count").text().trim() !== "0") {
            $("#cartShop").click(function() {
                $("#cartShopToggle").slideDown();
            });

            $("#cartShopToggle").mouseleave(function() {
                $(this).slideUp();
            });
            // }
        });


        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("psw");
            var passwordIcon = document.getElementById("togglePasswordIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordIcon.textContent = "visibility"; // Change icon to 'visibility_off'
            } else {
                passwordInput.type = "password";
                passwordIcon.textContent = "visibility_off"; // Change icon back to 'visibility'
            }
        }


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


    <?php
    if (isset($_SESSION['success']) || isset($_SESSION['success-admin']) || isset($_SESSION['error'])) {
        $icon = isset($_SESSION['error']) ? "error" : "success";
        $title = isset($_SESSION['error']) ? "ชื่อผู้ใช้หรือรหัสผ่านผิด!" : "เข้าสู่ระบบสำเร็จ!";
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


    <?php
    if (isset($_SESSION['psw_suc'])) {
    ?>
        <script>
            Swal.fire({
                icon: "success",
                title: "เปลี่ยนรหัสสำเร็จ!",
                text: "กรุณาเข้าสู่ระบบใหม่อีกครั้ง",
                showConfirmButton: false,
                timer: 1500
            })
        </script>
    <?php
        unset($_SESSION['psw_suc']);
    }
    ?>


</body>

</html>
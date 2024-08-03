<?php
include('condb.php');
$sql = "SELECT * FROM tb_member";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link rel="stylesheet" href="./assets/css/nav.css">

    <style>
        /* ซ่อนเมนูตั้งแต่แรกด้วย CSS */
        #navUserToggle {
            display: none;
        }
    </style>
</head>

<body>
    <nav>
        <div class="nav-container">
            <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['username'])) {
            ?>
                <!-- if login -->
                <div class="nav-top">
                    <div class="nav-top-contract">
                        <ul>
                            <li><a href="index.php"><i class='bx bxs-home'></i> หน้าหลัก</a></li>
                            <li><a href=""><i class='bx bxs-phone-call'></i> ติดต่อ</a></li>
                        </ul>
                    </div>
                    <div class="nav-top-user">
                        <div class="nav-top-user-profile">
                            <img src="assets/images/other/User-Profile-PNG.png" alt="">
                        </div>
                        <p id="navToggleProfile"><?php echo $_SESSION['fname'] . " " . $_SESSION['lname']; ?> <i class='bx bxs-chevron-down'></i></p>
                        <div id="navUserToggle" class="nav-top-user-toggle">
                            <a href="edit-profile.php?id=<?php echo $_SESSION['user_id']; ?>">ตั้งค่าผู้ใช้</a>
                            <a href="logout.php" onclick="confirmLogout(event)">ออกจากระบบ</a>
                        </div>
                    </div>
                </div>
                <div class="nav-bottom">
                    <div class="nav-bottom-logo">
                        <a href="index.php">RMUTI</a>
                    </div>
                    <div class="nav-bottom-search">
                        <form action="index_search.php" method="POST" class="nav-bottom-search" style="position: relative;">
                            <input type="text" placeholder="Search..." id="search" name="search" autocomplete="off" required>
                            <button type="submit" name="submit"><i class='bx bx-search-alt'></i></button>
                            <div class="list-group" id="show-list"></div>
                        </form>
                        <div class="col-md-5">
                            <div class="list-group" style="position: absolute; width: 490px;" id="show-list"></div>
                        </div>
                    </div>
                    <div class="nav-bottom-cart">
                        <a href="cart.php" class="cart-shop">
                            <div class="cart-count">
                                <?php
                                if (isset($_SESSION['inPro'])) {
                                    echo $_SESSION['inPro'];
                                } else {
                                    echo "0";
                                }
                                ?>
                            </div>
                            <i class='bx bxs-cart-alt'></i>
                        </a>
                    </div>
                </div>
            <?php } else { ?>
                <!-- if not login -->
                <div class="nav-top">
                    <div class="nav-top-contract">
                        <ul>
                            <li><a href="index.php"><i class='bx bxs-home'></i> หน้าหลัก</a></li>
                            <li><a href=""><i class='bx bxs-phone-call'></i> ติดต่อ</a></li>
                        </ul>
                    </div>
                    <div class="nav-top-user">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">เข้าสู่ระบบ</a>
                        <a href="reg.php">สมัครสมาชิก</a>
                    </div>
                </div>
                <div class="nav-bottom">
                    <div class="nav-bottom-logo">
                        <a href="index.php">RMUTI</a>
                    </div>
                    <div class="nav-bottom-search">
                        <input type="text" placeholder="Search...">
                        <button type="button"><i class='bx bx-search-alt'></i></button>
                    </div>
                    <div class="nav-bottom-cart">
                        <div class="cart-shop">
                            <i class='bx bxs-cart-alt'></i>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </nav>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
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
                            <input type="password" class="form-control" id="psw" name="psw" placeholder="Password" required>
                        </div>

                        <div class="d-flex justify-content-end mb-3">
                            <a href="#" onclick="togglePasswordVisibility()"><i id="togglePasswordIcon" class='bx bxs-low-vision fs-4'></i></a>
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
    </div>

    <!-- Add your scripts at the bottom -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="search.js"></script>

    <script>
        $(document).ready(function() {
            $("#navUserToggle").hide();

            $("#navToggleProfile").click(function() {
                $("#navUserToggle").slideToggle();
            });
            $("#navUserToggle").mouseleave(function() {
                $(this).slideUp();
            });
        });

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
    if (isset($_SESSION['success'])) {
    ?>
        <script>
            Swal.fire({
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
    if (isset($_SESSION['error'])) {
    ?>
        <script>
            Swal.fire({
                icon: "error",
                title: "ชื่อผู้ใช้หรือรหัสผ่านผิด!",
                text: "Login failed",
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
                text: "Password changed successfully.",
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

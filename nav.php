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

            #cartShopToggle {
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
                                <ul>
                                    <li><a href="edit-profile.php?id=<?php echo $_SESSION['user_id']; ?>">ตั้งค่าผู้ใช้</a></li>
                                    <li><a href="cart.php">การซื้อของฉัน</a></li>
                                    <li><a href="logout.php" onclick="confirmLogout(event)">ออกจากระบบ</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                    <div class="nav-bottom">
                        <div class="nav-bottom-logo">
                            <a href="index.php">RMUTI SHOP</a>
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
                            <div id="cartShop" class="cart-shop">
                                <div class="cart-count" id="cart-count">
                                    <?php
                                    if (isset($_SESSION['inPro']) && $_SESSION['inPro'] > 0) {
                                        echo $_SESSION['inPro'];
                                    } else {
                                        echo "0";
                                    }
                                    ?>
                                </div>
                                <i class='bx bx-cart-alt'></i>
                                <div id="cartShopToggle" class="cart-shop-toggle">
                                    <div class="cart-shop-toggle-header">
                                        <p>สินค้าที่เพิ่งเพิ่มเข้าไป</p>
                                    </div>
                                    <?php
                                    $hasItems = false;
                                    $totalPrice = 0;
                                    $itemCount = 0; // ตัวนับจำนวนรายการสินค้า
                                    if (isset($_SESSION["intLine"])) {
                                        for ($i = 0; $i <= (int) $_SESSION["intLine"]; $i++) {
                                            if (($_SESSION["strProductID"][$i]) != "") {
                                                $sql1 = "SELECT product.*, price_history.* 
                    FROM product 
                    LEFT JOIN price_history ON product.p_id = price_history.p_id 
                    WHERE product.p_id = '" . $_SESSION["strProductID"][$i] . "'";
                                                $result1 = mysqli_query($conn, $sql1);
                                                $row_product = mysqli_fetch_array($result1);
                                                $hasItems = true;
                                                $total = $_SESSION["strQty"][$i] * $row_product['price'];
                                                $totalPrice += $total;
                                                $itemCount++; // เพิ่มจำนวนรายการสินค้า
                                                if ($itemCount <= 3) { // แสดงแค่ 3 รายการสินค้า
                                    ?>
                                                    <div class="cart-shop-toggle-items">
                                                        <div class="cart-shop-toggle-items-img">
                                                            <img src="assets/images/product/<?= $row_product['image'] ?>" alt="">
                                                        </div>
                                                        <p><?= $_SESSION["strQty"][$i] ?> ชิ้น</p>
                                                        <p><?= number_format($total, 2) ?> บาท</p>
                                                    </div>
                                        <?php
                                                }
                                            }
                                        }
                                    }
                                    if (!$hasItems) {
                                        echo "<p>ไม่มีสินค้าในตะกร้า ...</p>";
                                    } else {
                                        ?>
                                        <?php if ($itemCount >= 1) { // แสดงปุ่ม "ไปที่ตะกร้า" ถ้ามีสินค้ามากกว่า 3 รายการ 
                                        ?>
                                            <div class="cart-shop-toggle-items-gotocart">
                                                <p><?= $itemCount ?> สินค้าเพิ่มเติมในรถเข็น</p>
                                                <a href="cart.php" class="btn-cart">ดูรถเข็นของคุณ</a>
                                            </div>
                                        <?php } ?>
                                        <div class="cart-shop-toggle-items-totalprice">
                                            <p>ราคารวมสุทธิ:</p>
                                            <p><?= number_format($totalPrice, 2) ?> บาท</p>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>

                        </div>
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
                    <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#signupModal">สมัครสมาชิก</a> -->
                </div>
            </div>
            <div class="nav-bottom">
                <div class="nav-bottom-logo">
                    <a href="index.php">RMUTI SHOP</a>
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
                                <input type="password" class="form-control" id="psw" name="psw" placeholder="Password" required>
                            </div>  

                            <div class="d-flex justify-content-between mb-3 align-items-center">
                                <a href="forgot-form.php" id="forgotpsw" class="mt-2" style="text-decoration: none; color: #00C300; font-size: 14px;">ลืมรหัสผ่าน ?</a>
                                <a href="#" onclick="togglePasswordVisibility()"> <span id="togglePasswordIcon" class="material-symbols-outlined" style="color: #000; font-size: 18px;">visibility_off</span></a>
                            </div>
                            <div class="d-grid">
                                <button class="btn" type="submit" style="background: #00C300; color: #fff;">Login</button>
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
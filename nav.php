<butt?php include ('condb.php'); $sql="SELECT * FROM tb_member" ; $result=mysqli_query($conn, $sql);
    $row=mysqli_fetch_array($result); ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Navbar</title>
        <link rel="stylesheet" href="./assets/css/index.css">
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
                            <p id="navToggleProfile"><?php echo $_SESSION['username']; ?> <i
                                    class='bx bxs-chevron-down'></i>
                            </p>
                            <div id="navUserToggle" class="nav-top-user-toggle">
                                <a href="edit-profile.php?id=<?php echo $_SESSION['user_id']; ?>">ตั้งค่าผู้ใช้</a>
                                <!-- <a href="view-order-history.php">ดูประวัติการสั่งซื้อ</a> -->
                                <a href=""></a>
                                <a href="logout.php">ออกจากระบบ</a>
                            </div>
                        </div>
                    </div>
                    <div class="nav-bottom">
                        <a href="index.php">RMUTI</a>
                        <div class="nav-bottom-search">
                            <input type="text" placeholder="Search...">
                            <button type="button">ค้นหา</button>
                        </div>
                        <div class="nav-bottom-cart">
                            <div class="cart-shop">
                                <div class="cart-count">
                                    <?php
                                    if (isset($_SESSION['inPro']) && is_array($_SESSION['inPro'])) {
                                        echo count($_SESSION['inPro']);
                                    } else {
                                        echo "0";
                                    }
                                    ?>
                                </div>
                                <i class='bx bxs-cart-alt'></i>
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
                            <a href="reg.php">สมัครสมาชิก</a>
                            <a href="login.php">เข้าสู่ระบบ</a>
                        </div>
                    </div>
                    <div class="nav-bottom">
                        <a href="index.php">RMUTI</a>
                        <div class="nav-bottom-search">
                            <input type="text" placeholder="Search...">
                            <button type="button">ค้นหา</button>
                        </div>
                        <div class="nav-bottom-cart">
                            <div class="cart-shop">
                                <div class="cart-count">
                                    0
                                </div>
                                <i class='bx bxs-cart-alt'></i>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </nav>

        <!-- Add your scripts at the bottom -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
        <script src="search.js"></script>

        <script>
            $(document).ready(function () {
                $("#navUserToggle").hide();

                $("#navToggleProfile").click(function () {
                    $("#navUserToggle").slideToggle();
                });
                $("#navUserToggle").mouseleave(function () {
                    $(this).slideUp();
                });
            });
        </script>
    </body>

    </html>
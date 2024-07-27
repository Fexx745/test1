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
</head>

<body>
    
    <nav>
        <div class="container-nav">
            <div class="logo">
                <a href="index.php"><img src="assets/images/other/logo.png" alt="" width="100%"></a>
            </div> <!-- logo -->
            <div class="search">
                <form action="index_search.php" method="POST" class="p-3"
                    style="position: relative; margin-top: -15px;">
                    <div class="input-group">

                        <div class="form-floating">
                            <input type="text" class="form-control" name="search" id="search"
                                placeholder="ค้นหาสินค้า..." autocomplete="off" style="border: none; border-bottom: 1px solid grey;" required>
                            <label for="floatingInputGroup1">ค้นหาสินค้า ...</label>
                        </div>
                        <button type="submit" name="submit" class="btn"
                            style="background: #198754; color: #fff; width: 50px; display: flex; justify-content: center; align-items: center;">
                            <i class='bx bx-search-alt'></i>
                        </button>
                    </div>
                    <div class="col-md-5">
                        <div class="list-group" style="position: absolute; width: 490px;" id="show-list"></div>
                    </div>
                </form>

            </div>


            <div class="authme-form">
                <div class="logo-login">
                    <a href="index.php"><i class='bx bxs-user-circle'></i></a>
                    <!-- <a href="index.php"><i class='bx bxs-user-circle'
                            style="position: absolute; top: 70px; left: 15px; font-size: 30px; color: #363949;"></i></a> -->
                </div>
                <div class="authme">
                    <?php
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    if (isset($_SESSION['username'])) {
                        // เข้าสู่ระบบแล้ว
                        ?>
                        <div class="login">
                            <p>ยินดีต้อนรับ
                                <?php echo $_SESSION['username']; ?>
                            </p>
                        </div>
                        <?php
                    } else {
                        // ยังไม่ได้เข้าสู่ระบบ
                        ?>
                        <div class="login">
                            <a href="login.php">
                                <p>เข้าสู่ระบบ</p>
                            </a>
                        </div>
                        <div class="reg">
                            <a href="login.php">
                                <p>หน้าสมาชิก</p>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>

            </div> <!-- authme-form -->

            <div class="cart-form">
                <div class="logo-cart">
                    <div class="cart-icon">
                        <div class="cart-amount">
                            <b><?php
                            if (isset($_SESSION['inPro']) && !empty($_SESSION['inPro'])){
                                echo $_SESSION['inPro']; 
                            } else {
                                echo "0";
                            }
                            ?></b>
                        </div>
                        <i class='bx bxs-cart' style='color: #363949;'></i>
                    </div>
                </div>
                <div class="cart">
                    <div class="cart-product">
                        <a href="cart.php">
                            <p>ตะกร้าสินค้า</p>
                        </a>
                    </div>
                    <div class="cart-price">
                        <a href="cart.php">
                            <p>
                                <div class="cart-price">
                                    <?php
                                    if (isset($_SESSION["sum_price"]) && !empty($_SESSION["sum_price"])) {
                                        echo number_format($_SESSION["sum_price"], 2) . " ฿";
                                    } else {
                                        echo "0.00 ฿";
                                    }
                                    ?>
                                </div>

                            </p>
                        </a>
                    </div>

                </div>
            </div>

            <div class="dropdown">
                <button id="homepage" class="btn btn-secondary rounded-5" type="button">
                    <a href="index.php"><i class='bx bx-home'></i> หน้าหลัก</a>
                </button>
                &nbsp;&nbsp;&nbsp;
                <button class="btn btn-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php
                    // ตรวจสอบว่าเซสชันยังไม่ได้เปิดหรือไม่
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    if (isset($_SESSION['username'])) {
                        echo "<i class='bx bx-caret-down'></i>";
                    } else {
                        echo "" . " " . "<i class='bx bxs-chevron-down'></i>"; // ถ้ายังไม่มี session 'username' ให้แสดงข้อความ "เข้าสู่ระบบ"
                    }
                    ?>
                </button>
                <ul class="dropdown-menu">
                    <?php
                    if (isset($_SESSION['username'])) {
                        // echo '<li><a class="dropdown-item" href="Edit-Profile.php"><i class="bx bx-edit-alt"></i>&nbsp;แก้ไขข้อมูลส่วนตัว</a></li>';
                        echo '<li><a class="dropdown-item" href="Logout.php"><i class="bx bx-log-out"></i>&nbsp;ออกจากระบบ</a></li>';
                    } else {
                        echo "<li><a class='dropdown-item' href='Logout.php'><i class='bx bx-log-in' ></i>&nbsp;เข้าสู่ระบบ</a></li>";
                    }
                    ?>
                </ul>
            </div> <!-- dropdown -->
        </div>
    </nav>


</body>

</html>

<!-- Bootrap -->
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="bootstrap/js/bootstrap.bundle.min.js"> </script>

<!-- Add your scripts at the bottom -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<script src="search.js"></script>
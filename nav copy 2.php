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

    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    ?>


    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">RMUTI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                    <!-- dropdown -->
                    <div class="dropdown">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://github.com/mdo.png" alt="mdo" width="42" height="42" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
                            <?php if (isset($_SESSION['username'])) : ?>
                                <!-- เข้าสู่ระบบแล้ว -->
                                <li><a class="dropdown-item" href="#"><i class='bx bx-user'></i> Hi.. <?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                                <li class="dropdown-header">Nav header</li>
                                <li><a class="dropdown-item" href="#"><i class='bx bx-cart-add'></i> ตะกร้าสินค้า</a></li>
                                <li><a class="dropdown-item" href="view-order-history.php"><i class='bx bx-car' ></i> ดูประวัติการสั่งซื้อ</a></li>
                                <li><a class="dropdown-item" href="edit-profile.php?id=<?php echo htmlspecialchars($_SESSION['user_id']); ?>"><i class='bx bx-edit'></i> แก้ไขโปรไฟล์</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="logout.php"><i class='bx bx-log-out'></i> ออกจากระบบ</a></li>
                            <?php else : ?>
                                <!-- ยังไม่ได้เข้าสู่ระบบ -->
                                <li><a class="dropdown-item" href="login.php">เข้าสู่ระบบ</a></li>
                                <li><a class="dropdown-item" href="register.php">หน้าสมาชิก</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                            <!-- end dropdown -->
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class='bx bx-car' style="font-size: 1.2vw;">ดูประวัติการสั่งซื้อ</i></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            ค้นหาด้วยประเภท
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Link</a>
                    </li>
                </ul>

                <!-- search -->
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Bootrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"> </script>

    <!-- Add your scripts at the bottom -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="search.js"></script>

</body>

</html>
<?php
include('condb.php');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <link rel="stylesheet" href="assets/css/footer.css">

    <!-- Bootrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"> </script>
    <script src="assets/dist/sweetalert2.all.min.js"></script>
    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <?php include('nav.php'); ?>

    <div class="container">

        <!-- Slideshow -->
        <div id="carouselExample" class="carousel slide" data-ride="carousel" style="height: 300px;">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" style="object-fit: contain; height: 100%;"
                        src="assets/images/banner/2.jpg" alt="First slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" style="object-fit: cover; height: 100%;" src="assets/images/banner/2.jpg"
                        alt="Second slide">
                </div>
                <!-- Add more slides as needed -->
            </div>

            <!-- Carousel controls -->
            <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only"></span>
            </a>
            <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only"></span>
            </a>
        </div>

        <div class="container-card2">
            <div class="categories-box">
                <div class="cate1">
                    <h6><i class='bx bxs-notepad'></i>&nbsp;ข้อมูลส่วนตัว</h6>
                </div>

                <?php
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                if (isset($_SESSION['username'])) {

                    ?>

                    <!-- <p class="alert alert-dark m-5">ชื่อผู้ใช้ <?php echo $_SESSION['username']; ?></p> -->

                    <div class="grid gap-0 row-gap-3 text-center">
                        <div class="p-2" style="margin: 0 40px 0 40px;">
                            <img src="assets/images/other/user-no-img.jpg" alt=""
                                style="width: 115px; height: 100px; object-fit: cover;">
                        </div>
                        <div class="p-2 g-col-6 d-flex justify-content-center">
                            <?php
                            $sql22 = "SELECT id FROM tb_member";
                            $result22 = mysqli_query($conn, $sql22);
                            $row22 = mysqli_fetch_array($result22);
                            ?>

                            <a href="edit-profile.php?id=<?= $row22['id'] ?>" class="btn"
                                style="margin-right: 10px; background: #dc3545; color: #fff;"><i
                                    class='bx bxs-user-badge'></i>&nbsp;แก้ไข
                            </a>
                            <a href="#" class="btn" style="background: #fd7e14; color: #fff;"><i
                                    class='bx bxs-car'></i>&nbsp;ติดตามสินค้า</a>
                        </div>

                    </div>
                <?php } else { ?>
                    <p class='text-center'>กรุณาเข้าสู่ระบบ</p>
                    <?php
                }
                ?>

                <form action="index_search.php" method="POST" class="p-3" style="position: relative;">
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control border-info rounded-0"
                            placeholder="ค้นหาสินค้า..." autocomplete="off" required>
                        <div class="input-group-append">
                            <button type="submit" name="submit" class="btn btn-info">
                                <i class='bx bx-search-alt'></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="list-group" style="position: absolute; width: 200px;" id="show-list"></div>
                    </div>
                </form>

                <div class="cate2">
                    <h6><i class='bx bxs-category'></i>&nbsp;หมวดหมู่สินค้า</h6>
                </div>
                    <div class="input-group">
                        <div class="input-group p-3">
                            <label class="input-group-text" for="typeprd"><i class='bx bx-layer-plus'></i></label>
                            <select name="typeprd" id="typeprd" class="form-select">
                                <?php
                                include('condb.php');
                                $sql = "SELECT DISTINCT t.type_id, t.type_name FROM product as p INNER JOIN product_type as t ON p.type_id = t.type_id ORDER BY t.type_id";
                                $result = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($rows = mysqli_fetch_array($result)) {
                                        ?>
                                        <option value='<?php echo $rows['type_id']; ?>'>
                                            <?php echo $rows['type_name']; ?>
                                        </option>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <option value=''>No types found</option>
                                    <?php
                                }
                                ?>
                            </select>
                            <div class="input-group-append">
                            <button type="submit" name="submit" class="btn btn-info">
                                <i class='bx bx-search-alt'></i>
                            </button>
                        </div>
                        </div>
                    </div>
                <div class="cate3">
                    <h6><i class='bx bxs-package'></i>&nbsp;สินค้าตามแพ็คเกจ</h6>
                </div>
                <ul>
                    <li><a href="#"><i class='bx bx-chevron-right'></i>เมล็ดพันธ์แบบของ</a></li>
                    <li><a href="#"><i class='bx bx-chevron-right'></i>เมล็ดพันธ์แบบห่อ</a></li>
                    <li><a href="#"><i class='bx bx-chevron-right'></i>เมล็ดพันธ์แบบประป๋อง</a></li>
                    <li><a href="#"><i class='bx bx-chevron-right'></i>เมล็ดพันธ์แบบลัง</a></li>
                    <li><a href="#"><i class='bx bx-chevron-right'></i>เมล็ดพันธ์แบบขวด</a></li>
                    <li><a href="#"><i class='bx bx-chevron-right'></i>เคมีเกษตรแบบขวด</a></li>
                    <li><a href="#"><i class='bx bx-chevron-right'></i>เคมีเกษตรแบบลัง</a></li>
                    <li><a href="#"><i class='bx bx-chevron-right'></i>ปุ๋ยแบบถุง</a></li>
                    <li><a href="#"><i class='bx bx-chevron-right'></i>ปุ๋ยแบบกระสอบ</a></li>
                </ul>
            </div> <!-- end categories-box -->

            <div class="products-box text-center">
                <?php
                $limit = 12; // จำนวนสินค้าที่แสดงในแต่ละหน้า
                $page = isset($_GET['page']) ? $_GET['page'] : 1; // หมายเลขหน้าที่ต้องการแสดง
                $start = ($page - 1) * $limit; // คำนวณเริ่มต้นแสดงผลสินค้า
                $sql = "SELECT * FROM product 
                LEFT JOIN price_history ON product.p_id = price_history.p_id 
                LEFT JOIN unit_type ON product.unit_id = unit_type.unit_id
                LEFT JOIN brand_type ON product.brand_id = brand_type.brand_id
                LEFT JOIN product_type ON product.type_id = product_type.type_id
                ORDER BY product.p_id LIMIT $start, $limit";
                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_array($result)) {
                    $check_amount = $row['amount'];
                    ?>
                    <div class="product-card">
                        <img src="assets/images/product/<?= $row['image'] ?>" alt="" class="mb-1">
                        <p><b>
                                <?= $row['p_name'] ?>
                            </b></p>
                        <div class="card-price">
                            <div>
                                <span>ราคาต่อ <span style="color: blue;">
                                        <?= $row['unit_name']; ?>
                                    </span></span>
                            </div>
                            <div>
                                <span>เหลือสินค้าจำนวน
                                    <b style="color: <?= $row['amount'] == 0 ? '#fd7e14' : 'green' ?>">
                                        <?= $row['amount'] ?>
                                    </b>
                                    <span>
                                        <?= $row['unit_name']; ?>
                                    </span>
                                </span>
                            </div>

                            <div>
                                <span>ราคา</span>
                                <b style="color: red;">
                                    <?= number_format($row['price'], 2) ?>
                                </b>
                                <span>บาท</span>
                            </div>
                        </div>

                        <div class="card-button">
                            <?php
                            if ($check_amount <= 0) {
                                echo '<a class="btn btn-danger disabled"><i class="bx bxs-error-alt"></i>&nbsp;สินค้าหมด</a>';
                            } else {
                                echo '<a class="btn" style="margin-right: 5px; background: #198754; color: #fff;" href="show_pd_detail.php?id=' . $row['p_id'] . '">ดูรายละเอียด</a>';
                                echo '<a class="btn" style="background: #0d6efd; color: #fff;" href="order.php?id=' . $row['p_id'] . '"><i class="bx bx-cart-add" ></i></a>';
                            }
                            ?>
                        </div>
                    </div> <!-- product-card -->
                    <?php
                }
                mysqli_close($conn);
                ?>


                <!-- ตำแหน่งปุ่มเปลี่ยนหน้า -->
                <div class="pagination">
                    <?php
                    include('condb.php');
                    $limit = 12; // จำนวนสินค้าที่แสดงในแต่ละหน้า
                    $page = isset($_GET['page']) ? $_GET['page'] : 1; // หมายเลขหน้าที่ต้องการแสดง
                    $start = ($page - 1) * $limit; // คำนวณเริ่มต้นแสดงผลสินค้า
                    
                    $sql = "SELECT COUNT(*) FROM product";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_row($result);
                    $total_records = $row[0];
                    $total_pages = ceil($total_records / $limit);
                    // ป้องกันการเลือกหน้าที่ไม่ถูกต้อง
                    if ($page > $total_pages) {
                        header('Location: index.php?page=' . $total_pages);
                        exit;
                    }

                    // แสดงปุ่ม "Previous"
                    if ($page > 1) {
                        echo "<a href='index.php?page=" . ($page - 1) . "'><i class='bx bx-first-page' ></i></a>";
                    }

                    // แสดงปุ่มเลขหน้า
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo "<a href='index.php?page=" . $i . "'>" . $i . "</a> ";
                    }

                    // แสดงปุ่ม "Next"
                    if ($page < $total_pages) {
                        echo "<a href='index.php?page=" . ($page + 1) . "'><i class='bx bx-last-page' ></i></a>";
                    }
                    mysqli_close($conn);
                    ?>
                </div>
            </div> <!-- products-box -->
        </div> <!-- container-card2-->
    </div> <!-- container -->
    <?php include('footer.php'); ?>
</body>

</html>
<!-- Add your scripts at the bottom -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<script src="search.js"></script>









<div class="container">
        <div class="row">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                data-bs-toggle="dropdown" aria-expanded="false">
                Categories
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <?php
                $sql = "SELECT * FROM product_type";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <li>
                        <a class="dropdown-item" href="#">
                            <img src="assets/images/type_product/<?= $row['type_image'] ?>" alt="<?= $row['type_name'] ?>"
                                class="me-2" style="max-width: 30px;">
                            <?= $row['type_name'] ?>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        </div>
       
    </div>
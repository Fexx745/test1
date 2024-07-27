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

    <?php
    $sql = "SELECT * FROM banners";
    $result = mysqli_query($conn, $sql);
    ?>

    <!-- Slideshow -->
    <div class="container">
        <div id="carouselExample" class="carousel slide" data-ride="carousel">
            <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $firstSlide = true;  // ตั้งค่าเริ่มต้นให้ slide แรกมี class active
                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <div class="carousel-item <?php echo ($firstSlide) ? 'active' : ''; ?>">
                            <img class="d-block w-100" src="assets/images/banner/<?php echo $row['image_path']; ?>"
                                alt="Slide Image">
                        </div>

                        <?php
                        $firstSlide = false;  // หลังจากแสดง slide แรกแล้วก็เปลี่ยนค่าเป็น false
                    }
                    ?>
                </div>
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
    </div>


</body>

</html>
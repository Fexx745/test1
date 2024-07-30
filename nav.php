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
    <link rel="stylesheet" href="./assets/css/index.css">
    <link rel="stylesheet" href="./assets/css/nav.css">
</head>

<body>


    <nav>
        <div class="nav-container">
            <div class="nav-top">
                <div class="nav-top-contract">
                    <ul>
                        <li><a href="">หน้าหลัก</a></li>
                        <li><a href="">ติดต่อ</a></li>
                    </ul>
                </div>
                <div class="nav-top-user">
                    <div class="nav-top-user-profile">
                        <img src="" alt="">
                    </div>
                    <p>Chatupon Singkrajom</p>
                </div>
            </div>
            <div class="nav-bottom">
                <h1>RMUTI</h1>

                <div class="nav-bottom-cart">
                    <div class="cart-shop">
                        <div class="cart-count">1</div>
                        <i class='bx bxs-cart-alt'></i>
                    </div>
                </div>
            </div>
        </div>
    </nav>


    <!-- Bootrap -->
    <!-- <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"> </script> -->

    <!-- Add your scripts at the bottom -->
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="search.js"></script> -->

</body>

</html>
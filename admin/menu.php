<?php
include("condb.php");
//แก้ไขข้อมูลลูกค้า
$sql5 = "SELECT * FROM tb_member";
$result5 = mysqli_query($conn, $sql5);
$row5 = mysqli_fetch_assoc($result5);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />

    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');

        * {
            font-size: 16px;
            font-family: 'K2D', sans-serif;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* ปรับตามความต้องการ */
        }
    </style>
</head>

<body>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">Dashboard</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">

                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i>&nbsp;<?php //echo $_SESSION['username']; 
                                                                                                                                                                                        ?></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <!-- <li><a class="dropdown-item" href="#!"><i class='bx bxs-cog'></i>&nbsp;การตั้งค่า</a></li> -->
                    <li><a class="dropdown-item" href="show_account.php?id=<?= $row5['id'] ?>"><i class='bx bxs-user-account'></i>&nbsp;จัดการข้อมูลลูกค้า</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="../logout.php"><i class='bx bx-log-in'></i>&nbsp;Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Home</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class='bx bxs-store-alt'></i></div>
                            หน้าหลัก
                        </a>
                        <div class="sb-sidenav-menu-heading">Interface</div>

                        <!-- จัดการสินค้า -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#edit-product" aria-expanded="false" aria-controls="edit-product">
                            <div class="sb-nav-link-icon"><i class='bx bx-box'></i></div>
                            จัดการสินค้า
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="edit-product" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="product_List.php"><i class='bx bx-edit'></i>&nbsp;จัดการสินค้า</a>
                                <a class="nav-link" href="producttype_List.php"><i class='bx bx-edit'></i>&nbsp;จัดการประเภทสินค้า</a>
                                <a class="nav-link" href="unit_add.php"><i class='bx bx-edit'></i>&nbsp;จัดการหน่วยสินค้า</a>
                                <a class="nav-link" href="brand_add.php"><i class='bx bx-edit'></i>&nbsp;จัดการแบรนด์สินค้า</a>
                            </nav>
                        </div>

                        <!-- ลูกค้า -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#customer" aria-expanded="false" aria-controls="customer">
                            <div class="sb-nav-link-icon"><i class='bx bxs-user-detail'></i></div>
                            ลูกค้า
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="customer" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="member_add.php"><i class='bx bxs-user-plus'></i>&nbsp;เพิ่มบัญชีลูกค้า</a>
                                <a class="nav-link" href="member_List.php"><i class='bx bx-edit'></i>&nbsp;จัดการข้อมูลลูกค้า</a>
                            </nav>
                        </div>

                        <!-- การตั้งค่าต่างๆ -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#setting" aria-expanded="false" aria-controls="setting">
                            <div class="sb-nav-link-icon"><i class='bx bx-cog'></i></div>
                            ตั้งค่า
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="setting" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="Line_notify.php"><i class='bx bxs-bell-ring' ></i>&nbsp;Line Nottify</a>
                                <a class="nav-link" href="banner_add.php"><i class='bx bx-image-alt'></i>&nbsp;แก้ไขรูปภาพ Banner</a>
                            </nav>
                        </div>

                        <!-- เกี่ยวกับขนส่ง -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#shipping" aria-expanded="false" aria-controls="shipping">
                            <div class="sb-nav-link-icon"><i class='bx bx-car'></i></div>
                            การส่งสินค้า
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="shipping" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="shipping_add.php"><i class='bx bxs-car-mechanic'></i>&nbsp;จัดการข้อมูลขนส่ง</a>
                            </nav>
                        </div>

                        <!-- เกี่่ยวกับการขาย -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#summary" aria-expanded="false" aria-controls="summary">
                            <div class="sb-nav-link-icon"><i class='bx bx-line-chart'></i></div>
                            เกี่ยวกับการขาย
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="summary" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="summary.php"><i class='bx bx-bar-chart-alt-2'></i>&nbspสรุปยอดขาย</a>
                                <a class="nav-link" href="report_order.php">
                                    <div class="sb-nav-link-icon"><i class='bx bx-calendar-check'></i></div>
                                    ตรวจสอบคำสั่งซื้อ
                                </a>
                            </nav>
                        </div>



                        <!-- <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            อื่นๆ
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a> -->
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                    <i class='bx bxs-contact'></i>&nbsp;สมัครและแก้ไข
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="editmember.php">แสดงข้อมูลลูกค้า</a>
                                        <a class="nav-link" href="register.php">Register</a>
                                        <a class="nav-link" href="password.php">เปลี่ยนรหัสผ่าน</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                    Error
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="401.html">401 Page</a>
                                        <a class="nav-link" href="404.html">404 Page</a>
                                        <a class="nav-link" href="500.html">500 Page</a>
                                    </nav>
                                </div>
                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Addons</div>
                        <!-- <a class="nav-link" href="report_order.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            ตรวจสอบคำสั่งซื้อ
                        </a> -->
                        <!-- <a class="nav-link" href="tables.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Tables
                        </a> -->
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <!-- Start Bootstrap -->
                </div>
            </nav>
        </div>
</body>

</html>
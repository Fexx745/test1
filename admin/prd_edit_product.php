<?php
session_start();
include('condb.php');

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
} elseif ($_SESSION['status'] !== '1') {
    header('Location: ../login.php');
    exit();
}

$idpd = $_GET['id'];
$sql = "SELECT product.*, price_history.price
        FROM product
        LEFT JOIN price_history ON product.p_id = price_history.p_id
        WHERE product.p_id = '$idpd'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <!-- Font -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="assets/dist/sweetalert2.all.min.js"></script>
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

<body class="sb-nav-fixed">

    <?php include('menu.php') ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 mt-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        แก้ไขข้อมูลสินค้า
                    </div>
                    <div class="card-body">
                        <h3 class="alert alert-primary">แก้ไขข้อมูลสินค้า</h3>
                        <form method="POST" action="prd_update.php" enctype="multipart/form-data">
                            <div class="mb-3 mt-3">
                                <input type="hidden" class="form-control alert alert-success" name="pid"
                                    value="<?= $row['p_id']; ?>" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-package'></i></span>
                                <input type="text" class="form-control" name="pname" value="<?= $row['p_name']; ?>">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-detail'></i></span>
                                <input type="text" class="form-control" name="detail" value="<?= $row['detail']; ?>">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">ประเภท&nbsp;&gt;</label>
                                    <?php
                                    $sql5 = "SELECT DISTINCT t.type_id, t.type_name 
                                    FROM product_type as t 
                                    LEFT JOIN product as p 
                                    ON p.type_id = t.type_id 
                                    ORDER BY t.type_id";
                                    $result5 = mysqli_query($conn, $sql5);

                                    if ($result5) {
                                        if (mysqli_num_rows($result5) > 0) {
                                            echo '<div class="input-group mb-3">';
                                            echo '<label class="input-group-text" for="typeprd"><i class="bx bx-category-alt"></i></label>';
                                            echo '<select name="typeprd" class="form-select">';

                                            while ($typeRow = mysqli_fetch_assoc($result5)) {
                                                $selected = ($row['type_id'] == $typeRow['type_id']) ? 'selected' : '';
                                                echo '<option value="' . $typeRow['type_id'] . '" ' . $selected . '>' . $typeRow['type_name'] . '</option>';
                                            }

                                            echo '</select>';
                                            echo '</div>';
                                        } else {
                                            echo 'No product types found.';
                                        }
                                    } else {
                                        echo 'Error: ' . mysqli_error($conn);
                                    }

                                    mysqli_close($conn);
                                    ?>
                                </div>



                                <div class="col-md-4">
                                    <label for="">หน่วย&nbsp;&gt;</label>
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" for="unittype"><i
                                                class='bx bx-cube'></i></label>
                                        <select name="unittype" id="unittype" class="form-select">
                                            <?php
                                            include('condb.php');
                                            $sql2 = "SELECT DISTINCT u.unit_id, u.unit_name FROM unit_type as u ORDER BY u.unit_id";
                                            $result2 = mysqli_query($conn, $sql2);

                                            if (mysqli_num_rows($result2) > 0) {
                                                while ($row2 = mysqli_fetch_assoc($result2)) {
                                            ?>
                                                    <option value="<?= $row2['unit_id']; ?>"
                                                        <?= ($row['unit_id'] == $row2['unit_id']) ? 'selected' : ''; ?>>
                                                        <?= $row2['unit_name']; ?>
                                                    </option>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <option value='' disabled>No types found</option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <label for="">ยี่ห้อ&nbsp;&gt;</label>
                                    <div class="input-group mb-3">
                                        <label class="input-group-text" for="brandprd"><i
                                                class='bx bx-tag'></i></label>
                                        <select name="brandprd" id="brandprd" class="form-select">
                                            <?php
                                            include('condb.php');
                                            $sql3 = "SELECT DISTINCT b.brand_id, b.brand_name FROM brand_type as b ORDER BY b.brand_id";
                                            $result3 = mysqli_query($conn, $sql3);

                                            if (mysqli_num_rows($result3) > 0) {
                                                while ($row3 = mysqli_fetch_assoc($result3)) {
                                            ?>
                                                    <option value="<?= $row3['brand_id']; ?>"
                                                        <?= ($row['brand_id'] == $row3['brand_id']) ? 'selected' : ''; ?>>
                                                        <?= $row3['brand_name']; ?>
                                                    </option>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <option value='' disabled>No brands found</option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <label for="">จำนวนสินค้าในสต็อก&nbsp;&gt;</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-layer'></i></span>
                                <input type="number" class="form-control" name="amount" value="<?= $row['amount']; ?>" placeholder="จำนวนสินค้าในสต็อก">
                            </div>
                            <div class="mb-3 mt-3">
                                <img src="../assets/images/product/<?= $row['image'] ?>" alt="รูปภาพปัจจุบัน"
                                    style="margin: 0 0 10px 0; width: 100px; height: 150px; object-fit: contain;">
                                <input type="file" class="form-control" name="image">
                            </div>
                            <div class="mt-4">
                                <a href="prd_show_product.php" class="btn btn-primary">ย้อนกลับ</a>
                                <button class="btn btn-success" type="submit">ตกลง</button>
                            </div>
                        </form>
                    </div> <!-- card-body -->
                </div>
            </div>
        </main>

        <?php include('footer.php') ?>
    </div>
    </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>

<?php
if (isset($_SESSION['edit_product'])) {
?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "แก้ไขสำเร็จ!",
            text: "Successfully",
            showConfirmButton: false,
            timer: 1500
        });
    </script>

<?php
    unset($_SESSION['edit_product']);
}
?>
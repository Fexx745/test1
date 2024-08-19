<?php
session_start();
include('condb.php');

if (!isset($_SESSION['username'])) {
    header('Location: reg.php');
    exit();
} else if ($_SESSION['status'] !== '0') {
    header('Location: reg.php');
    exit();
}
// ตรวจสอบและกำหนดค่าเริ่มต้นให้กับ $_SESSION['inPro']
if (!isset($_SESSION['inPro'])) {
    $_SESSION['inPro'] = 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/nav.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <style>
        .container {
            margin-top: 120px;
        }
    </style>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"> </script>
    <script src="assets/dist/sweetalert2.all.min.js"></script>
    <title>Cart</title>
</head>

<body>
    <?php include('nav.php'); ?>
    <!-- <br><br> -->
    <div class="container">
        <!-- <div style="border: 2px solid #adb5bd; border-radius: 10px; padding: 20px;"> -->
        <form action="cart_insert.php" id="form1" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex">
                        <h1>ตะกร้าสินค้า</h1>
                        <!-- <h1 style="color: red;">(<?php echo $_SESSION['inPro'] ?>)</h1> -->
                    </div>

                    <table class="table table-hover my-3">
                        <tr>
                            <th>ลำดับที่</th>
                            <th>รูปภาพ</th>
                            <th>ชื่อสินค้า</th>
                            <th>ราคา</th>
                            <th>จำนวน</th>
                            <th>ราคารวม</th>
                            <th>ปริมาณ</th>
                            <th></th>
                        </tr>
                        <?php
                        $Total = 0;
                        $sumPrice = 0;
                        $m = 1;
                        if (isset($_SESSION["intLine"])) {   //ถ้าไม่เป็นค่าว่างให้ทำงานใน {}

                            for ($i = 0; $i <= (int) $_SESSION["intLine"]; $i++) {
                                if (($_SESSION["strProductID"][$i]) != "") {
                                    //อันเดิม
                                    // $sql1 = "SELECT * FROM product WHERE p_id = '" . $_SESSION["strProductID"][$i] . "' ";

                                    //join ตาราง price_history
                                    $sql1 = "SELECT product.*, price_history.*, unit_type.*
                                    FROM product 
                                    LEFT JOIN price_history ON product.p_id = price_history.p_id 
                                    LEFT JOIN unit_type ON product.unit_id = unit_type.unit_id
                                    WHERE product.p_id = '" . $_SESSION["strProductID"][$i] . "'";

                                    $result1 = mysqli_query($conn, $sql1);
                                    $row_product = mysqli_fetch_array($result1);

                                    $_SESSION["price"] = $row_product['price'];
                                    $Total = $_SESSION["strQty"][$i]; //จำนวน
                                    $sum = $Total * $row_product['price']; //ผลรวม
                                    $sumPrice = $sumPrice + $sum; // ราคารวม
                                    $_SESSION["sum_price"] = $sumPrice;

                        ?>
                                    <tr>
                                        <td>
                                            <?= $m ?>
                                        </td>
                                        <td>
                                            <img src="assets/images/product/<?= $row_product['image'] ?>" width="80px" height="100" class="border" style="object-fit: cover;">
                                        </td>
                                        <td>
                                            <?= $row_product['p_name'] ?>
                                        </td>
                                        <td>
                                            <?= number_format($row_product['price'], 2) ?>
                                        </td>
                                        <td>
                                            <?= $_SESSION["strQty"][$i] . " " . $row_product['unit_name'] ?>
                                        </td>
                                        <td>
                                            <?= number_format($sum, 2) ?>
                                        </td>
                                        <td>
                                            <a href="order.php?id=<?= $row_product['p_id'] ?>"><button type="button" class="btn" style="background: #fd7e14; color: #fff; font-weight: bold; border-radius: 5px; line-height: 1;">+</button></a>
                                            <!-- เชคจำนวนสินค้าถ้ามี 1 ปุ่ม - จะไม่มี -->
                                            <?php if ($_SESSION["strQty"][$i] > 1) { ?>
                                                <a href="order_del.php?id=<?= $row_product['p_id'] ?>"><button type="button" class="btn" style="background: #f8f9fa; color: #fd7e14; font-weight: bold; border-radius: 5px; line-height: 1; border: 1px solid #fd7e14;">-</button></a>
                                            <?php } ?>
                                        </td>
                                        <!-- <td>
                                            <a href="cart_delete.php?Line=<?= $i ?>"><button type="button"
                                                    class="btn btn-danger"><i class='bx bx-trash' ></i></button></a>
                                        </td> -->
                                        <td>
                                            <button type="button" class="btn btn-danger" style="line-height: 1;" onclick="confirmDeleteCart(<?= $i ?>)">
                                                <i class='bx bx-trash-alt'></i>
                                            </button>
                                        </td>

                                    </tr>
                        <?php
                                    $m = $m + 1;
                                    $_SESSION['dePro'] = $m - 1;
                                }
                            }
                            // Update the number of products in cart
                            $_SESSION['inPro'] = $m - 1;
                        } //end if
                        ?>
                        <tr>
                            <td class="text-end fs-5" colspan="6">รวมเป็นเงิน</td>
                            <td>
                                <span class="fs-5" style="color: #dc3545; font-weight: 600;">
                                    <?= number_format($sumPrice, 2) ?>
                                </span>
                            </td>
                            <td class="text-start fs-5">บาท</td>
                        </tr>
                    </table>
                    <div class="container" style="margin-top: 20px; margin-bottom: 100px;">
                        <div class="row">
                            <div class="col-md-6 alert" style="background: rgba(229, 229, 229, 0.8); color: #333; border: none; outline: none;">

                                <h4>
                                    <i class='bx bx-info-circle'></i>
                                    &nbsp;โปรดแนบสลิปการโอนทุกครั้งที่ทำการชำระเงิน
                                </h4>
                                <b>รองรับไฟล์ .png .jpg</b>
                                <div class="text-end">
                                    <div class="input-group">
                                        <input type="hidden" class="form-control" name="payment_date" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class='bx bxs-camera fs-4'></i></span>
                                    <input type="file" class="form-control" name="slip_image" required>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="index.php"><button type="button" class="btn" style="background: #fcf5f4; margin: 0 10px; border: 1px solid #fd7e14; color: #000; font-size: 15px; font-weight: 600;"><i class='bx bx-cart-add'></i> เลือกสินค้าต่อ</button></a>
                                    <a href=""><button type="submit" class="btn" style="background: #fd7e14; border: none; color: #fff;">สั่งซื้อ</button></a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- col-md-13 -->
            </div> <!-- row -->
        </form>
    </div> <!-- container -->

    <?php
    mysqli_close($conn);
    ?>

    <?php
    include('footer.php');
    ?>
</body>

</html>
<?php
if (isset($_SESSION['cart-success'])) {
?>
    <script>
        Swal.fire({
            icon: "success",
            title: "คำสั่งซื้อของคุณสำเร็จแล้ว!",
            text: "ขอบคุณสำหรับการสั่งซื้อ",
            showConfirmButton: false,
            timer: 1500
        }).then(function() {
            window.location.href = 'view-order-history.php';
        });
    </script>

<?php
    unset($_SESSION['cart-success']);
}
?>


<!-- upload_error -->
<?php
if (isset($_SESSION['upload_error'])) {
?>
    <script>
        Swal.fire({
            icon: "info",
            title: "ข้อผิดพลาดในการอัปโหลดไฟล์!",
            text: "<?php echo $_SESSION['upload_error']; ?>",
            showConfirmButton: true,
            timer: 5000
        }).then(function() {
            window.location.href = 'cart.php';
        });
    </script>

<?php
    unset($_SESSION['upload_error']);
}
?>

?>

<script>
    function confirmDeleteCart(Line) {
        Swal.fire({
            title: "คุณแน่ใจหรือไม่?",
            text: "คุณต้องการลบสินค้านี้ออกจากตะกร้าใช่หรือไม่?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ตกลง",
            cancelButtonText: "ยกเลิก"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `cart_delete.php?Line=${Line}`;
            }
        });
    }
</script>
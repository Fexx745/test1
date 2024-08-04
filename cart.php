<?php
session_start();
include('condb.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
} else if ($_SESSION['status'] !== '0') {
    header('Location: login.php');
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
                        <h1 style="color: red;">(<?php echo $_SESSION['inPro'] ?>)</h1>
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
                                    $sql1 = "SELECT product.*, price_history.* 
                                    FROM product 
                                    LEFT JOIN price_history ON product.p_id = price_history.p_id 
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
                                            <?= $_SESSION["strQty"][$i] ?>
                                        </td>
                                        <td>
                                            <?= number_format($sum, 2) ?>
                                        </td>
                                        <td>
                                            <a href="order.php?id=<?= $row_product['p_id'] ?>"><button type="button" class="btn" style="background: #495057; color: #fff; font-weight: bold;">+</button></a>
                                            <!-- เชคจำนวนสินค้าถ้ามี 1 ปุ่ม - จะไม่มี -->
                                            <?php if ($_SESSION["strQty"][$i] > 1) { ?>
                                                <a href="order_del.php?id=<?= $row_product['p_id'] ?>"><button type="button" class="btn" style="background: #6c757d; color: #fff; font-weight: bold;">-</button></a>
                                            <?php } ?>
                                        </td>
                                        <!-- <td>
                                            <a href="cart_delete.php?Line=<?= $i ?>"><button type="button"
                                                    class="btn btn-danger"><i class='bx bx-trash' ></i></button></a>
                                        </td> -->
                                        <td>
                                            <button type="button" class="btn btn-danger" onclick="confirmDeleteCart(<?= $i ?>)">
                                                <i class='bx bx-trash'></i>
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
                                <span class="fs-5" style="color: green; font-weight: bold;">
                                    <?= number_format($sumPrice, 2) ?>
                                </span>
                            </td>
                            <td class="text-start fs-5">บาท</td>
                        </tr>
                    </table>
                    <div class="container" style="margin-top: 20px; margin-bottom: 100px;">
                        <div class="row">

                            <!-- <div class="col-md-6">
                                <img style="width:600px; height: 600px; object-fit: cover;" src="assets/images/other/slip.jpg" alt="">
                            </div> -->

                            <div class="col-md-12">
                                <h4 class="alert alert-danger"><i class='bx bx-error-alt'></i>
                                    &nbsp;ให้แนบสลิปการโอนทุกทั้งครับ/ค่ะ .png .jpg</h4>
                                <div class="text-end">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class='bx bx-money'></i></span>
                                        <input type="text" class="form-control" name="payment_amount"
                                            placeholder="จำนวนเงินที่ชำระ" required
                                            value="<?php echo number_format($sumPrice, 2); ?>" readonly>
                                    </div>
                                    <div class="input-group">
                                        <input type="hidden" class="form-control" name="payment_date" required>
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class='bx bxs-bank fs-4'></i></span>
                                        <input type="file" class="form-control" name="slip_image" required>
                                    </div>
                                    <a href="index.php"><button type="button" class="btn text-white" style="background: #343a40;">เลือกสินค้า</button></a>
                                    <a href=""><button type="submit" class="btn btn-danger">ยืนยันการสั่งซื้อ</button></a>
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
            // position: "top-center",
            icon: "success",
            title: "สั่งซื้อสินค้าสำเร็จ!",
            text: "Successfully",
            showConfirmButton: false,
            timer: 1500
        }).then(function() {
            window.location.href = 'print_order.php';
        });
    </script>

<?php
    unset($_SESSION['cart-success']);
}
?>

<script>
    function confirmDeleteCart(Line) {
        Swal.fire({
            title: "Are you sure?",
            text: "ลบสินค้าออกจากตะกร้า?",
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


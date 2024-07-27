<?php
    session_start();
    include('condb.php');
    $idpd = $_POST['pid'];
    $pdamount = $_POST['amount'];

    $sql = "UPDATE product SET amount = amount + $pdamount WHERE p_id = '$idpd'";
    $result = mysqli_query($conn, $sql);
    if($result) {
        $_SESSION['addstock'] = "เพิ่มสต็อก";
        header('Location: prd_show_product.php');
        exit();
        
    } else {
        echo "<script> alert('ไม่สามารถเพิ่มสต็อกสินค้าได้!'); </script>";
    }

    mysqli_close($conn);

?>
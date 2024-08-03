<?php
// add_review.php
session_start();
include('condb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id']; // สมมติว่า session มีการเก็บ user_id

    $sql = "INSERT INTO product_reviews (p_id, member_id, rating, comment) VALUES ('$product_id', '$user_id', '$rating', '$comment')";
    if (mysqli_query($conn, $sql)) {
        header("Location: itemsDetail.php?id=$product_id"); // เปลี่ยนเส้นทางกลับไปที่หน้า product_detail
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการเพิ่มรีวิว: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Results</title>
    <?php include('script-css.php'); ?>
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/nav.css">
</head>

<body>
    <div class="container">

        <?php
        include('nav.php');
        include('bc-menu.php');
        // กำหนดจำนวนสินค้าที่แสดงในแต่ละหน้า
        $limit = 20; 
        $page = isset($_GET['page']) ? $_GET['page'] : 1; 
        $start = ($page - 1) * $limit; 
        
        // Fetching product type ID from GET request
        $type_id = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;
        
        // Prepare and bind
        $stmt = $conn->prepare("
        SELECT 
            p.p_id, 
            p.p_name, 
            p.detail, 
            p.amount, 
            p.p_view, 
            p.image, 
            ph.price, 
            pt.type_name 
        FROM 
            product p 
        JOIN 
            price_history ph ON p.p_id = ph.p_id 
        JOIN 
            product_type pt ON p.type_id = pt.type_id 
        WHERE 
            p.type_id = ? 
            AND ph.to_date IS NULL 
        ORDER BY 
            p.p_name
        LIMIT ?, ?
        ");
        $stmt->bind_param("iii", $type_id, $start, $limit);
        
        $stmt->execute();
        $result = $stmt->get_result();
        ?>
        
        <div class="bc-show">
            <?php
            // Check if any results are returned
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <a href="itemsDetail.php?id=<?= $row['p_id'] ?>" class="bc-show-items">
                        <div class="bc-show-items-img">
                            <img src="assets/images/product/<?= $row['image'] ?>" alt="<?= $row['p_name'] ?>">
                        </div>
                        <p><?= $row['p_name'] ?></p>
                        <h5><?= number_format($row['price'], 2) ?> ฿</h5>
                    </a>
                    <?php
                }
            } else {
                echo "ไม่พบข้อมูลสินค้าในประเภทนี้";
            }
            $stmt->close();
            $conn->close();
            ?>
        </div>
        
        <a class="btn btn-info" href="index.php">Back to index</a>
        
    </div>
</body>

</html>
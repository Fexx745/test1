<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Results</title>
    <?php include('script-css.php'); ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .product-results {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .product-item {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .product-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .product-item h3 {
            font-size: 24px;
            margin: 0 0 10px;
        }

        .product-item p {
            color: #555;
            font-size: 16px;
            margin: 5px 0;
        }

        .product-item img {
            max-width: 100%;
            border-radius: 8px;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="container">

        <?php
        include('condb.php');

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
    ");
        $stmt->bind_param("i", $type_id);

        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any results are returned
        if ($result->num_rows > 0) {
            echo "<div class='product-results'>";
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product-item'>";
                echo "<h3>" . $row["p_name"] . "</h3>";
                echo "<p>" . $row["detail"] . "</p>";
                echo "<p>จำนวนสินค้า: " . $row["amount"] . "</p>";
                echo "<p>จำนวนเข้าชม: " . $row["p_view"] . "</p>";
                echo "<p>ราคา: " . $row["price"] . "</p>";
                echo "<img src='assets/images/product/" . $row["image"] . "' alt='" . $row["p_name"] . "'>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "ไม่พบข้อมูลสินค้าในประเภทนี้";
        }

        $stmt->close();
        $conn->close();
        ?>

        <a class="btn btn-info" href="index.php">Back to index</a>
    </div>
</body>

</html>
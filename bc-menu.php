<div class="bc-menu">
    <form action="search_type_product.php" method="get">
        <?php
        $sql = "SELECT * FROM product_type";
        $result = mysqli_query($conn, $sql);
        ?>
        <div class="bc-menu-category">
        <i class='bx bx-category'></i>
            <h2>หมวดหมู่สินค้า</h2>
        </div>
        <ul>
            <?php
            mysqli_data_seek($result, 0);
            while ($row = mysqli_fetch_array($result)) {
            ?>
                <li><a href="search_type_product.php?type_id=<?= $row['type_id'] ?>"><?= $row['type_name'] ?></a></li>
            <?php
            }
            ?>
        </ul>
    </form>
</div>
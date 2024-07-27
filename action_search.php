<?php

    require_once 'config.php';

    if (isset($_POST['query'])) {
        $inputText = $_POST['query'];
        $sql = "SELECT p_name FROM product WHERE p_name LIKE :p_name";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['p_name' => '%' . $inputText . '%' ]);
        $result = $stmt->fetchAll();

        if ($result) {
            foreach($result as $row) {
                echo '<a id="searchprd" class="list-group-item list-group-item-action border-1">' . $row['p_name'] . '</a>';
            }
        } else {
            echo '<p class="list-group-item border-1">ไม่พบสินค้า..</p>';
        }
    }

?>
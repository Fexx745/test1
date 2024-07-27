<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include('script-css.php'); ?>
</head>
<body>
    
<div class="container">
<form action="insert_line_notfiy.php" method="post" enctype="multipart/form-data">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br>
    <label for="pname">Product Name:</label>
    <input type="text" name="pname" id="pname" required><br>
    <input type="submit" name="submit" value="Submit">
</form>
</div>

</body>
</html>

<?php include('script-js.php'); ?>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
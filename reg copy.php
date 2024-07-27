<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- #bootrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"> </script>
</head>

<body>
    <div class="container">
        <h1 class="mt-5 alert alert-info">Form Register</h1>
        <?php
        session_start();
        if (!empty($_SESSION["Error"])) {
            echo "<h3 id='errorMessage' class='alert alert-danger'>" . $_SESSION["Error"] . "</h3>";
            echo "<script>
                setTimeout(function() {
                    var errorMessage = document.getElementById('errorMessage');
                    if (errorMessage) {
                        errorMessage.style.display = 'none';
                    }
                }, 5000); // นับเวลา 5 วินาทีแล้วซ่อนข้อความ
              </script>";
              unset($_SESSION["Error"]); // ลบค่า $_SESSION["Error"] ออกจาก session
        }
        ?>
        <form class="mt-3" method="post" action="reg_insert.php">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" class="form-control" name="fname" required>
            </div>
            <div class="mb-3">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="lname" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="number" class="form-control" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label for="psw" class="form-label">Password</label>
                <input type="password" class="form-control" name="psw" required>
            </div>
            <div class="alert alert-success">
                <a href="login.php">Login ?</a>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
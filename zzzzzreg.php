<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- #bootrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"> </script>
    <script src="assets/dist/sweetalert2.all.min.js"></script>

    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .wrapper {
            border-top: 3px solid blue;

            & a {
                text-decoration: none;
                color: green;
            }
            & a:hover {
                opacity: 0.5;
                transition: .3s;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-4 bg-white m-auto rounded-top wrapper">
                <h2 class="text-center pt-3">Signup Now</h2>
                <p class="text-center text-muted lead mb-3">
                    <?php
                    session_start();
                    if (!empty($_SESSION["Error"])) {
                        echo "<h5 id='errorMessage' class='alert alert-danger'>" . $_SESSION["Error"] . "</h5>";
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
                </p>

                <form action="reg_insert.php" method="POST">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-user'></i></span>
                        <select class="form-control" name="prefix">
                            <option value="" disabled selected hidden class="text-muted">- Prefix -</option>
                            <?php
                            $options = array('นาย', 'นาง', 'นางสาว');
                            foreach ($options as $option) {
                                echo "<option value='$option'>$option</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-user'></i></span>
                        <input type="text" class="form-control" name="fname" placeholder="First Name" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-user'></i></span>
                        <input type="text" class="form-control" name="lname" placeholder="Last Name" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-envelope'></i></span>
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-location-plus'></i></span>
                        <input type="text" class="form-control" name="address" placeholder="Address" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-phone'></i></span>
                        <input type="number" class="form-control" name="phone" placeholder="Phone" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-user-circle'></i></span>
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-lock'></i></span>
                        <input type="password" class="form-control" name="psw" placeholder="Password" required>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-lock'></i></span>
                        <input type="password" class="form-control" name="conpsw" placeholder="Confirm Password" required>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-primary">Signup Now</button>
                        <p class="text-center mt-2">คุณมีบัญชีอยู่แล้ว ? <a href="login.php">เข้าสู่ระบบ</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>


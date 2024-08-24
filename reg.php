<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิกเพื่อเริ่มต้นช้อปออนไลน์ได้ที่นี่ | RMUTI</title>

    <!-- #bootrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/js/bootstrap.bundle.min.js"> </script>
    <script src="assets/dist/sweetalert2.all.min.js"></script>

    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .wrapper {
            border-top: 3px solid #00C300;

            & a {
                text-decoration: none;
                color: #00C300;
            }

            & a:hover {
                opacity: 0.6;
                transition: .3s;
                color: #00C300;
            }
        }

    </style>
</head>

<body>

    <?php include('nav-reg.php'); ?>

    <div class="container-fluid p-5" style="background: #fff;">
        <div class="row mt-5">
            <div class="col-lg-4 bg-white m-auto rounded-top wrapper">
                <h2 class="text-center pt-3">สมัครสมาชิก</h2>
                <p class="text-center text-muted lead mb-3">
                    <?php
                    if (!empty($_SESSION["Error"])) {
                        echo "<h5 id='errorMessage' class='alert alert-danger'>" . $_SESSION["Error"] . "</h5>";
                        echo "<script>
                        setTimeout(function() {
                            var errorMessage = document.getElementById('errorMessage');
                            if (errorMessage) {
                                errorMessage.style.display = 'none';
                            }
                        }, 5000); // Hide the error message after 5 seconds
                    </script>";
                        unset($_SESSION["Error"]); // Clear the error session variable
                    }
                    ?>
                </p>

                <form action="reg_insert.php" method="POST">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-id-card'></i></span>
                        <select class="form-control" name="prefix">
                            <option value="" disabled selected hidden class="text-muted">** คำนำหน้าชื่อ **</option>
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
                        <input type="text" id="input_field" class="form-control" name="fname" placeholder="ชื่อ" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-user'></i></span>
                        <input type="text" class="form-control" name="lname" placeholder="นามสกุล" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-envelope'></i></span>
                        <input type="email" class="form-control" name="email" placeholder="อีเมลล์" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-map'></i></span>
                        <textarea class="form-control" name="address" placeholder="ที่อยู่ ...." required style="resize: none;"></textarea>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-phone'></i></span>
                        <input type="text" class="form-control" name="phone" placeholder="เบอร์โทร์ศัพท์" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-user-circle'></i></span>
                        <input type="text" class="form-control" name="username" placeholder="ชื่อผู้ใช้" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-lock'></i></span>
                        <input type="password" class="form-control" name="psw" placeholder="รหัสผ่าน" required>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-lock'></i></span>
                        <input type="password" class="form-control" name="confirm_psw" placeholder="ยืนยันรหัสผ่าน" required>
                    </div>
                    <div class="d-grid">
                        <button class="btn" style="background: #00C300; color: #fff;">ยืนยันการสมัคร</button>
                        <p class="text-center mt-2"><span style="color: #6c757d;">หากมีบัญชีผู้ใช้แล้ว คุณสามารถ</span> <a href="login.php">เข้าสู่ระบบ</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

</body>

</html>

<script>
    $(document).ready(function() {
        // Get the input field
        var inputField = $('#input_field');

        // Add an event listener to the input field
        inputField.on('input', function() {
            // Get the input value
            var inputValue = $(this).val();

            // Send an AJAX request to the PHP script
            $.ajax({
                type: 'POST',
                url: 'check_duplicate.php',
                data: {
                    input_value: inputValue
                },
                success: function(response) {
                    // Check the response
                    if (response == "duplicate") {
                        // Change the input border color to red
                        inputField.css('border-color', 'red');
                    } else {
                        // Change the input border color to green
                        inputField.css('border-color', 'green');
                    }
                }
            });
        });
    });
</script>

<?php
$alerts = [
    'Username_Already' => 'ชื่อผู้ใช้ถูกใช้ไปแล้ว!',
    'Email_Already' => 'อีเมลล์ถูกใช้ไปแล้ว!',
    'Phone_Already' => 'เบอร์โทรศัพท์ถูกใช้ไปแล้ว!',
    'PswDo_notMatch' => 'รหัสผ่านไม่ตรงกัน!',
];

foreach ($alerts as $key => $message) {
    if (isset($_SESSION[$key])) {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: '$message',
                text: 'กรุณาลองใหม่อีกครั้ง',
                showConfirmButton: false,
                timer: 1800
            });
        </script>";
        unset($_SESSION[$key]);
    }
}
?>
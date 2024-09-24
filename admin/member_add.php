<?php
session_start();
include('condb.php');

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
} elseif ($_SESSION['status'] !== '1') {
    header('Location: ../login.php');
    exit();
}

$sql = "SELECT * FROM tb_member";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <!-- Font -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="assets/dist/sweetalert2.all.min.js"></script>
</head>

<body class="sb-nav-fixed">

    <?php include('menu.php') ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 mt-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        เพิ่มบัญชีผู้ใช้งาน
                    </div>
                    <div class="card-body">
                        <div class="alert" style="background: linear-gradient(195deg, #f8f9fa 0%, #f8f9fa 100%); color: #333; border: none; outline: none;">
                            <div class="d-flex">
                                <img src="../assets/images/other/customer.png" alt="Line Notify Logo" style="height: 60px; margin-right: 5px;">
                                <h3 style="font-weight: 1000; margin-top: 10px;">
                                    สมัครสมาชิก
                                </h3>
                            </div>
                        </div>
                        <h1 class="text-center text-muted lead mb-3">
                            <?php
                            if (session_status() == PHP_SESSION_NONE) {
                                session_start();
                            }

                            if (!empty($_SESSION["Error"])) {
                                echo "<h4 id='errorMessage' class='alert alert-danger'>" . $_SESSION["Error"] . "</h4>";
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

                        </h1>
                        <form method="POST" action="member_insert.php">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-user'></i></span>
                                <select class="form-control" name="prefix">
                                    <option value="" disabled selected hidden class="text-muted">** คำนำหน้าชื่อ **
                                    </option>
                                    <?php
                                    $options = array('นาย', 'นาง', 'นางสาว');
                                    foreach ($options as $option) {
                                        echo "<option value='$option'>$option</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-user-account'></i></span>
                                <input type="text" class="form-control" name="fname" placeholder="ชื่อ" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-user-account'></i></span>
                                <input type="text" class="form-control" name="lname" placeholder="นามสกุล" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-phone-call'></i></span>
                                <input type="text" class="form-control" name="telephone" placeholder="เบอร์โทรศัพท์" required>
                            </div>
                            <div id="telephoneMessage" class="text-danger my-2" style="display: none;"></div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-location-plus'></i></span>
                                <input type="text" class="form-control" name="address" placeholder="ที่อยู่" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-envelope'></i></span>
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                            <div id="emailMessage" class="text-danger my-2" style="display: none;"></div>

                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-user-circle'></i></span>
                                <input type="text" class="form-control" name="username" placeholder="Username" required>
                            </div>
                            <div id="usernameMessage" class="text-danger my-2" style="display: none;"></div>

                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-lock'></i></span>
                                <input type="password" class="form-control" name="psw" placeholder="Password" required>
                            </div>
                            <div id="passwordMessage" class="text-danger mb-2" style="display: none;"></div>

                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bxs-lock'></i></span>
                                <input type="password" class="form-control" name="conpsw" placeholder="Confirm Password" required>
                            </div>
                            <div id="confirmPasswordMessage" class="text-danger mb-2" style="display: none;"></div>


                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class='bx bx-stats'></i></span>
                                <select class="form-control" name="status">
                                    <option value="" disabled selected hidden class="text-muted">** สถานะ **</option>
                                    <?php
                                    $options = array(
                                        "ลูกค้า" => 0,
                                        "ผู้ดูแลระบบ" => 1,
                                    );

                                    foreach ($options as $label => $value) {
                                        echo "<option value='$value'>$label</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mt-4" class="mb-2">
                                <a href="member_List.php" class="btn btn-dark">ย้อนกลับ</a>
                                <button class="btn btn-danger" type="submit">ยืนยันการสมัคร</button>
                            </div>
                        </form>
                    </div> <!-- card-body -->
                </div>
            </div>
        </main>

        <?php include('footer.php') ?>
    </div>
    </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>

<?php
if (isset($_SESSION['addaccount'])) {
?>
    <script>
        Swal.fire({
            // position: "top-center",
            icon: "success",
            title: "เพิ่มผู้ใช้สำเร็จ!",
            footer: "<span style='color: #30b566'>เพิ่มผู้ใช้สำเร็จ</span>",
            showConfirmButton: false,
            timer: 1500
        }).then(function() {
            window.location.href = 'show_account.php';
        });
    </script>

<?php
    unset($_SESSION['addaccount']);
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const usernameInput = document.querySelector('input[name="username"]');
        const emailInput = document.querySelector('input[name="email"]');
        const telephoneInput = document.querySelector('input[name="telephone"]');

        // ฟังก์ชันตรวจสอบชื่อผู้ใช้
        usernameInput.addEventListener('input', function() {
            const username = usernameInput.value;
            const usernameMessage = document.getElementById('usernameMessage');

            if (username) {
                fetch('member_check.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'username=' + encodeURIComponent(username),
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === "exists") {
                            usernameInput.style.border = "1px solid red"; // ชื่อผู้ใช้มีอยู่แล้ว
                            usernameMessage.textContent = "ชื่อผู้ใช้นี้มีอยู่แล้ว กรุณาใช้ชื่อผู้ใช้ใหม่";
                            usernameMessage.style.display = 'block';
                        } else {
                            usernameInput.style.border = "1px solid green"; // ชื่อผู้ใช้ว่าง
                            usernameMessage.textContent = ""; // ลบข้อความเตือน
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                usernameInput.style.border = ""; // รีเซ็ตถ้าไม่มีข้อมูล
                usernameMessage.textContent = ""; // ลบข้อความเตือน
            }
        });

        // ฟังก์ชันตรวจสอบอีเมล
        emailInput.addEventListener('input', function() {
            const email = emailInput.value;
            const emailMessage = document.getElementById('emailMessage');
            const emailPattern = /^[a-zA-Z0-9._%+-]+@(gmail\.com|hotmail\.com|[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$/;

            if (email) {
                if (!emailPattern.test(email)) {
                    emailInput.style.border = "1px solid red"; // รูปแบบอีเมลไม่ถูกต้อง
                    emailMessage.textContent = "กรุณากรอกอีเมลในรูปแบบที่ถูกต้อง เช่น example@gmail.com";
                    emailMessage.style.display = 'block';
                    return;
                }

                fetch('member_check.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'email=' + encodeURIComponent(email),
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === "exists") {
                            emailInput.style.border = "1px solid red"; // อีเมลมีอยู่แล้ว
                            emailMessage.textContent = "อีเมลนี้มีอยู่แล้ว กรุณาใช้ใหม่";
                            emailMessage.style.display = 'block';
                        } else {
                            emailInput.style.border = "1px solid green"; // อีเมลว่าง
                            emailMessage.textContent = ""; // ลบข้อความเตือน
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                emailInput.style.border = ""; // รีเซ็ตถ้าไม่มีข้อมูล
                emailMessage.textContent = ""; // ลบข้อความเตือน
            }
        });

        // ฟังก์ชันตรวจสอบหมายเลขโทรศัพท์
        telephoneInput.addEventListener('input', function() {
            const telephone = telephoneInput.value;
            const telephoneMessage = document.getElementById('telephoneMessage');
            const telephonePattern = /^\d{10}$/;

            if (telephone) {
                if (!telephonePattern.test(telephone)) {
                    telephoneInput.style.border = "1px solid red"; // รูปแบบหมายเลขโทรศัพท์ไม่ถูกต้อง
                    telephoneMessage.textContent = "หมายเลขโทรศัพท์ต้องเป็นตัวเลขและมีความยาว 10 ตัว";
                    telephoneMessage.style.display = 'block';
                    return; // ออกจากฟังก์ชันหากรูปแบบไม่ถูกต้อง
                }

                fetch('member_check.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'telephone=' + encodeURIComponent(telephone),
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === "telephone_exists") {
                            telephoneInput.style.border = "1px solid red"; // หมายเลขโทรศัพท์มีอยู่แล้ว
                            telephoneMessage.textContent = "หมายเลขโทรศัพท์นี้มีอยู่แล้ว กรุณาใช้หมายเลขใหม่";
                            telephoneMessage.style.display = 'block';
                        } else {
                            telephoneInput.style.border = "1px solid green"; // หมายเลขโทรศัพท์ว่าง
                            telephoneMessage.textContent = ""; // ลบข้อความเตือน
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                telephoneInput.style.border = ""; // รีเซ็ตถ้าไม่มีข้อมูล
                telephoneMessage.textContent = ""; // ลบข้อความเตือน
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        const usernameInput = document.querySelector('input[name="username"]');
        const passwordInput = document.querySelector('input[name="psw"]');
        const confirmPasswordInput = document.querySelector('input[name="conpsw"]');
        const passwordMessage = document.getElementById('passwordMessage');
        const confirmPasswordMessage = document.getElementById('confirmPasswordMessage');

        // Username validation (unchanged)
        usernameInput.addEventListener('input', function() {
            const username = usernameInput.value;

            if (username) {
                fetch('check_username.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'username=' + encodeURIComponent(username),
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === "exists") {
                            usernameInput.style.border = "1px solid red";
                        } else {
                            usernameInput.style.border = "1px solid green";
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                usernameInput.style.border = "";
            }
        });

        // Password validation
        function validatePassword() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            const isValidPassword = /^[a-zA-Z0-9]{6,}$/.test(password); // At least 6 characters, only letters and numbers
            const isMatching = password === confirmPassword;

            // Reset messages
            passwordMessage.style.display = 'none';
            confirmPasswordMessage.style.display = 'none';
            passwordInput.style.border = ""; // Reset border
            confirmPasswordInput.style.border = ""; // Reset border

            if (!isValidPassword) {
                passwordInput.style.border = "1px solid red"; // รหัสผ่านไม่ถูกต้อง
                passwordMessage.textContent = "รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษรและต้องประกอบด้วยตัวอักษรภาษาอังกฤษและตัวเลขเท่านั้น.";
                passwordMessage.style.display = 'block';
            } else {
                passwordInput.style.border = "1px solid green"; // รหัสผ่านถูกต้อง
            }

            if (!isMatching) {
                confirmPasswordInput.style.border = "1px solid red"; // รหัสผ่านไม่ตรงกัน
                confirmPasswordMessage.textContent = "รหัสผ่านไม่ตรงกัน กรุณาลองอีกครั้ง.";
                confirmPasswordMessage.style.display = 'block';
            } else {
                confirmPasswordInput.style.border = "1px solid green"; // รหัสผ่านตรงกัน
            }

        }

        passwordInput.addEventListener('input', validatePassword);
        confirmPasswordInput.addEventListener('input', validatePassword);
    });
</script>
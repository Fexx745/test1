<?php
// เชื่อมต่อกับฐานข้อมูล (หรือดำเนินการตามความจำเป็น)
session_start();
include('condb.php');

// ตรวจสอบว่ามีพารามิเตอร์ email และ token ใน URL หรือไม่
if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    // ตรวจสอบว่าโทเค็นที่ได้รับเหมาะสมหรือไม่
    $query = "SELECT * FROM tb_member WHERE email='$email' AND reset_token='$token'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        // แสดงแบบฟอร์มสำหรับตั้งค่ารหัสผ่านใหม่
?>
        <!-- #bootrap -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="bootstrap/js/bootstrap.bundle.min.js"> </script>
        <script src="assets/dist/sweetalert2.all.min.js"></script>
        <!-- boxicons -->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <?php include('nav_reg.php'); ?>
        <div class="container-fluid p-5" style="background: #fff; margin-bottom: 450px;">
            <div class="row mt-5">
                <div class="col-lg-4 bg-white m-auto rounded-top wrapper-forgot">
                    <h2 class="text-center pt-3">สร้างรหัสผ่านใหม่</h2>
                    <form action="forgot_reset_process.php" method="POST" onsubmit="return validatePasswords()">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                        <p id="password-length-msg" style="color:red; display:none;">รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร</p>
                        <p id="password-char-msg" style="color:red; display:none;">รหัสผ่านจะต้องมีทั้งตัวอักษรและตัวเลข</p>
                        <div class="input-group mb-3 position-relative">
                            <span class="input-group-text"><i class='bx bxs-lock-alt'></i></span>
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="รหัสผ่านใหม่" required>
                            <i id="password-check-icon" class='bx bxs-check-circle' style="display: none; position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: green;"></i>
                        </div>




                        <p id="password-match-msg" style="color:red; display:none;">รหัสผ่านไม่ตรงกัน</p>

                        <div class="input-group mb-3 position-relative">
                            <span class="input-group-text"><i class='bx bxs-lock-alt'></i></span>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="ยืนยันรหัสผ่านใหม่" required>
                            <i id="confirm-check-icon" class='bx bxs-check-circle' style="display: none; position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: green;"></i>
                        </div>

                        <div class="text-end my-2">
                            <span id="togglePassword" style="cursor: pointer;">
                                <span class="material-symbols-outlined" id="eye-icon" style="font-size: 20px; font-weight: 1000;">visibility_off</span>
                            </span>
                        </div>
                        <div class="d-grid">
                            <button class="btn-forgot" type="submit" name="submit">ยืนยัน</button>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class="mt-2"><span style="color: #6c757d;">คุณจำรหัสผ่านได้แล้ว ใช่หรือไม่?</span> <a href="login.php" style="text-decoration: none;">เข้าสู่ระบบ</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include('footer.php'); ?>

        <!-- JavaScript for real-time password validation -->
        <script>
            // ตรวจสอบรหัสผ่านหลัก
            document.getElementById("new_password").addEventListener("input", function() {
                var password = document.getElementById("new_password").value;
                var lengthMessage = document.getElementById("password-length-msg");
                var charMessage = document.getElementById("password-char-msg");
                var passwordCheckIcon = document.getElementById("password-check-icon");

                // ตรวจสอบความยาวรหัสผ่าน
                if (password.length < 6) {
                    lengthMessage.style.display = "block";
                    document.getElementById("new_password").style.border = "1px solid red";
                    passwordCheckIcon.style.display = "none";
                } else {
                    lengthMessage.style.display = "none";
                }

                // ตรวจสอบว่ามีทั้งตัวเลขและตัวอักษร
                var hasLetters = /[a-zA-Z]/.test(password);
                var hasNumbers = /[0-9]/.test(password);

                if (!hasLetters || !hasNumbers) {
                    charMessage.style.display = "block";
                    document.getElementById("new_password").style.border = "1px solid red";
                    passwordCheckIcon.style.display = "none";
                } else {
                    charMessage.style.display = "none";
                    document.getElementById("new_password").style.border = "1px solid green";
                    passwordCheckIcon.style.display = "inline"; // แสดงไอคอนติ๊กถูกเมื่อถูกต้อง
                }
            });


            // ตรวจสอบรหัสผ่านยืนยัน
            document.getElementById("confirm_password").addEventListener("input", function() {
                var newPassword = document.getElementById("new_password").value;
                var confirmPassword = document.getElementById("confirm_password").value;
                var matchMessage = document.getElementById("password-match-msg");
                var confirmCheckIcon = document.getElementById("confirm-check-icon");

                if (newPassword !== confirmPassword || confirmPassword.length === 0) {
                    matchMessage.style.display = "block";
                    document.getElementById("confirm_password").style.border = "1px solid red";
                    confirmCheckIcon.style.display = "none";
                } else {
                    matchMessage.style.display = "none";
                    document.getElementById("confirm_password").style.border = "1px solid green";
                    confirmCheckIcon.style.display = "inline"; // แสดงไอคอนติ๊กถูกเมื่อรหัสผ่านตรงกัน
                }
            });

            // ฟังก์ชันสำหรับสลับการแสดงรหัสผ่าน
            // ฟังก์ชันสำหรับสลับการแสดงรหัสผ่าน
            function togglePasswordVisibility() {
                var newPasswordInput = document.getElementById("new_password");
                var confirmPasswordInput = document.getElementById("confirm_password");
                var icon = document.getElementById("eye-icon");

                if (newPasswordInput.type === "password") {
                    newPasswordInput.type = "text";
                    confirmPasswordInput.type = "text";
                    icon.textContent = "visibility"; // เปลี่ยนไอคอนเป็น 'visibility'
                } else {
                    newPasswordInput.type = "password";
                    confirmPasswordInput.type = "password";
                    icon.textContent = "visibility_off"; // เปลี่ยนไอคอนเป็น 'visibility_off'
                }
            }

            // เพิ่มอีเวนต์คลิกสำหรับการสลับรหัสผ่าน
            document.getElementById("togglePassword").addEventListener("click", togglePasswordVisibility);
        </script>




<?php
    } else {
        echo "ล้มเหลวในการรีเซ็ตรหัสผ่าน: โทเค็นไม่ถูกต้อง";
    }
} else {
    echo "ล้มเหลวในการรีเซ็ตรหัสผ่าน: ข้อมูลไม่ครบถ้วน";
    // $_SESSION['reset_psw'] = "ล้มเหลวในการรีเซ็ตรหัสผ่าน: ข้อมูลไม่ครบถ้วน";
    // header('Location: reset_password.php');
    // exit();
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
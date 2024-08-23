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

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

            * {
                font-family: 'Poppins', sans-serif;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            .wrapper {
                border-top: 3px solid #ffc107;

                & a {
                    text-decoration: none;
                    color: green;
                }

                & a:hover {
                    opacity: 0.5;
                    transition: .3s;
                }
            }

            button:hover {
                opacity: 0.7;
                transition: .3s;
            }
        </style>
        <?php include('nav-reg.php'); ?>
        <div class="container-fluid p-5" style="background: #fff; margin-bottom: 450px;">
            <div class="row mt-5">
                <div class="col-lg-4 bg-white m-auto rounded-top wrapper">
                    <h2 class="text-center pt-3">New password</h2>
                    <form action="process_reset_password.php" method="POST">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class='bx bxs-lock-alt'></i></span>
                            <input type="password" class="form-control" id="new_password" name="new_password"
                                placeholder="รหัสผ่านใหม่" required>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-warning" type="submit" name="submit"">ยืนยัน</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include('footer.php'); ?>

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
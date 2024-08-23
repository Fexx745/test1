    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Navbar</title>
        <link rel="stylesheet" href="./assets/css/nav-reg.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    </head>

    <body>
        <nav>
            <a href="reg.php">
                <div class="nav-icon">
                    <i class="material-symbols-outlined">shopping_bag</i>
                    <h3>RMUTI <span>สมัครสมาชิก</span></h3>
                </div>
                <div class="nav-help">
                    <a href="#">ต้องการความช่วยเหลือ?</a>
                </div>
            </a>
        </nav>





        <!-- Add your scripts at the bottom -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
        <script src="search.js"></script>
        <?php
        if (isset($_SESSION['reg_success'])) {
        ?>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "ลงทะเบียนสำเร็จ!",
                    // text: "คุณได้เข้าสู่ระบบเรียบร้อยแล้ว",
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
        <?php
            unset($_SESSION['reg_success']);
        }
        ?>
        <?php
        if (isset($_SESSION['success'])) {
        ?>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "เข้าสู่ระบบสำเร็จ!",
                    // text: "คุณได้เข้าสู่ระบบเรียบร้อยแล้ว",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = 'index.php';
                });
            </script>
        <?php
            unset($_SESSION['success']);
        }
        ?>

        <?php
        if (isset($_SESSION['success-admin'])) {
        ?>
            <script>
                Swal.fire({
                    // position: "top-center",
                    icon: "success",
                    title: "เข้าสู่ระบบสำเร็จ!",
                    // text: "",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = 'admin/index.php';
                });
            </script>

        <?php
            unset($_SESSION['success-admin']);
        }
        ?>

        <?php
        if (isset($_SESSION['error'])) {
        ?>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "ชื่อผู้ใช้หรือรหัสผ่านผิด!",
                    text: "กรุณาลองใหม่อีกครั้ง",
                    showConfirmButton: false,
                    timer: 1800
                });
            </script>
        <?php
            unset($_SESSION['error']);
        }
        ?>
        <?php
        if (isset($_SESSION['Username_Already'])) {
        ?>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "ชื่อผู้ใช้ถูกใช้ไปงานแล้ว!",
                    text: "กรุณาลองใหม่อีกครั้ง",
                    showConfirmButton: false,
                    timer: 1800
                });
            </script>
        <?php
            unset($_SESSION['Username_Already']);
        }
        ?>
        <?php
        if (isset($_SESSION['Email_Already'])) {
        ?>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "อีเมลล์ถูกใช้ไปงานแล้ว!",
                    text: "กรุณาลองใหม่อีกครั้ง",
                    showConfirmButton: false,
                    timer: 1800
                });
            </script>
        <?php
            unset($_SESSION['Email_Already']);
        }
        ?>
        <?php
        if (isset($_SESSION['Phone_Already'])) {
        ?>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "เบอร์โทรศัพท์ถูกใช้ไปงานแล้ว!",
                    text: "กรุณาลองใหม่อีกครั้ง",
                    showConfirmButton: false,
                    timer: 1800
                });
            </script>
        <?php
            unset($_SESSION['Phone_Already']);
        }
        ?>
        <?php
        if (isset($_SESSION['PswDo_notMatch'])) {
        ?>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "รหัสผ่านไม่ตรงกัน!",
                    text: "กรุณาลองใหม่อีกครั้ง",
                    showConfirmButton: false,
                    timer: 1800
                });
            </script>
        <?php
            unset($_SESSION['PswDo_notMatch']);
        }
        ?>

        <?php
        if (isset($_SESSION['psw_suc'])) {
        ?>
            <script>
                Swal.fire({
                    icon: "success",
                    title: "เปลี่ยนรหัสสำเร็จ!",
                    text: "กรุณาเข้าสู่ระบบใหม่อีกครั้ง",
                    showConfirmButton: false,
                    timer: 1500
                })
            </script>
        <?php
            unset($_SESSION['psw_suc']);
        }
        ?>


    </body>

    </html>
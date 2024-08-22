<?php session_start(); ?>

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

        .container {
            margin: 200px auto;
        }

        .wrapper {
            border-top: 3px solid red;

            & a {
                text-decoration: none;
                color: #0d6efd;
            }

            & a:hover {
                opacity: 0.5;
                transition: .3s;
            }
        }

        #forgotpsw {
            color: #0d6efd;
        }

        #forgotpsw:hover {
            opacity: 0.5;
            transition: .3s;
        }
    </style>
</head>

<body>
    <?php include('nav-reg.php'); ?>
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-4 bg-white m-auto rounded-top wrapper">
                <h2 class="text-center pt-3">Forgot Password</h2>
                <form action="forgot_psw.php" method="POST">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-envelope'></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="กรุณากรอกอีเมลล์" required>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-danger" type="submit" name="submit" onclick="confirmSendEmail()">ยืนยัน</button>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="mt-2">มีบัญชีแล้วไช่ไหม? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">เข้าสู่ระบบ</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<?php
if (isset($_SESSION['not-email'])) {
?>
    <script>
        Swal.fire({
            icon: "warning",
            title: "ไม่พบอีเมลล์ในระบบ!",
            text: "Couldn't find email in the system.",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php
    unset($_SESSION['not-email']);
}
?>

<?php
if (isset($_SESSION['send-email'])) {
?>
    <script>
        Swal.fire({
            icon: "success",
            title: "ส่งคำขอเปลี่ยนรหัสสำเร็จ!",
            text: "Password change request sent.",
            // footer: '<span style="color: blue;">กรุณาตรวจสอบ Email ของคุณ</span>',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
<?php
    unset($_SESSION['send-email']);
}
?>


<script>
    function confirmSendEmail() {
        Swal.fire({
            title: "Confirm sending request to change password.",
            text: "ยืนยันส่งคำขอเปลี่ยนรหัสผ่าน!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#198754",
            cancelButtonColor: "#d33",
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "forgot_psw.php",
                    data: {
                        email: $("#email").val() // รับค่าอีเมลล์จากฟอร์ม
                    },
                    success: function(response) {
                        // ดำเนินการหลังจากสำเร็จ
                        window.location.href = `forgot_psw.php`;
                    },
                    error: function(xhr, status, error) {
                        // ดำเนินการหลังจากเกิดข้อผิดพลาด
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    }
</script>
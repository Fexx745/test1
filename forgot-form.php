<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <?php include('script-css.php'); ?>
</head>

<body>
    <?php include('nav-reg.php'); ?>
    <div class="container-fluid p-5" style="background: #fff; margin-bottom: 400px;">
        <div class="row mt-5">
            <div class="col-lg-4 bg-white m-auto rounded-top wrapper">
                <h2 class="text-center pt-3">ลืมรหัสผ่าน</h2>
                <form action="forgot_psw.php" method="POST">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-envelope'></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="กรุณากรอกอีเมลล์ ที่คุณสมัครไว้" required>
                    </div>
                    <div class="d-grid">
                        <button class="btn-submit">ยืนยัน</button>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="mt-2"><span style="color: #6c757d;">มีบัญชีแล้วไช่ไหม?</span> <a href="login.php">เข้าสู่ระบบ</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>

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
            title: "คำขอเปลี่ยนรหัสผ่านสำเร็จ!",
            text: "เราได้ส่งคำขอเปลี่ยนรหัสผ่านไปยังอีเมลของคุณแล้ว",
            footer: '<span style="color: #198754;">กรุณาตรวจสอบอีเมลของคุณ</span>',
            showConfirmButton: false,
            timer: 3500
        });
    </script>
<?php
    unset($_SESSION['send-email']);
}
?>


<!-- <script>
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
</script> -->
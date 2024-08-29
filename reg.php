<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิกเพื่อเริ่มต้นช้อปออนไลน์ได้ที่นี่ | RMUTI</title>

    <?php include('script-css.php'); ?>
</head>

<body>

    <?php include('nav_reg.php'); ?>

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
                        <select class="form-control" name="prefix" required>
                            <option value="" disabled selected hidden class="text-muted">** คำนำหน้าชื่อ **</option>
                            <?php
                            $options = array('นาย', 'นาง', 'นางสาว');
                            foreach ($options as $option) {
                                $selected = (isset($_SESSION['form_data']['prefix']) && $_SESSION['form_data']['prefix'] == $option) ? 'selected' : '';
                                echo "<option value='$option' $selected>$option</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- First Name Field -->
                    <label id="fname_error_label" class="text-danger" style="display:none;">
                        <i class="fas fa-exclamation-circle"></i> กรุณาใส่ชื่อด้วยตัวอักษรภาษาไทยหรืออังกฤษเท่านั้น
                    </label>
                    <div class="input-group mb-3 position-relative">
                        <span class="input-group-text"><i class='bx bx-user'></i></span>
                        <input type="text" id="fname_field" class="form-control" name="fname" placeholder="ชื่อ" value="<?php echo htmlspecialchars($_SESSION['form_data']['fname'] ?? ''); ?>" required>
                        <i id="fname_success_icon" class="fas fa-check-circle text-success position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); display: none;"></i>
                    </div>

                    <!-- Last Name Field -->
                    <label id="lname_error_label" class="text-danger" style="display:none;">
                        <i class="fas fa-exclamation-circle"></i> กรุณาใส่นามสกุลด้วยตัวอักษรภาษาไทยหรืออังกฤษเท่านั้น
                    </label>
                    <div class="input-group mb-3 position-relative">
                        <span class="input-group-text"><i class='bx bx-user'></i></span>
                        <input type="text" id="lname_field" class="form-control" name="lname" placeholder="นามสกุล" value="<?php echo htmlspecialchars($_SESSION['form_data']['lname'] ?? ''); ?>" required>
                        <i id="lname_success_icon" class="fas fa-check-circle text-success position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); display: none;"></i>
                    </div>


                    <label id="username_error_label" class="text-danger" style="display:none;"><i class="fas fa-exclamation-circle"></i> ชื่อผู้ใช้นี้ถูกใช้แล้ว</label>
                    <div class="input-group mb-3 position-relative">
                        <span class="input-group-text"><i class='bx bx-user-circle'></i></span>
                        <input type="text" id="username_field" class="form-control <?php echo isset($_SESSION['Error_field']) && $_SESSION['Error_field'] == 'username' ? 'is-invalid' : ''; ?>" name="username" placeholder="ชื่อผู้ใช้" value="<?php echo htmlspecialchars($_SESSION['form_data']['username'] ?? ''); ?>" required>
                        <i id="username_success_icon" class="fas fa-check-circle text-success position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); display: none;"></i>
                    </div>

                    <label id="email_error_label" class="text-danger" style="display:none;"><i class="fas fa-exclamation-circle"></i> อีเมลล์นี้ถูกใช้แล้ว</label>
                    <div class="input-group mb-3 position-relative">
                        <span class="input-group-text"><i class='bx bx-envelope'></i></span>
                        <input type="email" id="email_field" class="form-control <?php echo isset($_SESSION['Error_field']) && $_SESSION['Error_field'] == 'email' ? 'is-invalid' : ''; ?>" name="email" placeholder="อีเมลล์" value="<?php echo htmlspecialchars($_SESSION['form_data']['email'] ?? ''); ?>" required>
                        <i id="email_success_icon" class="fas fa-check-circle text-success position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); display: none;"></i>
                    </div>

                    <label id="phone_error_label" class="text-danger" style="display:none;">
                        <i class="fas fa-exclamation-circle"></i>
                        กรุณาใส่เบอร์โทรศัพท์เป็นตัวเลขเท่านั้น
                    </label>
                    <div class="input-group mb-3 position-relative">
                        <span class="input-group-text"><i class='bx bx-phone'></i></span>
                        <input type="text" id="phone_field" class="form-control <?php echo isset($_SESSION['Error_field']) && $_SESSION['Error_field'] == 'phone' ? 'is-invalid' : ''; ?>" name="phone" placeholder="เบอร์โทรศัพท์" value="<?php echo htmlspecialchars($_SESSION['form_data']['phone'] ?? ''); ?>" required>
                        <i id="phone_success_icon" class="fas fa-check-circle text-success position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); display: none;"></i>
                    </div>



                    <div class="input-group mb-3 position-relative">
                        <span class="input-group-text"><i class='bx bx-lock'></i></span>
                        <input type="password" id="psw_field" class="form-control <?php echo isset($_SESSION['Error_field']) && $_SESSION['Error_field'] == 'psw' ? 'is-invalid' : ''; ?>" name="psw" placeholder="รหัสผ่าน" required>
                        <i id="psw_success_icon" class="fas fa-check-circle text-success position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); display: none;"></i>
                    </div>


                    <div class="input-group mb-3 position-relative">
                        <span class="input-group-text"><i class='bx bx-lock'></i></span>
                        <input type="password" id="confirm_psw_field" class="form-control <?php echo isset($_SESSION['Error_field']) && $_SESSION['Error_field'] == 'psw' ? 'is-invalid' : ''; ?>" name="confirm_psw" placeholder="ยืนยันรหัสผ่าน" required>
                        <i id="confirm_psw_success_icon" class="fas fa-check-circle text-success position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); display: none;"></i>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class='bx bx-map'></i></span>
                        <textarea class="form-control" name="address" placeholder="ที่อยู่ ...." style="resize: none;" required><?php echo htmlspecialchars($_SESSION['form_data']['address'] ?? ''); ?></textarea>
                    </div>
                    <div class="d-grid">
                        <button class="btn-submit">ยืนยันการสมัคร</button>
                        <button onclick="window.location.href='login.php'" class="btn-login">เข้าสู่ระบบ</button>
                        <p class="text-center mt-2"><span style="color: #6c757d;">หากมีบัญชีผู้ใช้แล้ว คุณสามารถเข้าสู่ระบบได้เลย</span>
                        </p>
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
        function validateField(inputField, successIcon, errorLabel, inputValue, inputName) {
            if (inputValue === '') {
                inputField.css('border-color', '');
                errorLabel.hide();
                successIcon.hide();
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'reg_check_duplicate.php',
                    data: {
                        input_value: inputValue,
                        input_name: inputName
                    },
                    success: function(response) {
                        if (response == "duplicate") {
                            inputField.css('border-color', 'red');
                            errorLabel.show();
                            successIcon.hide();
                        } else {
                            inputField.css('border-color', 'green');
                            errorLabel.hide();
                            successIcon.show();
                        }
                    }
                });
            }
        }

        $('#email_field, #phone_field, #username_field').on('input', function() {
            var inputField = $(this);
            var inputValue = inputField.val();
            var inputName = inputField.attr('name');
            var successIcon = $('#' + inputName + '_success_icon');
            var errorLabel = $('#' + inputName + '_error_label');

            if (inputName === 'phone') {
                if (!/^\d*$/.test(inputValue)) { // Check if the value contains only digits
                    inputField.css('border-color', 'red');
                    errorLabel.text('กรุณาใส่เบอร์โทรศัพท์เป็นตัวเลขเท่านั้น').show();
                    successIcon.hide();
                } else {
                    inputField.css('border-color', 'green');
                    errorLabel.hide();
                    successIcon.show();
                }
            } else if (inputName === 'username') {
                if (inputValue.length < 6) {
                    inputField.css('border-color', 'red');
                    errorLabel.text('ชื่อผู้ใช้ต้องมีอย่างน้อย 6 ตัวอักษร').show();
                    successIcon.hide();
                } else {
                    validateField(inputField, successIcon, errorLabel, inputValue, inputName);
                }
            } else {
                validateField(inputField, successIcon, errorLabel, inputValue, inputName);
            }
        });

        // Handle first name and last name fields
        $('#fname_field, #lname_field').on('input', function() {
            var inputField = $(this);
            var inputValue = inputField.val();
            var fieldId = inputField.attr('id');
            var successIcon = $('#' + fieldId + '_success_icon');
            var errorLabel = $('#' + fieldId + '_error_label');
            var isValid = /^[a-zA-Zก-ฮะ-์\s]*$/.test(inputValue); // Only letters (Thai & English) and spaces

            if (inputValue === '') {
                inputField.css('border-color', '');
                successIcon.hide();
                errorLabel.hide();
            } else if (!isValid) {
                inputField.css('border-color', 'red');
                successIcon.hide();
                errorLabel.show();
            } else {
                inputField.css('border-color', 'green');
                successIcon.show();
                errorLabel.hide();
            }
        });

        // Password confirmation check
        $('#psw_field, #confirm_psw_field').on('input', function() {
            var pswField = $('#psw_field');
            var confirmPswField = $('#confirm_psw_field');
            var pswFieldSuccessIcon = $('#psw_success_icon');
            var confirmPswSuccessIcon = $('#confirm_psw_success_icon');

            if (pswField.val().length < 6) { // Check if password has at least 10 characters
                pswField.css('border-color', 'red');
                pswFieldSuccessIcon.hide();
            } else {
                pswField.css('border-color', 'green');
                pswFieldSuccessIcon.show();
            }

            if (confirmPswField.val() === '') {
                confirmPswField.css('border-color', '');
                confirmPswSuccessIcon.hide();
            } else if (pswField.val() === confirmPswField.val() && pswField.val().length >= 6) {
                confirmPswField.css('border-color', 'green');
                confirmPswSuccessIcon.show();
            } else {
                confirmPswField.css('border-color', 'red');
                confirmPswSuccessIcon.hide();
            }
        });
    });
</script>
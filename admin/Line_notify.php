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
                        <i class='bx bxs-bell-ring'></i>
                        Line Notify
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <h3 style="font-weight: 1000;">
                                <img src="../assets/images/other/line.png" alt="Line Notify Logo" style="height: 30px; margin-right: 10px;">
                                Line Notify
                            </h3>
                            <p style="color: #000;">คุณสามารถรับรหัส Token ได้ที่ลิงก์นี้และอ่านคำแนะนำก่อนใช้งาน</p>
                            <a href="https://notify-bot.line.me/th/" target="_blank">https://notify-bot.line.me/th/</a>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>โทเค็น</th>
                                    <th>แก้ไข</th>
                                    <th>แก้ไขล่าสุด</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM tb_tokens";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_array($result)) {
                                ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['token'] ?></td>
                                        <td>
                                            <a class="btn btn-warning" href="javascript:void(0);" onclick="editShipping('<?= $row['id'] ?>', '<?= $row['token'] ?>')"><i class='bx bx-pencil'></i></a>
                                        </td>
                                        <td><?= $row['updated_at'] ?></td>
                                    </tr>
                                <?php
                                }
                                mysqli_close($conn);
                                ?>
                            </tbody>
                        </table>
                        <div class="mt-4" class="mb-2">
                            <a href="index.php" class="btn btn-dark">ย้อนกลับ</a>
                        </div>
                    </div> <!-- card-body -->
                </div>
            </div>
        </main>

        <?php include('footer.php') ?>
    </div>
    </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function editShipping(id, currentName) {
        Swal.fire({
            title: 'แก้ไข Token',
            input: 'text',
            inputValue: currentName,
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'บันทึก',
            showLoaderOnConfirm: true,
            preConfirm: (newName) => {
                return fetch(`Line_notify_edit.php?id=${id}&name=${newName}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`)
                    })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'แก้ไขสำเร็จแล้ว',
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            }
        });
    }
</script>
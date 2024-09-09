<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
                .btn {
                        border: none;
                        font-size: 16px;
                        font-weight: bold;
                        transition: background 0.3s, transform 0.3s;
                }

                .btn:hover {
                        transform: scale(1.05);
                }

                .btn[data-status="waiting"] {
                        background: linear-gradient(195deg, #ffc107 0%, #ffc107 100%);
                        color: #fff;
                }

                .btn[data-status="shipping"] {
                        background: linear-gradient(195deg, #ee4d2d 0%, #ff7337 100%);
                        color: #fff;
                }

                .btn[data-status="delivered"] {
                        background: linear-gradient(195deg, #20c997 0%, #198754 100%);
                        color: #fff;
                }

                .btn[data-status="canceled"] {
                        background: linear-gradient(195deg, #dc3545 0%, #e35866 100%);
                        color: #fff;
                }

                a:hover {
                        color: #fff;
                }
        </style>
</head>

<body>
        <div class="mt-3 mb-3">
                <a href="report_order.php"><button type="button" class="btn" data-status="waiting"><i class='bx bxs-time-five'></i>&nbsp;รอตรวจสอบ</button></a>
                <a href="report_order_wait.php"><button type="button" class="btn" data-status="shipping"><i class='bx bxs-truck'></i>&nbsp;รอจัดส่ง</button></a>
                <a href="report_order_yes.php"><button type="button" class="btn" data-status="delivered"><i class='bx bxs-check-circle'></i>&nbsp;จัดส่งสำเร็จ</button></a>
                <a href="report_order_no.php"><button type="button" class="btn" data-status="canceled"><i class='bx bxs-x-circle'></i>&nbsp;ยกเลิกการสั่งซื้อ</button></a>
        </div>
</body>

</html>
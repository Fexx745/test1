<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link rel="stylesheet" href="assets/CSS/footer.css">

    <!-- #bootrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- <script src="bootstrap/js/bootstrap.bundle.min.js"> </script> -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>

    <footer>
        <div class="container">
                <hr width="100%">
                <div class="footer-last">
                    <p style="color: #6c757d; font-size: 13px;">© 2024 RMUTI. All Rights Reserved</p>
                </div>
        </div>


        <!-- GetButton.io widget -->
        <script async="" defer="" crossorigin="anonymous" src="https://connect.facebook.net/th_TH/sdk.js#xfbml=1&amp;version=v16.0&amp;appId=368793348680076&amp;autoLogAppEvents=1" nonce=""></script>
        <script charset="utf-8" src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>

        <script type="text/javascript">
            var BASE_URL = 'https://www.ihavecpu.com';

            // check cookie for support 2 language system notify
            var cookie_site_lang = 'th';
            let label_call_to_action = '';

            if (cookie_site_lang == 'th') {
                label_call_to_action = 'ส่งข้อความถึงเรา';

            } else {
                label_call_to_action = 'Send a message to us';
            }

            (function() {
                var options = {
                    facebook: "1408301299383405", // Facebook page ID
                    line: "https://line.me/ti/p/kGgLG4g8PY", // Line QR code URL
                    call_to_action: label_call_to_action, // Call to action
                    button_color: "#666666", // Color of button
                    position: "right", // Position may be 'right' or 'left'
                    order: "line,facebook", // Order of buttons
                };
                var proto = 'https:',
                    host = "getbutton.io",
                    url = proto + '//static.' + host;
                var s = document.createElement('script');
                s.type = 'text/javascript';
                s.async = true;
                s.src = url + '/widget-send-button/js/init.js';
                s.onload = function() {
                    WhWidgetSendButton.init(host, proto, options);
                };
                var x = document.getElementsByTagName('script')[0];
                x.parentNode.insertBefore(s, x);
            })();
            $(document).ready(function() {

                $('.google-login').click(function() {
                    $('#google_login').find('.modal-content').load(BASE_URL + '/components/Modal/GoogleLogin.php');
                });

                $("#mainLogoHeaderBar").attr("src", BASE_URL + "/public/asset/image/logos/NewLogoIHAVECPU.png");

                $('#google_login').on('hidden.bs.modal', function() {
                    $('#body').css('overflow', 'auto');
                })


            });
        </script>
    </footer>

</body>

</html>
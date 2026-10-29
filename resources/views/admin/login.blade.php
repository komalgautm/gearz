<!DOCTYPE html>
<html lang="zxx">
<!-- Mirrored from demo.dashboardpack.com/sales-html/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 03 Nov 2023 06:17:20 GMT -->

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Admin Login</title>
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap1.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/themefy_icon/themify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/font_awesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/vendors/scroll/scrollable.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/style1.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/css/colors/default.css') }}" id="colorSkinCSS">
</head>
<style>
    input.btn_1.full_width.text-center {
        background-color: #FFE600;
        color: #000000;
        border-radius: 5px;
        cursor: pointer;
    }

    .loginForm {
        text-align: center;
    }

    .loginForm .btn_1 {
        background-color: #231f20 !important;
        border: 1px solid #231f20 !important;
        color: #ffffff !important;
        padding: 7px 20px !important;
    }

    .loginForm .cs_modal .modal-body {
        background: #f7faff;
    }

    .loginForm .btn_1:hover {
        color: #000000;
        background-color: #FFE600;
        box-shadow: 0 2px 5px #00000052;
    }

    .loginForm .cs_modal .modal-body .btn_1 {
        width: 100%;
        display: block;
        margin-top: 20px;
        font-size: 16px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .loginForm .cs_modal .modal-body input,
    .loginForm .cs_modal .modal-body .nice_Select {
        border: 1px solid #e1e3e5;
    }

    .loginSideImg img {
        height: 100%;
        width: 100%;
        object-fit: cover;
    }

    .loginSideImg {
        height: 80vh;
        width: 100%;
    }

    .loginMain {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .loginMainInner {
        width: 80%;
        margin: auto;
        position: relative;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    }

    .loginFormImg {
        text-align: center;
        padding: 15px;
        position: absolute;
        top: 15px;
        left: 15px;
        height: 130px;
        width: 130px;
    }

    .loginFormImg img {
        height: 100%;
        width: 100%;
        object-fit: contain;
    }

    .username_input {
        border-radius: 25px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        border: 0px;
        padding: 8px 20px;
        margin: 10px;
        width: 60%;
    }
</style>

<body>
    <div class="loginMain">
        <div class="loginMainInner">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6 p-0">
                        <div class="loginForm">
                            <div>
                                <div class="loginFormImg">
                                    <img src="{{ url('public/img/new-logo_G.jpg') }}" alt="Logo">
                                </div>
                                <div class="pt-5">
                                    <form action="{{ url('admin/login') }}" method="post" onsubmit="return validate();">
                                        @csrf
                                        <div class="text-center">
                                            <h3>Login</h3>
                                        </div>
                                        <div>
                                            <input type="text" name="username" class="username_input username" placeholder="Username">
                                        </div>
                                        <div class="mb-4">
                                            <input type="password" name="password" class="username_input password" placeholder="Password">
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn_1 full_width text-center">Log in</button>
                                        </div>
                                        @if(Session::has('message'))
                                        <p class="alert alert-danger"><span style="font-weight: 600;"> Failed !! </span>{{ Session::get('message') }}</p>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 p-0">
                        <div class="loginSideImg">
                            <img src="{{ url('public/img/Background-grey.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('public/js/jquery1-3.4.1.min.js') }}"></script>
    <script src="{{ asset('public/js/popper1.min.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap.min.html') }}"></script>
    <script src="{{ asset('public/js/metisMenu.js') }}"></script>
    <script src="{{ asset('public/vendors/scroll/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('public/vendors/scroll/scrollable-custom.js') }}"></script>
    <script src="{{ asset('public/js/custom.js') }}"></script>
    <script>
        function validate() {
            var username = $('.username').val();
            var password = $('.password').val();
            if (!username) {
                $('.username').css('border', '2px solid red');
                $('.password').css('border', '');
                return false;
            } else if (!password) {
                $('.username').css('border', '');
                $('.password').css('border', '2px solid red');
                return false;
            }
        }
    </script>
    <script>
        $('.alert').fadeOut(6000);
    </script>
    <script>
    fetch("http://localhost/gearz/webhook.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            status: "SUCCESS",
            transactionNumber: "TXN1743069524" // Match with your payment gateway referenceId
        })
    })
    .then(response => {
        if (!response.ok) throw new Error("Network response was not ok");
        return response.text();
    })
    .then(data => console.log("Webhook response:", data))
    .catch(error => console.error("Fetch error:", error));
</script>

</body>
<!-- Mirrored from demo.dashboardpack.com/sales-html/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 03 Nov 2023 06:17:20 GMT -->

</html>
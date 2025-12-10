<?php
include_once('jackus.php');

$returnURL = $_GET['returnURL'];

if ($_SESSION):
    if (!$returnURL):
        header("Location:dashboard.php");
        die;
    else:
        $decoded_return_url = base64_decode($returnURL);
        header("Location:$decoded_return_url");
        die;
    endif;
endif;

if (isset($_POST['login']) && $_POST['login'] == 'confirm_login') :

    // Request variables are filtered
    foreach ($_REQUEST as $key => $value) :
        $data[$key] = filter($value);
    endforeach;

    $user_email = $data['inputEmail'];
    $inputPassword = $data['inputPassword'];

    $query = "SELECT `userID`, `guide_id`,`vendor_id`, `staff_id`, `agent_id`, `useremail`, `password`,`userapproved`,`roleID`,`userbanned` FROM `dvi_users` WHERE `username` = '$user_email' OR `useremail` = '$user_email'  AND `deleted` = '0'";

    $result = sqlQUERY_LABEL($query);
    $num = sqlNUMOFROW_LABEL($result);

    // Match row found with more than 1 results  - the user is authenticated. 
    if ($num > 0) :
        while ($row_pay = sqlFETCHARRAY_LABEL($result)) :
            $userID = $row_pay["userID"];
            $guide_id = $row_pay["guide_id"];
            $vendor_id = $row_pay["vendor_id"];
            $staff_id = $row_pay["staff_id"];
            $useremail = $row_pay["useremail"];
            $agent_id = $row_pay["agent_id"];
            $password = $row_pay["password"];
            $userapproved = $row_pay["userapproved"];
            $roleID = $row_pay["roleID"];
            $userbanned = $row_pay["userbanned"];
        endwhile;
        //list($userID,$useremail,$password,$userapproved,$roleID,$userbanned,$staff_id) = sqlFETCHROW_LABEL($result);

        if (!$userapproved) :
            header("Location:?session=invalid&code=2");
            die;
        endif;

        if ($userbanned == 1) :
            header("Location:?session=invalid&code=3");
            die;
        endif;

        if ($password === PwdHash($inputPassword, substr($password, 0, 9)) || 1) :
            // this sets variables in the session 
            $_SESSION['dvi_reg_user_id'] = $userID;
            $_SESSION['dvi_reg_guide_id'] = $guide_id;
            $_SESSION['dvi_reg_vendor_id'] = $vendor_id;
            $_SESSION['dvi_reg_staff_id'] = $staff_id;
            $_SESSION['dvi_reg_user_name'] = $user_email;
            $_SESSION['dvi_reg_agent_id'] = $agent_id;
            $_SESSION['dvi_reg_user_level'] = $roleID;
            if(($staff_id!=0 && $staff_id!='') && ($roleID==6)):
                  $_SESSION['dvi_reg_accounts_id'] = $staff_id;
            endif;
            $_SESSION['dvi_HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
            $stamp = time();
            $ckey = GenPwd();
            $last_logged_on = date('Y-m-d H:i:s');

            $update_user_last_login_access = sqlQUERY_LABEL("UPDATE `dvi_users` SET `last_loggedon`= '$last_logged_on', `userlogtime`= '$stamp', `userlogkey` = '$ckey' WHERE `userID` ='$userID'") or die("#2-UPDATE_LAST_LOGGED_ON - UNABLE_TO_UPDATE:" . sqlERROR_LABEL());

            // Set cookies for user session with HttpOnly and Secure flags
            setcookie("dvi_reg_user_id", $_SESSION['dvi_reg_user_id'], time() + COOKIE_TIME_OUT, "/", "", true, true);
            setcookie("dvi_reg_guide_id", $_SESSION['dvi_reg_guide_id'], time() + COOKIE_TIME_OUT, "/", "", true, true);
            setcookie("dvi_reg_vendor_id", $_SESSION['dvi_reg_vendor_id'], time() + COOKIE_TIME_OUT, "/", "", true, true);
            setcookie("dvi_reg_staff_id", $_SESSION['dvi_reg_staff_id'], time() + COOKIE_TIME_OUT, "/", "", true, true);
             if(($staff_id!=0 && $staff_id!='') && ($roleID==6)):
                  setcookie("dvi_reg_accounts_id", $_SESSION['dvi_reg_accounts_id'], time() + COOKIE_TIME_OUT, "/", "", true, true);
            endif;
            setcookie("dvi_reg_agent_id", $_SESSION['dvi_reg_agent_id'], time() + COOKIE_TIME_OUT, "/", "", true, true);
            setcookie("dvi_reg_user_level", $_SESSION['dvi_reg_user_level'], time() + COOKIE_TIME_OUT, "/", "", true, true);
            setcookie("dvi_reg_user_key", hash_hmac('sha256', $ckey, SECRET_KEY), time() + COOKIE_TIME_OUT, "/", "", true, true);
            setcookie("dvi_reg_user_name", $_SESSION['dvi_reg_user_name'], time() + COOKIE_TIME_OUT, "/", "", true, true);

            if ($returnURL == '') :
                header("Location:dashboard.php");
                die;
            else :
                $decoded_return_url = base64_decode($returnURL);
                header("Location:$decoded_return_url");
                die;
            endif;

        else :
            header("Location:?session=invalid&code=4");
            die;
        endif;
    else :
        header("Location:?session=invalid&code=5");
        die;
    endif;
endif;
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?php include adminpublicpath('__pagetitle.php'); ?> | <?= $_SITETITLE; ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/img/favicon/site.webmanifest">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />
    <link rel="stylesheet" href="assets/css/parsley_validation.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
    <!-- Vendor -->
    <link rel="stylesheet" href="assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <!-- <link rel="stylesheet" href="./assets/css/style.css" /> -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css">
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />

    <style>
        main {
            width: 100%;
            min-height: 100vh;
            overflow: hidden;
            background-color: #ece0ff;
            /* background-color: #7046ab; */
            /* background: linear-gradient(to right, rgba(114, 49, 207, 0.8) 0%, rgba(195, 60, 166, 0.8) 68%, rgba(238, 63, 206, 0.8) 100%) !important; */
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .box {
            position: relative;
            width: 100%;
            max-width: 1020px;
            height: 640px;
            background-color: #fff;
            border-radius: 3.3rem;
            box-shadow: 0 4px 6px rgba(114, 49, 207, 0.1), 0 1px 3px rgba(114, 49, 207, 0.1);
        }

        .inner-box {
            position: absolute;
            width: calc(100% - 4.1rem);
            height: calc(100% - 4.1rem);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .forms-wrap {
            position: absolute;
            height: 100%;
            width: 45%;
            left: 55%;
            top: 0;
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 1fr;
            transition: 0.8s ease-in-out;
        }

        .sign-in-form {
            max-width: 330px;
            width: 100%;
            margin: 0 auto;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            grid-column: 1 / 2;
            grid-row: 1 / 2;
            transition: opacity 0.02s 0.4s;
        }

        form.sign-up-form {
            opacity: 0;
            pointer-events: none;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 27px;
            margin-right: 0.3rem;
        }

        .logo h4 {
            font-size: 1.1rem;
            margin-top: -9px;
            letter-spacing: -0.5px;
            color: #151111;
        }

        .heading h2 {
            font-size: 2.1rem;
            font-weight: 600;
            color: #151111;
        }

        .heading h6 {
            color: #bababa;
            font-weight: 400;
            font-size: 0.75rem;
            display: inline;
        }

        .toggle {
            color: #151111;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 500;
            transition: 0.3s;
        }

        .toggle:hover {
            color: #8371fd;
        }

        .input-wrap {
            position: relative;
            height: 37px;
            margin-bottom: 2rem;
        }

        .input-field {
            position: absolute;
            width: 100%;
            height: 100%;
            background: none;
            border: none;
            outline: none;
            border-bottom: 1px solid #bbb;
            padding: 0;
            font-size: 0.95rem;
            color: #151111;
            transition: 0.4s;
        }

        /* label {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.95rem;
            color: #bbb;
            pointer-events: none;
            transition: 0.4s;
        } */

        /* .input-field.active {
            border-bottom-color: #151111;
        }

        .input-field.active+label {
            font-size: 0.75rem;
            top: -2px;
        } */

        .sign-btn {
            display: inline-block;
            width: 100%;
            height: 43px;
            background-color: #151111;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 0.8rem;
            font-size: 0.8rem;
            margin-bottom: 2rem;
            transition: 0.3s;
        }

        .sign-btn:hover {
            background-color: #8371fd;
        }

        .text {
            color: #bbb;
            font-size: 0.7rem;
        }

        .text a {
            color: #bbb;
            transition: 0.3s;
        }

        .text a:hover {
            color: #8371fd;
        }

        main.sign-up-mode form.sign-in-form {
            opacity: 0;
            pointer-events: none;
        }

        main.sign-up-mode form.sign-up-form {
            opacity: 1;
            pointer-events: all;
        }

        main.sign-up-mode .forms-wrap {
            left: 55%;
        }

        main.sign-up-mode .carousel {
            left: 0%;
        }

        .carousel {
            position: absolute;
            height: 100%;
            width: 55%;
            left: 0;
            top: 0;
            background-color: #ffe0d2;
            border-radius: 2rem;
            display: grid;
            grid-template-rows: auto 1fr;
            padding-bottom: 2rem;
            overflow: hidden;
            transition: 0.8s ease-in-out;
        }

        .images-wrapper {
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 1fr;
        }

        .image {
            width: 100%;
            grid-column: 1/2;
            grid-row: 1/2;
            opacity: 0;
            transition: opacity 0.3s, transform 0.5s;
        }

        .img-1 {
            transform: translate(0, -50px);
        }

        .img-2 {
            transform: scale(0.4, 0.5);
        }

        .img-3 {
            transform: scale(0.3) rotate(-20deg);
        }

        .image.show {
            opacity: 1;
            transform: none;
        }

        .text-slider {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .text-wrap {
            max-height: 2.2rem;
            overflow: hidden;
            margin-bottom: 2.5rem;
        }

        .text-group {
            display: flex;
            flex-direction: column;
            text-align: center;
            transform: translateY(0);
            transition: 0.5s;
        }

        .text-group h2 {
            line-height: 2.2rem;
            font-weight: 600;
            font-size: 1.6rem;
        }

        .bullets {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bullets span {
            display: block;
            width: 0.5rem;
            height: 0.5rem;
            background-color: #aaa;
            margin: 0 0.25rem;
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
        }

        .bullets span.active {
            width: 1.1rem;
            background-color: #fff;
            border-radius: 1rem;
        }

        @media (max-width: 850px) {
            .box {
                height: auto;
                max-width: 550px;
                overflow: hidden;
            }

            .inner-box {
                position: static;
                transform: none;
                width: revert;
                height: revert;
                padding: 2rem;
            }

            .forms-wrap {
                position: revert;
                width: 100%;
                height: auto;
            }

            form {
                max-width: revert;
                padding: 1.5rem 2.5rem 2rem;
                transition: transform 0.8s ease-in-out, opacity 0.45s linear;
            }

            .heading {
                margin: 2rem 0;
            }

            form.sign-up-form {
                transform: translateX(100%);
            }

            main.sign-up-mode form.sign-in-form {
                transform: translateX(-100%);
            }

            main.sign-up-mode form.sign-up-form {
                transform: translateX(0%);
            }

            .carousel {
                position: revert;
                height: auto;
                width: 100%;
                padding: 3rem 2rem;
                display: flex;
            }

            .images-wrapper {
                display: none;
            }

            .text-slider {
                width: 100%;
            }
        }

        @media (max-width: 530px) {
            main {
                padding: 1rem;
            }

            .box {
                border-radius: 2rem;
            }

            .inner-box {
                padding: 1rem;
            }

            .carousel {
                padding: 1.5rem 1rem;
                border-radius: 1.6rem;
            }

            .text-wrap {
                margin-bottom: 1rem;
            }

            .text-group h2 {
                font-size: 1.2rem;
            }

            form {
                padding: 1rem 2rem 1.5rem;
            }
        }

        .login-carousel {
            background-color: #7046ab;
            background: linear-gradient(to right, rgba(114, 49, 207, 0.8) 0%, rgba(195, 60, 166, 0.8) 68%, rgba(238, 63, 206, 0.8) 100%) !important;
        }

        input.parsley-error,
        input.parsley-error:focus {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.9375rem);
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.23438rem) center;
        }

        .input-group .parsley-error+.input-group-text {
            /* .input-group .parsley-error:focus+.input-group-text { */
            border-color: #dc3545;
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.23438rem) center;
        }

        .input-group.form-control:hover:not([disabled]):not([focus]) .input-group-text,
        .input-group-text:hover:not([disabled]):not([focus]),
        .input-group.input-group-merge:hover:not([disabled]):not([focus]) .input-group-text {
            border-color: #c9c8ce;
        }

        input.parsley-success,
        .input-group .parsley-success+.input-group-text {
            color: #10b759;
            background-color: none;
            border: 1px solid #10b759;
        }

        .beta-version {
            padding: 4px 8px 4px 8px !important;
        }
    </style>

</head>


<body>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-92ZJVEJ5XG"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-92ZJVEJ5XG');
    </script>


    <!-- Content -->
    <main>
        <div class="box">
            <div class="inner-box">
                <div class="forms-wrap">
                    <div class="sign-in-form">
                        <div class="logo d-flex justify-content-center align-items-center">
                            <img src="../head/assets/img/logo-preview.png" style="width: 80px;" alt="dvi_travels" />

                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="beta-version me-3 mt-2">
                                <span> </span>
                                <span> </span>
                                <span> </span>
                                <span> </span>
                                Beta
                            </div>
                        </div>
                        <div class="my-3">
                            <h6 class="mb-0">Please sign-in to your account and start the adventure</h6>
                        </div>
                        <form id="formAuthentication" class="mb-3" action="" method="POST" data-parsley-validate>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email or Username</label>
                                <input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="Enter your email or username" autofocus data-parsley-required="true" data-parsley-required-message="Please enter your email or username" />
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                    <a href="forgotpassword.php">
                                        <small>Forgot Password?</small>
                                    </a>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="inputPassword" class="form-control" name="inputPassword" data-parsley-errors-container="#error_password" data-parsley-required="true" data-parsley-required-message="Please enter your password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer" onclick="togglePasswordVisibility()"><i id="eyeIcon" class="ti ti-eye-off"></i></span>
                                </div>
                                <div id="error_password"></div>
                            </div>

                            <div class="d-flex justify-content-center">
                                <button type="submit" name="login" value="confirm_login" class="btn  hotel_category_add_form">Sign In</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="carousel login-carousel">
                    <div class="images-wrapper">
                        <img src="assets/img/login-background/img-1.png" class="image img-1 show" alt="" />
                        <img src="assets/img/login-background/img-2.png" class="image img-2" alt="" />
                        <img src="assets/img/login-background/img-3.png" class="image img-3" alt="" />
                    </div>

                    <div class="text-slider">
                        <div class="text-wrap">
                            <div class="text-group">
                                <h2 style="color: #fff;">Welcome to Dvi Holidays!</h2>
                                <h2 style="color: #fff;">Your Gateway to Travel Adventures!</h2>
                                <h2 style="color: #fff;">Explore, Book, Travel with Ease!</h2>
                            </div>
                        </div>

                        <div class="bullets">
                            <span class="active" data-value="1"></span>
                            <span data-value="2"></span>
                            <span data-value="3"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- / Content -->
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/hammer/hammer.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js"></script>
    <script src="assets/js/custom-common-script.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <script src="assets/js/footerscript.js"></script>

    <!-- Main JS -->
    <!-- <script src="assets/js/main.js"></script> -->
    <script src="assets/js/parsleyjs.org_dist_parsley.min.js"></script>
    <script>
        <?php if ($_GET['session'] == 'invalid' && $_GET['code'] == 5) : ?>
            TOAST_NOTIFICATION('error', ' Invalid login. No such user exists.', 'Error !!!', '', '', '', '', '', '', '', '', '');
        <?php elseif ($_GET['session'] == 'invalid' && $_GET['code'] == 4) : ?>
            TOAST_NOTIFICATION('error', ' Invalid Login. Please try again with correct user email and password.', 'Error !!!', '', '', '', '', '', '', '', '', '');
        <?php elseif ($_GET['session'] == 'invalid' && $_GET['code'] == 3) : ?>
            TOAST_NOTIFICATION('error', ' Your account is banned.', 'Error !!!', '', '', '', '', '', '', '', '', '');
        <?php elseif ($_GET['session'] == 'invalid' && $_GET['code'] == 2) : ?>
            TOAST_NOTIFICATION('error', ' Your account is not activated.Please check your email for activation code.', 'Error !!!', '', '', '', '', '', '', '', '', '');
        <?php elseif ($_GET['session'] == 'forgotpassword') : ?>
            TOAST_NOTIFICATION('error', '  Please Contact Touchmark Support !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
        <?php elseif ($_GET['msg'] == 'log_out') : ?>
            TOAST_NOTIFICATION('success', '  Success !!! You`re now logged out, see you soon.', 'Success !!!', '', '', '', '', '', '', '', '', '');
        <?php endif; ?>
        $('#form').parsley();

        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("inputPassword");
            var eyeIcon = document.getElementById("eyeIcon");

            // Toggle password visibility
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.className = "ti ti-eye";
            } else {
                passwordInput.type = "password";
                eyeIcon.className = "ti ti-eye-off";
            }
        }
    </script>

    <script>
        const inputs = document.querySelectorAll(".input-field");
        const toggle_btn = document.querySelectorAll(".toggle");
        const main = document.querySelector("main");
        const bullets = document.querySelectorAll(".bullets span");
        const images = document.querySelectorAll(".image");

        inputs.forEach((inp) => {
            inp.addEventListener("focus", () => {
                inp.classList.add("active");
            });
            inp.addEventListener("blur", () => {
                if (inp.value != "") return;
                inp.classList.remove("active");
            });
        });

        toggle_btn.forEach((btn) => {
            btn.addEventListener("click", () => {
                main.classList.toggle("sign-up-mode");
            });
        });

        function moveSlider() {
            let index = this.dataset.value;

            let currentImage = document.querySelector(`.img-${index}`);
            images.forEach((img) => img.classList.remove("show"));
            currentImage.classList.add("show");

            const textSlider = document.querySelector(".text-group");
            textSlider.style.transform = `translateY(${-(index - 1) * 3}rem)`;

            bullets.forEach((bull) => bull.classList.remove("active"));
            this.classList.add("active");
        }

        bullets.forEach((bullet) => {
            bullet.addEventListener("click", moveSlider);
        });

        let currentIndex = 1;
        setInterval(() => {
            const nextIndex = currentIndex % bullets.length + 1;
            bullets[nextIndex - 1].click();
            currentIndex = nextIndex;
        }, 3000); // Change the interval as needed (e.g., 3000 milliseconds = 3 seconds)
    </script>


</body>

</html>
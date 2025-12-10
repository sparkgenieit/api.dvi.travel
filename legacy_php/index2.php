<?php
include_once('jackus.php');

if (isset($_POST['login']) && $_POST['login'] == 'confirm_login') :
    // Request variables are filtered
    foreach ($_REQUEST as $key => $value) :
        $data[$key] = filter($value);
    endforeach;

    $user_email = $data['inputEmail'];
    $inputPassword = $data['inputPassword'];



    $query = "SELECT `userID`, `vendor_id`, `useremail`, `password`,`userapproved`,`roleID`,`userbanned` FROM `dvi_users` WHERE `username` = '$user_email' OR `useremail` = '$user_email'  AND `deleted` = '0'";

    $result = sqlQUERY_LABEL($query);
    $num = sqlNUMOFROW_LABEL($result);

    // Match row found with more than 1 results  - the user is authenticated. 
    if ($num > 0) :
        while ($row_pay = sqlFETCHARRAY_LABEL($result)) :
            $userID = $row_pay["userID"];
            $vendor_id = $row_pay["vendor_id"];
            $useremail = $row_pay["useremail"];
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

        if ($password === PwdHash($inputPassword, substr($password, 0, 9))) :
            // this sets variables in the session 
            $_SESSION['dvi_reg_user_id'] = $userID;
            $_SESSION['dvi_reg_vendor_id'] = $vendor_id;
            $_SESSION['dvi_reg_user_name'] = $user_email;
            $_SESSION['dvi_reg_user_level'] = $roleID;
            $_SESSION['dvi_HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
            $stamp = time();
            $ckey = GenPwd();
            $last_logged_on = date('Y-m-d H:i:s');

            $update_user_last_login_access = sqlQUERY_LABEL("UPDATE `dvi_users` SET `last_loggedon`= '$last_logged_on', `userlogtime`= '$stamp', `userlogkey` = '$ckey' WHERE `userID` ='$userID'") or die("#2-UPDATE_LAST_LOGGED_ON - UNABLE_TO_UPDATE:" . sqlERROR_LABEL());

            //set a cookie 
            setcookie("dvi_reg_user_id", $_SESSION['dvi_reg_user_id'], time() + 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
            setcookie("dvi_reg_vendor_id", $_SESSION['dvi_reg_vendor_id'], time() + 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
            setcookie("dvi_reg_user_level", $_SESSION['dvi_reg_user_level'], time() + 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
            setcookie("dvi_reg_user_key", sha1($ckey), time() + 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
            setcookie("dvi_reg_user_name", $_SESSION['dvi_reg_user_name'], time() + 60 * 60 * 24 * COOKIE_TIME_OUT, "/");

            if ($returnURL == '') :
                if ($vendor_id != 0) :
                    header("Location:vendor_dashboard.php");
                else :
                    header("Location:dashboard.php");
                endif;
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

</head>


<body>


    <!-- Content -->
    <div class="authentication-wrapper authentication-cover authentication-bg">
        <div class="authentication-inner row">
            <!-- /Left Text -->

            <div class="d-none d-lg-flex col-lg-7 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img src="./assets/img/illustrations/auth-login-illustration-light.png" alt="auth-login-cover" class="img-fluid my-5 auth-illustration" data-app-light-img="illustrations/auth-login-illustration-light.png" data-app-dark-img="illustrations/auth-login-illustration-dark.html">

                    <img src="./assets/img/illustrations/bg-shape-image-light.png" alt="auth-login-cover" class="platform-bg" data-app-light-img="illustrations/bg-shape-image-light.png" data-app-dark-img="illustrations/bg-shape-image-dark.html">
                </div>
            </div>

            <!-- /Left Text -->

            <!-- Login -->
            <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
                <div class="w-px-400 mx-auto">
                    <span id="response_alert"></span>
                    <div class="text-center mb-4">
                        <img src="./assets/img/logo.png" class="index_logo" />
                    </div>
                    <h3 class="mb-1 text-center ">Welcome to Dvi Holidays!</h3>
                    <p class="mb-4">Please sign-in to your account and start the adventure</p>

                    <form id="formAuthentication" class="mb-3" action="" method="POST" data-parsley-validate>
                        <!-- RESPONSE ALERT -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email or Username</label>
                            <input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="Enter your email or username" autofocus data-parsley-required="true" data-parsley-required-message="Please enter your email or username" />
                        </div>

                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                                <a href="#">
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

            <!-- /Login -->
        </div>
    </div>

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
</body>

</html>
<?php
include_once('jackus.php');

$_email = $_GET['email'];
$_key = $_GET['key'];

if ($_email) :
    $decoded_email = Encryption::Decode($_email, SECRET_KEY);
endif;

$validate_hash_key = getPASSWORD_RESET_LOG_DETAILS($decoded_email, $_key, 'reset_key');
$_key_expiry_date = getPASSWORD_RESET_LOG_DETAILS($decoded_email, $_key, 'expiry_date');
$_key_status = getPASSWORD_RESET_LOG_DETAILS($decoded_email, $_key, 'status');
$check_reset_count = getPASSWORD_RESET_LOG_DETAILS($decoded_email, $_key, 'check_reset_count');
$current_DATETIME = date('Y-m-d H:i:s');

$select_global_settings_details = sqlQUERY_LABEL("SELECT `company_logo` FROM `dvi_global_settings` WHERE `status`='1' and `deleted` = '0'") or die("#1-UNABLE_TO_GET_GLOBAL_SETTINGS:" . sqlERROR_LABEL());
while ($fetch_data = sqlFETCHARRAY_LABEL($select_global_settings_details)) :
    $logo = $fetch_data['company_logo'];
endwhile;

?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="vertical-menu-template">

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
            <div class="row justify-content-center">
                <div class="col-md-5 col-xl-4">
                    <div class="admin-logo text-center mb-5">
                        <img src="<?= BASEPATH . '/uploads/logo/' . $logo; ?>" alt="logo" class="img-fluid" width="150px" />
                    </div>
                    <div class="card card-body">
                        <span id="response_alert"></span>
                        <?php
                        if ($_email != '' && $_key != '' && $_GET['action'] == 'reset' && $validate_hash_key == $_key) :
                            if ($_key_status == 1) :
                        ?>
                                <h4 class="admin-login-header text-center m-3">Forgot Password?</h4>
                                <p class="admin-login-subcontent text-center">Enter your email and we'll send you instructions to reset your password</p>
                                <form id="forgot_password_form" class="mt-4 needs-validation" novalidate="" action="" method="post" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="forgot_pwd_email" class="form-label">Email ID<span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="forgot_pwd_email" name="forgot_pwd_email" required placeholder="name@example.com" aria-describedby="email">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" id="forgot_pwd_btn" class="btn btn-primary w-100 mt-3">Login</button>
                                    </div>
                                    <div class="text-center mb-3 mt-3">
                                        <span class="admin-login-subcontent">I know my password? <a href="index.php" class="mt-2 ml-auto text-primary">Login</a></span>
                                    </div>
                                </form>
                            <?php elseif ($_key_expiry_date >= $current_DATETIME) : ?>
                                <span id="show_ajax_response_data" style="display:none;">
                                    <h4 class="admin-login-header text-center m-3">Forgot Password?</h4>
                                    <p class="admin-login-subcontent text-center">Enter your email and we'll send you instructions to reset your password</p>
                                    <form id="forgot_password_form" class="mt-4 needs-validation" novalidate="" action="" method="post" data-parsley-validate>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="forgot_pwd_email" class="form-label">Email ID<span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" id="forgot_pwd_email" name="forgot_pwd_email" required placeholder="name@example.com" aria-describedby="email">
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="submit" id="forgot_pwd_btn" class="btn btn-primary w-100 mt-3">Login</button>
                                        </div>
                                        <div class="text-center mb-3 mt-3">
                                            <span class="admin-login-subcontent">I know my password? <a href="index.php" class="mt-2 ml-auto text-primary">Login</a></span>
                                        </div>
                                    </form>
                                </span>
                                <span id="default_response">
                                    <h4 class="admin-login-header text-center mb-2">Create New Password</h4>
                                    <form id="reset_password_form" class="theme-form login-form needs-validation" novalidate="" action="" method="post" data-parsley-validate>
                                        <div class="forgot-password-text text-left">
                                            <div class="password-conformation-email text-center"><b class="text-primary"><?= $decoded_email; ?></b></div>
                                        </div>
                                        <div class="col-12">
                                            <span id="response_modal"></span>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="mb-3 form-password-toggle fv-plugins-icon-container">
                                                <label class="form-label" for="new_password">New Password</label>
                                                <div class="input-group input-group-merge has-validation">
                                                    <input type="password" id="new_password" class="form-control" name="new_password" placeholder="Enter your New Password" data-parsley-trigger="keyup" data-parsley-minlength="8" data-parsley-uppercase="1" data-parsley-lowercase="1" data-parsley-number="1" data-parsley-special="1" required placeholder="Enter your New Password" type="password" data-parsley-errors-container="#error_container_new_password" aria-describedby="new_password">
                                                    <span class="input-group-text cursor-pointer show-hide-password show_new_password"><i class="ti ti-eye-off"></i></span>
                                                </div>
                                                <div id="error_container_new_password"></div>
                                            </div>

                                            <div class="mb-3 form-password-toggle fv-plugins-icon-container">
                                                <label class="form-label" for="confirm_password">Confirm Password</label>
                                                <div class="input-group input-group-merge has-validation">
                                                    <input class="form-control form-control-password" data-parsley-equalto="#new_password" data-parsley-trigger="keyup" id="confirm_password" name="confirm_password" required placeholder="Enter your Confirm Password" type="password" data-parsley-errors-container="#error_container_confirm_password">
                                                    <span class="input-group-text cursor-pointer show-hide-password show_confirm_password"><i class="ti ti-eye-off"></i></span>
                                                </div>
                                                <div id="error_container_confirm_password"></div>
                                            </div>

                                            <input type="hidden" name="hidden_email" id="hidden_email" value="<?= $_email; ?>" hidden>
                                            <input type="hidden" name="hidden_key" id="hidden_key" value="<?= $_key; ?>" hidden>
                                            <div class="col-md-12 mb-3">
                                                <button type="submit" id="reset_pwd_btn" class="btn btn-primary w-100 mt-3">Reset Password</button>
                                            </div>
                                        </div>
                                    </form>
                                </span>
                            <?php else : ?>
                                <h4 class="admin-login-header text-center m-3">Forgot Password?</h4>
                                <p class="admin-login-subcontent text-center">Enter your email and we'll send you instructions to reset your password</p>
                                <form id="forgot_password_form" class="mt-4 needs-validation" novalidate="" action="" method="post" data-parsley-validate>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="forgot_pwd_email" class="form-label">Email ID<span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="forgot_pwd_email" name="forgot_pwd_email" required placeholder="name@example.com" aria-describedby="email">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" id="forgot_pwd_btn" class="btn btn-primary w-100 mt-3">Login</button>
                                    </div>
                                    <div class="text-center mb-3 mt-3">
                                        <span class="admin-login-subcontent">I know my password? <a href="index.php" class="mt-2 ml-auto text-primary">Login</a></span>
                                    </div>
                                </form>
                            <?php endif; ?>
                        <?php else : ?>
                            <h4 class="admin-login-header text-center m-3">Create New Password</h4>
                            <form id="forgot_password_form" class=" theme-form login-form needs-validation" novalidate="" action="" method="post" data-parsley-validate>
                                <div class="text-center">
                                    <div class="text-center">
                                        <img src="assets/img/404.svg" alt="logo" class="img-fluid" />
                                    </div>
                                    <h5 class="mb-3">Page Not Found</h5>
                                    <span class="m-2">We're sorry, the page you requested could not be found. Please go back to the homepage.</span>
                                    <a href="index.php" class="btn btn-primary w-100 mt-3">Back to Login</a>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    <script src="assets/js/custom-common-script.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <script src="assets/js/footerscript.js"></script>

    <!-- Main JS -->
    <!-- <script src="assets/js/main.js"></script> -->
    <script src="assets/js/parsleyjs.org_dist_parsley.min.js"></script>

    <script>
        <?php if ($_GET['session'] == 'invalid' && $_GET['code'] == 5) : ?>
            TOAST_NOTIFICATION('error', 'Invalid login. No such user exists.', 'Error !!!', '', '', '', '', '', '', '', '', '');
        <?php elseif ($_GET['session'] == 'invalid' && $_GET['code'] == 4) : ?>
            TOAST_NOTIFICATION('error', 'Invalid Login. Please try again with correct user email and password.', 'Error !!!', '', '', '', '', '', '', '', '', '');
        <?php elseif ($_GET['session'] == 'invalid' && $_GET['code'] == 3) : ?>
            TOAST_NOTIFICATION('error', 'Your account is banned.', 'Error !!!', '', '', '', '', '', '', '', '', '');
        <?php elseif ($_GET['session'] == 'invalid' && $_GET['code'] == 2) : ?>
            TOAST_NOTIFICATION('error', 'Your account is not activated.Please check your email for activation code.', 'Error !!!', '', '', '', '', '', '', '', '', '');
        <?php elseif ($_GET['session'] == 'forgotpassword') : ?>
            TOAST_NOTIFICATION('error', 'Please Contact your Admin!!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
        <?php endif; ?>

        $(document).ready(function() {
            $('#new_password, #confirm_password').on('keyup', function() {
                if ($(this).length > 0) {
                    $('#reset_pwd_btn').removeAttr('disabled');
                } else {
                    $('#reset_pwd_btn').attr('disabled', true);
                }
            });

            $('#forgot_pwd_email').on('keyup', function() {
                if ($(this).length > 0) {
                    $('#forgot_pwd_btn').removeAttr('disabled');
                } else {
                    $('#forgot_pwd_btn').attr('disabled', true);
                }
            });

            // Show/hide new password
            $('.show_new_password').click(function() {
                let passwordInput = $('#new_password');
                let icon = $(this).find('i');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('ti-eye-off').addClass('ti-eye');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('ti-eye').addClass('ti-eye-off');
                }
            });

            // Show/hide confirm password
            $('.show_confirm_password').click(function() {
                let passwordInput = $('#confirm_password');
                let icon = $(this).find('i');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('ti-eye-off').addClass('ti-eye');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('ti-eye').addClass('ti-eye-off');
                }
            });

            $(document).ready(function() {

                //has uppercase
                $('#new_password').parsley();
                window.Parsley.addValidator('uppercase', {
                    requirementType: 'number',
                    validateString: function(value, requirement) {
                        var uppercases = value.match(/[A-Z]/g) || [];
                        return uppercases.length >= requirement;
                    },
                    messages: {
                        en: 'Your password must contain at least %s uppercase letter.'
                    }
                });

                //has lowercase
                $('#new_password').parsley();
                window.Parsley.addValidator('lowercase', {
                    requirementType: 'number',
                    validateString: function(value, requirement) {
                        var lowecases = value.match(/[a-z]/g) || [];
                        return lowecases.length >= requirement;
                    },
                    messages: {
                        en: 'Your password must contain at least %s lowercase letter.'
                    }
                });

                //has number
                $('#new_password').parsley();
                window.Parsley.addValidator('number', {
                    requirementType: 'number',
                    validateString: function(value, requirement) {
                        var numbers = value.match(/[0-9]/g) || [];
                        return numbers.length >= requirement;
                    },
                    messages: {
                        en: 'Your password must contain at least %s number.'
                    }
                });

                //has special char
                $('#new_password').parsley();
                window.Parsley.addValidator('special', {
                    requirementType: 'number',
                    validateString: function(value, requirement) {
                        var specials = value.match(/[^a-zA-Z0-9]/g) || [];
                        return specials.length >= requirement;
                    },
                    messages: {
                        en: 'Your password must contain at least %s special characters.'
                    }
                });

                $("#reset_password_form").submit(function(event) {
                    var form = $('#reset_password_form')[0];
                    var data = new FormData(form);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_reset_password.php?type=reset_pwd',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        console.log(data);
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.errors.not_valid_email) {
                                TOAST_NOTIFICATION('error', response.errors.not_valid_email, 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                            if (response.errors.no_user_found) {
                                TOAST_NOTIFICATION('error', response.errors.no_user_found, 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                            if (response.errors.link_already_used) {
                                TOAST_NOTIFICATION('error', response.errors.link_already_used, 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                            if (response.errors.link_expired) {
                                TOAST_NOTIFICATION('error', response.errors.link_expired, 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                            if (response.errors.password_does_not_match) {
                                TOAST_NOTIFICATION('error', response.errors.password_does_not_match, 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.result == true) {
                                //RESULT SUCCESS
                                $('#default_response').remove();
                                $('#show_ajax_response_data').show();
                                TOAST_NOTIFICATION('success', response.html_result, 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', response.result_error, 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        }
                        if (response == "OK") {
                            return true;
                        } else {
                            return false;
                        }
                    });
                    event.preventDefault();
                });

                $("#forgot_password_form").submit(function(event) {
                    var form = $('#forgot_password_form')[0];
                    var data = new FormData(form);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_forgot_password.php?type=forgot_pwd',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        console.log(data);
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.errors.not_valid_email) {
                                TOAST_NOTIFICATION('error', response.errors.not_valid_email, 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                            if (response.errors.no_user_found) {
                                TOAST_NOTIFICATION('error', response.errors.no_user_found, 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                            if (response.errors.account_not_activated) {
                                TOAST_NOTIFICATION('error', response.errors.account_not_activated, 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                            if (response.errors.account_banned) {
                                TOAST_NOTIFICATION('error', response.errors.account_banned, 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                            if (response.errors.not_allowed) {
                                TOAST_NOTIFICATION('error', response.errors.not_allowed, 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            $('#response_alert').html('');
                            if (response.result == true) {
                                //RESULT SUCCESS
                                $('#default_response').load(' #default_response');
                                TOAST_NOTIFICATION('success', response.html_result, 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', response.result_error, 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        }
                        if (response == "OK") {
                            return true;
                        } else {
                            return false;
                        }
                    });
                    event.preventDefault();
                });
            });
        });

        <?php if ($check_reset_count > 0 && ($_key_status == 1 || ($_key_expiry_date < $current_DATETIME))) : ?>
            TOAST_NOTIFICATION('error', 'Password reset url is invalid or has expired. Try requesting another password reset url.', 'Error !!!', '', '', '', '', '', '', '', '', '');
        <?php endif; ?>
    </script>
</body>

</html>

<!-- beautify ignore:end -->
<?php
include_once('jackus.php');

$select_global_settings_details = sqlQUERY_LABEL("SELECT `company_logo` FROM `dvi_global_settings` WHERE `status`='1' and `deleted` = '0'") or die("#1-UNABLE_TO_GET_GLOBAL_SETTINGS:" . sqlERROR_LABEL());
while ($fetch_data = sqlFETCHARRAY_LABEL($select_global_settings_details)) :
  $logo = $fetch_data['company_logo'];
endwhile;
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-wide  customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template">

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
          <img src="assets/img/forgot_pass2.png" alt="auth-forgot-password-cover" class="img-fluid my-5 auth-illustration">
        </div>
      </div>
      <!-- /Left Text -->

      <!-- Forgot Password -->
      <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
        <div class="w-px-400 mx-auto">
          <!-- Logo -->
          <div class="app-brand mb-4">
            <a href="index.php" class="app-brand-link gap-2">
              <span class="demo">
                <img src="<?= BASEPATH . '/uploads/logo/' . $logo; ?>" alt="logo" class="img-fluid" width="150px" />
              </span>
            </a>
          </div>
          <!-- /Logo -->
          <h3 class="mb-1">Forgot Password?</h3>
          <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>
          <form id="forgot_password_form" class="mb-3" novalidate="" action="" method="post" data-parsley-validate>
            <div class="mb-3">
              <label for="forgot_pwd_email" class="form-label">Email ID</label>
              <input type="email" class="form-control" autocomplete="off" required id="forgot_pwd_email" name="forgot_pwd_email" placeholder="Enter your Email Address" autofocus>
            </div>
            <button type="submit" id="forgot_pwd_btn" class="btn btn-primary d-grid w-100">Send Reset Link</button>
          </form>
          <div class="text-center">
            <a href="index.php" class="d-flex align-items-center justify-content-center">
              <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
              Back to login
            </a>
          </div>
        </div>
      </div>
      <!-- /Forgot Password -->
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
  <!-- / Content -->

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
      $('#forgot_pwd_email').on('keyup', function() {
        if ($(this).length > 0) {
          $('#forgot_pwd_btn').removeAttr('disabled');
        } else {
          $('#forgot_pwd_btn').attr('disabled', true);
        }
      });

      $("#forgot_password_form").submit(function(event) {
        var form = $('#forgot_password_form')[0];
        var data = new FormData(form);
        $(this).find("button[type='submit']").prop('disabled', true);
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
              TOAST_NOTIFICATION('success', response.html_result, 'Success !!!', '', '', '', '', '', '', '', '', '');
              $('#forgot_password_form')[0].reset();
              $('#forgot_pwd_email').val('');
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
  </script>
</body>

</html>
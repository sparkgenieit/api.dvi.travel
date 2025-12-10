<?php
include_once("jackus.php");
admin_reguser_protect();
require_once('guide_invoice.php');
$current_page = 'dashboard.php'; // Set the current page variable
?>

<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon/favicon-16x16.png">
  <link rel="manifest" href="assets/img/favicon/site.webmanifest">


  <!-- Favicon -->
  <!-- <link rel="icon" type="image/x-icon" href="https://demos.pixinvent.com/vuexy-html-admin-template/assets/img/favicon/favicon.ico" /> -->

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com/">
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="./assets/vendor/fonts/fontawesome.css" />
  <link rel="stylesheet" href="./assets/vendor/fonts/tabler-icons.css" />
  <link rel="stylesheet" href="./assets/vendor/fonts/flag-icons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="./assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="./assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="./assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="./assets/vendor/libs/node-waves/node-waves.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/typeahead-js/typeahead.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/apex-charts/apex-charts.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
  <link rel="stylesheet" type="text/css" href="assets/css/parsley_validation.css">
  <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
    <link rel="stylesheet" href="assets/css/_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/_1.13.6_css_jquery.dataTables.min.css" />



  <!-- Map -->
  <link rel="stylesheet" href="assets/vendor/libs/leaflet/leaflet.css" />
  <!-- Map -->

  <!-- Swiper -->
  <link rel="stylesheet" href="assets/vendor/libs/swiper/swiper.css" />

  <!-- Helpers -->
  <script src="./assets/vendor/js/helpers.js"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
  <script src="./assets/vendor/js/template-customizer.js"></script>
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="./assets/js/config.js"></script>

  <!-- Page CSS -->

  <link rel="stylesheet" href="./assets/vendor/css/pages/app-logistics-dashboard.css" />
  <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar  ">
    <div class="layout-container">
      <!-- Menu -->
      <div class="layout-page">
	  
        <?php include_once('public/__sidebar.php'); ?>

        <!-- Navbar -->
        <?php include_once('public/__topbar.php'); ?>
        <!-- / Navbar -->
		
        <div class="content-wrapper">
			<!-- Content -->
			<div class="container-xxl flex-grow-1 container-p-y">
				<div class=" d-flex justify-content-between align-items-center">
					<h4><?php include adminpublicpath('__pagetitle.php'); ?></h4>
					<?php include adminpublicpath('__breadcrumb.php'); ?>
				</div>

				<span id="showINVOICELIST"></span>
				<span id="showINVOICEDETAIL"></span>
			</div>
			<!-- / Content -->
			
			<!-- Footer -->
			<?php include_once('public/__footer.php'); ?>
			<!-- / Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>



    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>


    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>

  </div>
  <!-- / Layout wrapper -->

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/vendor/js/menu.js"></script>
    <script src="assets/vendor/libs/tagify/tagify.js"></script>
    <script src="assets/js/forms-tagify.js"></script>

    <!-- endbuild -->
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <!-- Form Validation -->
    <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
    <script src="assets/js/parsley.min.js"></script>
    <script src="assets/js/custom-common-script.js"></script>
    <script src="assets/js/easy-autocomplete.min.js"></script>
    <script src="assets/js/selectize/selectize.min.js"></script>
    <!-- Vendors JS -->
    <script src="assets/vendor/libs/jquery-repeater/jquery-repeater.js"></script>

    <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
    <script src="assets/vendor/libs/bootstarp-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="assets/js/_jquery.dataTables.min.js"></script>
    <script src="assets/js/_dataTables.buttons.min.js"></script>
    <script src="assets/js/_jszip_3.10.1_jszip.min.js"></script>
    <script src="assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
    <script src="assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
    <script src="assets/js/_js_buttons.html5.min.js"></script>
    <script src=" assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <script src="assets/js/footerscript.js"></script>
  
  <script>
        $(document).ready(function() {
            <?php if ($_GET['route'] == '') : ?>
                show_GUIDE_INVOICE_LIST();
            <?php elseif (($_GET['route'] == 'preview' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'guide_invoice_details') : ?>
                show_GUIDE_INVOICE_DETAIL('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
            <?php endif; ?>
        });

        function show_GUIDE_INVOICE_LIST() {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_guide_invoice_list.php?type=show_form',
                success: function(response) {
                    $('#showINVOICELIST').html(response);
					$('#showINVOICEDETAIL').html('');
                }
            });
        }

        function show_GUIDE_INVOICE_DETAIL(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_guide_invoice_details.php?type=show_form',
                data: {
                    ID: ID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#showINVOICELIST').html('');
                    $('#showINVOICEDETAIL').html(response);

                }
            });
        }
  </script>
</body>
</html>


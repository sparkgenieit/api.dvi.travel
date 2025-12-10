<?php
/*
 * JACKUS - An In-house Framework for TDS Apps
 *
 * Author: Touchmark Descience Private Limited.
 * https://touchmarkdes.com
 * Version 5.0.1
 * Copyright (c) 2010-2022 Touchmark De`Science
 *
 */
include_once 'jackus.php';
admin_reguser_protect();
if ($_GET['regen'] == 'y') :
    session_regenerate_id(TRUE);
    $itinerary_session_id = session_id();
else :
    $itinerary_session_id = session_id();
endif;
require_once('check_restriction.php');
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $itinerary_session_id . ' ' . $_SITETITLE; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Icons -->
    <link rel="stylesheet" href="assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <!-- <link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" /> -->
    <!-- <link rel="stylesheet" href="assets/vendor/libs/mapbox-gl/mapbox-gl.css" /> -->

    <!-- Page CSS -->
    <!-- <link rel="stylesheet" href="assets/vendor/css/pages/app-logistics-fleet.css" /> -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />

    <!-- Row Group CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css">
    <!-- Form Validation -->
    <!-- <link rel="stylesheet" href="assets/vendor/libs/%40form-validation/umd/styles/index.min.css" /> -->
    <link rel="stylesheet" href="assets/css/_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/_1.13.6_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/parsley_validation.css">
    <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
    <!-- <link rel="stylesheet" href="assets/vendor/libs/bs-stepper/bs-stepper.css" /> -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/itineary_custom_style.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/app-chat.css">
    <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="assets/vendor/libs/tagify/tagify.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/ui-carousel.css" />
    <!-- <link rel="stylesheet" href="assets/vendor/libs/swiper/swiper.css" /> -->
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
    <link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <style>
        #loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        <?php if ($_GET['route'] == '' && $_GET['formtype'] == '') : ?>.bs-tooltip-auto[x-placement^=top] .arrow.active,
        .bs-tooltip-top .arrow.active {
            bottom: 0;
        }

        .tooltip-inner {
            max-width: 500px;
            width: 240px;
            background-color: #fff5fd;
            color: #6f6b7d;
            border-radius: 8px;
            padding: 10px;
            border: #6f6b7d 1px solid;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.12), 0 2px 2px rgba(0, 0, 0, 0.12);
        }

        <?php endif ?>.text-primary {
            color: #e900e5 !important;
        }
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Menu -->
                <?php include_once('public/__sidebar.php'); ?>
                <!-- / Menu -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y pt-3">

                        <span id="showITINERARYLIST"></span>
                        <span id="showITINEARYSTEP1"></span>
                        <span id="showITINEARYSTEP2"></span>

                        <div class="loader-overlay" id="show_itineary_loader">
                            <div class="loader" id="loader">
                                <div class="text-center">
                                    <!-- Lottie container for the animation -->
                                    <div id="lottie-loader" style="width:450px; height:200px; margin: 0 auto;"></div>
                                    <h4 class="mt-3">We are planning to prepare your itinerary...</h4>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- build:js assets/vendor/js/core.js -->
        </div>
    </div>

    <div id="spinner"></div>

    <div class="modal fade" id="MODALINFODATA" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
            <div class="modal-content p-3">
                <div class="modal-body receiving-modal-info-form-data">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="MULTIPLEROOMMODALINFODATA" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
            <div class="modal-content p-3">
                <div class="modal-body receiving-multiple-room-modal-info-form-data">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="HOTSPOTCONFLICTMODALINFODATA" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-enable-otp modal-dialog-top">
            <div class="modal-content p-3">
                <div class="modal-body receiving-hotspot-conflict-modal-info-form-data">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="VIEWMODALINFODATA" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-simple modal-dialog-centered modal-refer-and-earn">
            <div class="modal-content p-0">
                <div class="modal-body p-0 receiving-view-modal-info-form-data">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="VEHICLEMODALINFODATA" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-simple modal-refer-and-earn">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body receiving-vehicle-modal-info-form-data">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="GALLERYMODALINFODATA" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg receiving-gallery-modal-info-form-data" role="document">
        </div>
    </div>

    <div class="modal fade" id="hotelADDAMENITIESMODALINFODATA" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-simple modal-dialog-centered modal-refer-and-earn">
            <div class="modal-content p-0 receiving-hotel-amenities-modal-info-form-data">
            </div>
        </div>
    </div>

    <div class="modal fade" id="OPTIMIZEMODALINFODATA" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
            <div class="modal-content p-3">
                <div class="modal-body receiving-route-optimizing-modal-info-form-data">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="DISTANCELIMITRESTRICTIONMODAL" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
            <div class="modal-content p-3">
                <div class="modal-body receiving-distancelimit-restriction-modal-info-form-data">
                    <div class="row">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="42px" height="42px" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                <g>
                                    <path d="M313.107 201.775c0-53.378-43.439-96.805-96.834-96.805s-96.805 43.426-96.805 96.805 43.427 96.833 96.805 96.833 96.834-43.439 96.834-96.833zm-96.834 66.833c-36.836 0-66.805-29.981-66.805-66.833s29.969-66.805 66.805-66.805 66.834 29.968 66.834 66.805-29.981 66.833-66.834 66.833zm266.099 81.57-11.465-7.066-7.501-18.07 3.123-13.095a15.001 15.001 0 0 0-3.984-14.086l-20.295-20.295a15.004 15.004 0 0 0-14.063-3.99l-13.113 3.106-15.965-6.61c7.259-22.906 10.94-45.884 10.94-68.297C410.049 94.927 323.121 8 216.274 8S22.499 94.927 22.499 201.775c0 52.464 19.354 105.31 59.169 161.557 33.204 46.908 75.147 88.168 108.851 121.321a4106.721 4106.721 0 0 1 15.178 14.983c2.926 2.91 6.751 4.364 10.577 4.364s7.655-1.456 10.582-4.369c4.916-4.893 10.07-9.964 15.4-15.208 7.673-7.549 15.907-15.657 24.363-24.152l16.772 16.773a15 15 0 0 0 14.091 3.983l13.071-3.122 18.059 7.477 7.062 11.479a14.998 14.998 0 0 0 12.775 7.14h28.714a14.998 14.998 0 0 0 12.775-7.14L397 485.382l18.061-7.478 13.103 3.124a14.992 14.992 0 0 0 14.094-3.992l20.295-20.324a15 15 0 0 0 3.976-14.084l-3.121-13.063 7.5-18.067 11.465-7.066a14.998 14.998 0 0 0 7.13-12.77v-28.714a15 15 0 0 0-7.13-12.77zm-261.156 112.86c-1.666 1.639-3.315 3.261-4.945 4.867l-4.714-4.639C144.43 397.234 52.499 306.801 52.499 201.775 52.499 111.469 125.968 38 216.273 38s163.775 73.469 163.775 163.775c0 15.976-2.197 32.358-6.529 48.834h-25.071a15.002 15.002 0 0 0-12.77 7.129l-7.062 11.458-18.076 7.484-13.081-3.104a15.004 15.004 0 0 0-14.062 3.981l-20.323 20.295a15 15 0 0 0-3.991 14.093l3.124 13.102-7.479 18.061-11.479 7.063a14.998 14.998 0 0 0-7.14 12.775v28.714a14.998 14.998 0 0 0 7.14 12.775l11.479 7.063 5.259 12.701c-13.378 13.847-26.761 27.02-38.772 38.838zm238.286-79.752-8.461 5.215a14.989 14.989 0 0 0-5.983 7.019l-11.225 27.042a14.992 14.992 0 0 0-.736 9.235l2.305 9.648-8.442 8.454-9.666-2.305a14.997 14.997 0 0 0-9.218.732l-27.041 11.196a14.993 14.993 0 0 0-7.037 5.999L368.781 474h-11.948l-5.217-8.479a14.998 14.998 0 0 0-7.037-5.999l-27.042-11.196a15.012 15.012 0 0 0-9.223-.731l-9.642 2.303-8.46-8.459 2.304-9.642a15 15 0 0 0-.731-9.224l-11.197-27.042a15.006 15.006 0 0 0-5.998-7.037l-8.479-5.217v-11.948l8.479-5.217a14.996 14.996 0 0 0 5.998-7.037l11.197-27.042a14.997 14.997 0 0 0 .732-9.218l-2.305-9.666 8.464-8.453 9.659 2.292c3.07.729 6.288.471 9.201-.736l27.042-11.196a15.002 15.002 0 0 0 7.031-5.988l5.215-8.46h11.964l5.215 8.46a15.002 15.002 0 0 0 7.031 5.988l27.041 11.196a14.991 14.991 0 0 0 9.195.737l9.686-2.294 8.446 8.447-2.307 9.673a14.998 14.998 0 0 0 .737 9.23l11.225 27.042a14.994 14.994 0 0 0 5.983 7.019l8.461 5.215v11.964zm-96.682-62.948c-31.419 0-56.979 25.548-56.979 56.952s25.561 56.98 56.979 56.98 56.951-25.561 56.951-56.98-25.548-56.952-56.951-56.952zm0 83.932c-14.877 0-26.979-12.103-26.979-26.98s12.103-26.952 26.979-26.952 26.951 12.09 26.951 26.952-12.09 26.98-26.951 26.98z" fill="#ff9f43" opacity="1" data-original="#000000" class=""></path>
                                </g>
                            </svg>
                        </div>
                        <h6 class="mt-3 mb-2 text-center">Distance Limit Exceeds !!!!!!</h6>
                        <p class="text-center">The Location you have selected will not meet the Per day travel limit for
                            the last day.<br /> <br> Please select another location which satisfy our conditions.</p>
                        <input type="hidden" id="hidden_day_no" value="" />
                        <div class="text-center pb-0">
                            <button type="button" onclick="closeDistanceLimitRestrictionModal();" class="btn btn-success">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal to fetch customer details for itinerary confirmation-->
    <div class="modal fade" id="VIEWCUSTOMERDETAILSMODAL" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-simple modal-dialog-centered modal-refer-and-earn">
            <div class="modal-content p-0">
                <div class="modal-body p-0 receiving-customer-details-form-data">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="NOROUTESUGGESTIONSMODAL" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
            <div class="modal-content p-3">
                <div class="modal-body receiving-no-route-modal-info-form-data">
                    <div class="row">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="42px" height="42px" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                <g>
                                    <path d="M313.107 201.775c0-53.378-43.439-96.805-96.834-96.805s-96.805 43.426-96.805 96.805 43.427 96.833 96.805 96.833 96.834-43.439 96.834-96.833zm-96.834 66.833c-36.836 0-66.805-29.981-66.805-66.833s29.969-66.805 66.805-66.805 66.834 29.968 66.834 66.805-29.981 66.833-66.834 66.833zm266.099 81.57-11.465-7.066-7.501-18.07 3.123-13.095a15.001 15.001 0 0 0-3.984-14.086l-20.295-20.295a15.004 15.004 0 0 0-14.063-3.99l-13.113 3.106-15.965-6.61c7.259-22.906 10.94-45.884 10.94-68.297C410.049 94.927 323.121 8 216.274 8S22.499 94.927 22.499 201.775c0 52.464 19.354 105.31 59.169 161.557 33.204 46.908 75.147 88.168 108.851 121.321a4106.721 4106.721 0 0 1 15.178 14.983c2.926 2.91 6.751 4.364 10.577 4.364s7.655-1.456 10.582-4.369c4.916-4.893 10.07-9.964 15.4-15.208 7.673-7.549 15.907-15.657 24.363-24.152l16.772 16.773a15 15 0 0 0 14.091 3.983l13.071-3.122 18.059 7.477 7.062 11.479a14.998 14.998 0 0 0 12.775 7.14h28.714a14.998 14.998 0 0 0 12.775-7.14L397 485.382l18.061-7.478 13.103 3.124a14.992 14.992 0 0 0 14.094-3.992l20.295-20.324a15 15 0 0 0 3.976-14.084l-3.121-13.063 7.5-18.067 11.465-7.066a14.998 14.998 0 0 0 7.13-12.77v-28.714a15 15 0 0 0-7.13-12.77zm-261.156 112.86c-1.666 1.639-3.315 3.261-4.945 4.867l-4.714-4.639C144.43 397.234 52.499 306.801 52.499 201.775 52.499 111.469 125.968 38 216.273 38s163.775 73.469 163.775 163.775c0 15.976-2.197 32.358-6.529 48.834h-25.071a15.002 15.002 0 0 0-12.77 7.129l-7.062 11.458-18.076 7.484-13.081-3.104a15.004 15.004 0 0 0-14.062 3.981l-20.323 20.295a15 15 0 0 0-3.991 14.093l3.124 13.102-7.479 18.061-11.479 7.063a14.998 14.998 0 0 0-7.14 12.775v28.714a14.998 14.998 0 0 0 7.14 12.775l11.479 7.063 5.259 12.701c-13.378 13.847-26.761 27.02-38.772 38.838zm238.286-79.752-8.461 5.215a14.989 14.989 0 0 0-5.983 7.019l-11.225 27.042a14.992 14.992 0 0 0-.736 9.235l2.305 9.648-8.442 8.454-9.666-2.305a14.997 14.997 0 0 0-9.218.732l-27.041 11.196a14.993 14.993 0 0 0-7.037 5.999L368.781 474h-11.948l-5.217-8.479a14.998 14.998 0 0 0-7.037-5.999l-27.042-11.196a15.012 15.012 0 0 0-9.223-.731l-9.642 2.303-8.46-8.459 2.304-9.642a15 15 0 0 0-.731-9.224l-11.197-27.042a15.006 15.006 0 0 0-5.998-7.037l-8.479-5.217v-11.948l8.479-5.217a14.996 14.996 0 0 0 5.998-7.037l11.197-27.042a14.997 14.997 0 0 0 .732-9.218l-2.305-9.666 8.464-8.453 9.659 2.292c3.07.729 6.288.471 9.201-.736l27.042-11.196a15.002 15.002 0 0 0 7.031-5.988l5.215-8.46h11.964l5.215 8.46a15.002 15.002 0 0 0 7.031 5.988l27.041 11.196a14.991 14.991 0 0 0 9.195.737l9.686-2.294 8.446 8.447-2.307 9.673a14.998 14.998 0 0 0 .737 9.23l11.225 27.042a14.994 14.994 0 0 0 5.983 7.019l8.461 5.215v11.964zm-96.682-62.948c-31.419 0-56.979 25.548-56.979 56.952s25.561 56.98 56.979 56.98 56.951-25.561 56.951-56.98-25.548-56.952-56.951-56.952zm0 83.932c-14.877 0-26.979-12.103-26.979-26.98s12.103-26.952 26.979-26.952 26.951 12.09 26.951 26.952-12.09 26.98-26.951 26.98z" fill="#ff9f43" opacity="1" data-original="#000000" class=""></path>
                                </g>
                            </svg>
                        </div>
                        <h6 class="mt-3 mb-2 text-center">No Default Routes Found !!!!!!</h6>
                        <p class="text-center">The Location you have selected will not have route suggestions.<br /> <br> Please add suggested routes before proceeding.</p>
                        <input type="hidden" id="hidden_day_no" value="" />
                        <div class="text-center pb-0">
                            <button type="button" data-bs-dismiss="modal" class="btn btn-success">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="NOMATCHINGROUTESMODAL" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
            <div class="modal-content p-3">
                <div class="modal-body receiving-no-route-modal-info-form-data">
                    <div class="row">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="42px" height="42px" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                <g>
                                    <path d="M313.107 201.775c0-53.378-43.439-96.805-96.834-96.805s-96.805 43.426-96.805 96.805 43.427 96.833 96.805 96.833 96.834-43.439 96.834-96.833zm-96.834 66.833c-36.836 0-66.805-29.981-66.805-66.833s29.969-66.805 66.805-66.805 66.834 29.968 66.834 66.805-29.981 66.833-66.834 66.833zm266.099 81.57-11.465-7.066-7.501-18.07 3.123-13.095a15.001 15.001 0 0 0-3.984-14.086l-20.295-20.295a15.004 15.004 0 0 0-14.063-3.99l-13.113 3.106-15.965-6.61c7.259-22.906 10.94-45.884 10.94-68.297C410.049 94.927 323.121 8 216.274 8S22.499 94.927 22.499 201.775c0 52.464 19.354 105.31 59.169 161.557 33.204 46.908 75.147 88.168 108.851 121.321a4106.721 4106.721 0 0 1 15.178 14.983c2.926 2.91 6.751 4.364 10.577 4.364s7.655-1.456 10.582-4.369c4.916-4.893 10.07-9.964 15.4-15.208 7.673-7.549 15.907-15.657 24.363-24.152l16.772 16.773a15 15 0 0 0 14.091 3.983l13.071-3.122 18.059 7.477 7.062 11.479a14.998 14.998 0 0 0 12.775 7.14h28.714a14.998 14.998 0 0 0 12.775-7.14L397 485.382l18.061-7.478 13.103 3.124a14.992 14.992 0 0 0 14.094-3.992l20.295-20.324a15 15 0 0 0 3.976-14.084l-3.121-13.063 7.5-18.067 11.465-7.066a14.998 14.998 0 0 0 7.13-12.77v-28.714a15 15 0 0 0-7.13-12.77zm-261.156 112.86c-1.666 1.639-3.315 3.261-4.945 4.867l-4.714-4.639C144.43 397.234 52.499 306.801 52.499 201.775 52.499 111.469 125.968 38 216.273 38s163.775 73.469 163.775 163.775c0 15.976-2.197 32.358-6.529 48.834h-25.071a15.002 15.002 0 0 0-12.77 7.129l-7.062 11.458-18.076 7.484-13.081-3.104a15.004 15.004 0 0 0-14.062 3.981l-20.323 20.295a15 15 0 0 0-3.991 14.093l3.124 13.102-7.479 18.061-11.479 7.063a14.998 14.998 0 0 0-7.14 12.775v28.714a14.998 14.998 0 0 0 7.14 12.775l11.479 7.063 5.259 12.701c-13.378 13.847-26.761 27.02-38.772 38.838zm238.286-79.752-8.461 5.215a14.989 14.989 0 0 0-5.983 7.019l-11.225 27.042a14.992 14.992 0 0 0-.736 9.235l2.305 9.648-8.442 8.454-9.666-2.305a14.997 14.997 0 0 0-9.218.732l-27.041 11.196a14.993 14.993 0 0 0-7.037 5.999L368.781 474h-11.948l-5.217-8.479a14.998 14.998 0 0 0-7.037-5.999l-27.042-11.196a15.012 15.012 0 0 0-9.223-.731l-9.642 2.303-8.46-8.459 2.304-9.642a15 15 0 0 0-.731-9.224l-11.197-27.042a15.006 15.006 0 0 0-5.998-7.037l-8.479-5.217v-11.948l8.479-5.217a14.996 14.996 0 0 0 5.998-7.037l11.197-27.042a14.997 14.997 0 0 0 .732-9.218l-2.305-9.666 8.464-8.453 9.659 2.292c3.07.729 6.288.471 9.201-.736l27.042-11.196a15.002 15.002 0 0 0 7.031-5.988l5.215-8.46h11.964l5.215 8.46a15.002 15.002 0 0 0 7.031 5.988l27.041 11.196a14.991 14.991 0 0 0 9.195.737l9.686-2.294 8.446 8.447-2.307 9.673a14.998 14.998 0 0 0 .737 9.23l11.225 27.042a14.994 14.994 0 0 0 5.983 7.019l8.461 5.215v11.964zm-96.682-62.948c-31.419 0-56.979 25.548-56.979 56.952s25.561 56.98 56.979 56.98 56.951-25.561 56.951-56.98-25.548-56.952-56.951-56.952zm0 83.932c-14.877 0-26.979-12.103-26.979-26.98s12.103-26.952 26.979-26.952 26.951 12.09 26.951 26.952-12.09 26.98-26.951 26.98z" fill="#ff9f43" opacity="1" data-original="#000000" class=""></path>
                                </g>
                            </svg>
                        </div>
                        <h6 class="mt-3 mb-2 text-center">No Matching Routes Found !!!!!!</h6>
                        <p class="text-center">The Location you have selected will not have matching route suggestions.<br /> <br> Please add matching routes before proceeding.</p>
                        <input type="hidden" id="hidden_day_no" value="" />
                        <div class="text-center pb-0">
                            <button type="button" data-bs-dismiss="modal" class="btn btn-success">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="buy-now">
        <a class="btn btn-primary btn-buy-now text-white" id="scrollToTopButton">
            <span class="align-middle"> <i class="ti ti-arrow-up"></i></span>
        </a>
    </div>

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/js/extended-ui-perfect-scrollbar.js"></script>
    <!-- <script src="assets/vendor/libs/i18n/i18n.js"></script> -->
    <script src="assets/vendor/js/menu.js"></script>

    <!-- endbuild -->
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="assets/vendor/libs/tagify/tagify.js"></script>

    <!-- Form Validation -->
    <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
    <!-- Vendors JS -->
    <script src="assets/js/_jquery.dataTables.min.js"></script>
    <script src="assets/js/_dataTables.buttons.min.js"></script>
    <!-- <script src="assets/js/_jszip_3.10.1_jszip.min.js"></script>
    <script src="assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
    <script src="assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
    <script src="assets/js/_js_buttons.html5.min.js"></script> -->
    <script src="assets/js/selectize/selectize.min.js"></script>
    <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <script src="assets/js/footerscript.js"></script>
    <!-- <script src="assets/vendor/libs/dropzone/dropzone.js"></script>
    <script src="assets/vendor/libs/fullcalendar/fullcalendar.js"></script> -->
    <script src="assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <script src="assets/js/lottie.min.js"></script>
    <script src="./assets/vendor/js/dropdown-hover.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <script>
        // Initialize the Lottie animation
        lottie.loadAnimation({
            container: document.getElementById('lottie-loader'), // The ID of the div to hold the animation
            renderer: 'svg', // Render as SVG
            loop: true, // Make it loop
            autoplay: true, // Auto-start the animation
            path: 'assets/img/json/Travel.json' // Path to your Lottie animation JSON file
        });

        var scrollToTopButton = document.getElementById("scrollToTopButton");
        // Add click event listener to the button
        scrollToTopButton.addEventListener("click", function() {
            // Scroll the page to the top smoothly
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });

        $(document).ready(function() {
            <?php if (($_GET['route'] == '') && $_GET['formtype'] == '') : ?>
                showITINERARYLIST();
                $('body').tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
                $(function() {
                    $('[data-toggle="tooltip"]').tooltip()
                })
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'basic_info') : ?>
                showITINERARYFORMSTEP1(<?= $_GET['id']; ?>);
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'generate_itinerary') : ?>
                showITINERARYFORMSTEP2(<?= $_GET['id']; ?>);
            <?php endif; ?>
        });

        function showITINERARYLIST() {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/ajax_latest_itinerary_list.php?type=show_form',
                success: function(response) {
                    $('#showITINERARYLIST').html(response);
                }
            });
        }

        function showITINERARYFORMSTEP1(ID) {
            $.ajax({
                type: "POST",
                url: "engine/ajax/ajax_latest_itineary_step1_form.php?type=show_form",
                data: {
                    _ID: ID
                },
                success: function(response) {
                    $('#showITINEARYSTEP2').html('')
                    $('#showITINEARYSTEP1').html(response);
                }
            });
        }

        function showITINERARYFORMSTEP2(ID) {
            $.ajax({
                type: "POST",
                url: "engine/ajax/ajax_latest_itineary_step2_form.php?type=show_form&selected_group_type=1",
                data: {
                    _ID: ID
                },
                success: function(response) {
                    $('#showITINEARYSTEP1').html('')
                    $('#showITINEARYSTEP2').html(response);
                }
            });
        }
    </script>

</body>

</html>
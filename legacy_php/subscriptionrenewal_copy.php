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

include_once('jackus.php');
admin_reguser_protect();

if ($logged_agent_id != '' && $logged_agent_id != '0') :

    $selected_query = sqlQUERY_LABEL("SELECT `subscription_plan_title`, `subscription_amount`, `validity_start`, `validity_end` FROM `dvi_agent_subscribed_plans` WHERE `deleted` = '0' AND `status`='1' AND `agent_ID` = '$logged_agent_id' ORDER BY `agent_subscribed_plan_ID` DESC LIMIT 1") or die("#-getAGENTDETAILS: Getting Agent Id: " . sqlERROR_LABEL());

    while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
        $subscription_plan_title = $fetch_data['subscription_plan_title'];
        $subscription_amount = $fetch_data['subscription_amount'];
        $validity_start = $fetch_data['validity_start'];
        $validity_end = $fetch_data['validity_end'];

        // Get the current date
        $current_date = strtotime(date('Y-m-d'));
        // Get the validity end date
        $validity_end_date = strtotime(date('Y-m-d', strtotime($validity_end)));

        // Calculate the difference in days
        $difference_in_seconds = $validity_end_date - $current_date;
        $days_remaining = floor($difference_in_seconds / (60 * 60 * 24));

        if ($difference_in_seconds < 0) :
            $message = "Your $subscription_plan_title plan has expired.";
        elseif ($days_remaining == 0) :
            $message = "Your subscription expires today.";
            $url = 'dashboard.php'; // Set the URL you want to redirect to
            echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        elseif ($days_remaining == 1) :
            $message = "Your subscription expires tomorrow.";
            $url = 'dashboard.php'; // Set the URL you want to redirect to
            echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        else :
            $message = "Your $subscription_plan_title plan will expire in $days_remaining days.";
            $url = 'dashboard.php'; // Set the URL you want to redirect to
            echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        endif;
    endwhile;
?>
<?php
endif;
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

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

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">

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
    <link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="assets/vendor/libs/tagify/tagify.css" />
    <!-- Row Group CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css">
    <!-- Form Validation -->
    <link rel="stylesheet" href="assets/css/_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/_1.13.6_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/parsley_validation.css">
    <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/bs-stepper/bs-stepper.css" />
    <link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
    <link rel="stylesheet" href="assets/vendor/libs/dropzone/dropzone.css">

    <link rel="stylesheet" href="assets/vendor/libs/swiper/swiper.css" />

    <!-- Page CSS -->
    <!-- <link rel="stylesheet" href="assets/vendor/css/pages/ui-carousel.css" /> -->
    <script src="assets/vendor/js/template-customizer.js"></script>

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <style>
        .body {
            background-color: #f0f0ff91 !important;
        }

        .text-blue {
            color: #001255;
        }

        .text-red {
            color: #d72323;
        }

        .recommended-badge {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
            font-size: 13px;
            padding: 3px 24px 0px 25px;
            background: #d72323;
            color: #fff;
            border-radius: 0 0 25px 25px;
        }

        .recommended {
            border: 1px solid #d72323 !important;
        }

        .choose-btn {
            border: 1px solid #d72323;
            color: #d72323 !important;
            font-size: 14px;
            font-weight: 600;
            padding: 6px 5px;
            background: #fff;
            border-radius: 5px;
        }

        .choose-btn:hover {
            background-color: #d72323;
            color: #fff !important;
        }

        .choose-btn-recommened {
            border: 1px solid #d72323;
            color: #fff !important;
            font-size: 14px;
            font-weight: 600;
            padding: 6px 5px;
            background: #d72323;
            border-radius: 5px;
        }

        .card {
            border: 1px solid #d2d2d2;
        }

        .card:hover {
            box-shadow: 0 0 11px rgba(33, 33, 33, .2) !important;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #001255;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }

        .swiper .swiper-slide {
            padding: 2rem 0;
            background-size: cover;
        }

        #swiper-multiple-slides,
        #swiper-3d-coverflow-effect {
            height: auto !important;
        }
    </style>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar ">
        <div class="layout-container">

            <!-- Layout container -->
            <div class="mx-auto text-center">

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <img src="../head/assets/img/logo-preview.png" width="80px" class="mb-4" />
                        <div>
                            <h4 class="mb-1 text-blue">You have reached the limit of your plan !</h4>
                            <p>Don't miss out on the benefits of your subscription. Renew today to stay connected.</p>
                        </div>
                        <h5 class="mt-4">Choose the Plan</h5>
                        <div class="row mx-5">
                            <div class="col-12 mb-4">
                                <div class="swiper" id="swiper-multiple-slides">
                                    <div class="swiper-wrapper"><?php
                                                                $select_subscribed_plan_details = sqlQUERY_LABEL("SELECT asp.`agent_subscription_plan_ID`, asp.`agent_subscription_plan_title`, asp.`itinerary_allowed`, asp.`subscription_amount`, asp.`admin_count`, asp.`staff_count`, asp.`additional_charge_for_per_staff`, asp.`per_itinerary_cost`, asp.`validity_in_days`, asp.`subscription_notes`, asb.`agent_subscribed_plan_ID`, asb.`agent_ID`, asb.`additional_staff_count`, asb.`additional_staff_charge` FROM `dvi_agent_subscribed_plans` asb LEFT JOIN `dvi_agent_subscription_plan` asp ON asb.`subscription_plan_ID` = asp.`agent_subscription_plan_ID` WHERE asb.`status` = 1 AND asb.`deleted` = 0 AND asp.`status` = 1 AND asp.`deleted` = 0 AND asb.`agent_ID`= $logged_agent_id") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
                                                                $count_subscribed_plan_details = sqlNUMOFROW_LABEL($select_subscribed_plan_details);
                                                                if ($count_subscribed_plan_details > 0) :
                                                                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_subscribed_plan_details)) :
                                                                        $agent_subscription_plan_ID = $fetch_data['agent_subscription_plan_ID'];
                                                                        $agent_subscribed_plan_ID = $fetch_data['agent_subscribed_plan_ID'];
                                                                        $agent_ID = $fetch_data['agent_ID'];
                                                                        $agent_subscription_plan_title = $fetch_data['agent_subscription_plan_title'];
                                                                        $itinerary_allowed = $fetch_data['itinerary_allowed'];
                                                                        $subscription_amount = $fetch_data['subscription_amount'];
                                                                        $admin_count = $fetch_data['admin_count'];
                                                                        $staff_count = $fetch_data['staff_count'];
                                                                        $additional_charge_for_per_staff = $fetch_data['additional_charge_for_per_staff'];
                                                                        $per_itinerary_cost = $fetch_data['per_itinerary_cost'];
                                                                        $validity_in_days = $fetch_data['validity_in_days'];
                                                                        $subscription_notes = $fetch_data['subscription_notes'];
                                                                        $additional_staff_count = $fetch_data['additional_staff_count'];
                                                                        $additional_staff_charge = $fetch_data['additional_staff_charge'];

                                                                        $recommended = 'recommended';
                                                                        $recommended_badge = '<span class="recommended-badge">Renew Existing Plan</span>';
                                                                        $recommebded_button = 'choose-btn-recommened';
                                                                ?>
                                                <div class="swiper-slide">
                                                    <div class="card <?= $recommended; ?> p-4 mx-1">
                                                        <?= $recommended_badge; ?>
                                                        <h5 class="mt-3"><?= $agent_subscription_plan_title; ?></h5>
                                                        <h3 class="text-center">
                                                            <span class="text-red"> <?= general_currency_symbol; ?><?= number_format($subscription_amount, 0); ?></span><span class="fs-6"> / days (<?= $validity_in_days; ?>)</span>
                                                        </h3>
                                                        <div class="px-4">
                                                            <p class="mb-2"><i class="ti fs-5 ti-check"></i>Allowed Itinerary creation <span class="text-blue"> (<?= $itinerary_allowed; ?>)</span></p>
                                                            <p class="mb-2"><i class="ti  fs-5 ti-check"></i>Per Itinerary creating cost<span class="text-blue"> (<?= general_currency_symbol; ?><?= number_format($per_itinerary_cost, 2); ?>)</span></p>
                                                            <!-- <p class="mb-2"><i class="ti  fs-5 ti-check"></i>Joining Bonus<span class="text-blue"> (<?= general_currency_symbol; ?><?= number_format($joining_bonus, 2); ?>)</span></p> -->
                                                            <p class="mb-2"><i class="ti  fs-5 ti-check"></i>Staff login count<span class="text-blue"> (<?= $staff_count; ?>)</span></p>
                                                            <p class="mb-2"><i class="ti  fs-5 ti-check"></i>Per cost staff for extra login<span class="text-blue"> (<?= general_currency_symbol; ?><?= number_format($additional_charge_for_per_staff, 2); ?>)</span></p>
                                                        </div>
                                                        <a type="button" target="_blank" onclick="subscriptionID(<?= $agent_subscription_plan_ID; ?>);" class="<?= $recommebded_button; ?> m-4">Choose Plan</a>
                                                    </div>
                                                </div>
                                        <?php endwhile;
                                                                endif; ?>
                                        <?php
                                        $select_subscription = sqlQUERY_LABEL("SELECT `agent_subscription_plan_ID`, `agent_subscription_plan_title`, `itinerary_allowed`, `subscription_type`, `subscription_amount`, `joining_bonus`, `admin_count`, `staff_count`, `additional_charge_for_per_staff`, `per_itinerary_cost`, `validity_in_days`, `recommended_status`, `subscription_notes` FROM `dvi_agent_subscription_plan` WHERE `deleted` = '0' AND `status` = '1' AND `subscription_type` = '1' ORDER BY `subscription_amount` ASC") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
                                        $total_no_of_subscription_plan = sqlNUMOFROW_LABEL($select_subscription);
                                        while ($fetch_data = sqlFETCHARRAY_LABEL($select_subscription)) :
                                            $agent_subscription_plan_ID = $fetch_data['agent_subscription_plan_ID'];
                                            $agent_subscription_plan_title = $fetch_data['agent_subscription_plan_title'];
                                            $subscription_amount = $fetch_data['subscription_amount'];
                                            $validity_in_days = $fetch_data['validity_in_days'];
                                            $itinerary_allowed = $fetch_data['itinerary_allowed'];
                                            $per_itinerary_cost = $fetch_data['per_itinerary_cost'];
                                            $joining_bonus = $fetch_data['joining_bonus'];
                                            $recommended_status = $fetch_data['recommended_status'];
                                            $staff_count = $fetch_data['staff_count'];
                                            $subscription_notes = $fetch_data['subscription_notes'];
                                            $additional_charge_for_per_staff = $fetch_data['additional_charge_for_per_staff'];

                                            $recommended = '';
                                            $recommended_badge = '';
                                            $recommebded_button = 'choose-btn';

                                        ?>
                                            <div class="swiper-slide">
                                                <div class="card <?= $recommended; ?> p-4 mx-1">
                                                    <?= $recommended_badge; ?>
                                                    <h5 class="mt-3"><?= $agent_subscription_plan_title; ?></h5>
                                                    <h3 class="text-center">
                                                        <span class="text-red"> <?= general_currency_symbol; ?><?= number_format($subscription_amount, 0); ?></span><span class="fs-6"> / days (<?= $validity_in_days; ?>)</span>
                                                    </h3>
                                                    <div class="px-4">
                                                        <p class="mb-2"><i class="ti fs-5 ti-check"></i>Allowed Itinerary creation <span class="text-blue"> (<?= $itinerary_allowed; ?>)</span></p>
                                                        <p class="mb-2"><i class="ti  fs-5 ti-check"></i>Per Itinerary creating cost<span class="text-blue"> (<?= general_currency_symbol; ?><?= number_format($per_itinerary_cost, 2); ?>)</span></p>
                                                        <p class="mb-2"><i class="ti  fs-5 ti-check"></i>Joining Bonus<span class="text-blue"> (<?= general_currency_symbol; ?><?= number_format($joining_bonus, 2); ?>)</span></p>
                                                        <p class="mb-2"><i class="ti  fs-5 ti-check"></i>Staff login count<span class="text-blue"> (<?= $staff_count; ?>)</span></p>
                                                        <p class="mb-2"><i class="ti  fs-5 ti-check"></i>Per cost staff for extra login<span class="text-blue"> (<?= general_currency_symbol; ?><?= number_format($additional_charge_for_per_staff, 2); ?>)</span></p>
                                                    </div>
                                                    <a type="button" target="_blank" onclick="subscriptionID(<?= $agent_subscription_plan_ID; ?>);" class="<?= $recommebded_button; ?> m-4">Choose Plan</a>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                    <div class="swiper-pagination"></div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <!-- / Content -->
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <div class="modal fade" id="showDELETEMODAL" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content receiving-delete-form-data">
            </div>
        </div>
    </div>

    <div class="modal-onboarding modal fade animate__animated" id="showSWIPERGALLERYMODAL" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 999999;">
        <div class="modal-dialog modal-md modal-dialog-center">
            <div class="modal-content receiving-swiper-room-form-data">
            </div>
        </div>
    </div>

    <div class="modal fade" id="showPRICEBOOKFORM" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-top">
            <div class="modal-content">
                <div class="modal-body show-pricebook-form-data">
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <!-- endbuild -->
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="assets/vendor/libs/tagify/tagify.js"></script>

    <!-- Form Validation -->
    <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
    <!-- Vendors JS -->
    <script src="assets/js/_jquery.dataTables.min.js"></script>
    <script src="assets/js/_dataTables.buttons.min.js"></script>
    <script src="assets/js/_jszip_3.10.1_jszip.min.js"></script>
    <script src="assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
    <script src="assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
    <script src="assets/js/_js_buttons.html5.min.js"></script>
    <script src="assets/js/selectize/selectize.min.js"></script>
    <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <!-- Correctly include Swiper JS -->
    <script src="assets/js/swiper-bundle.min.js"></script>
    <!-- <script src="assets/js/ui-carousel.js"></script> -->
    <script src="assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <!-- endbuild -->

    <!-- Main JS -->
    <script src="assets/js/footerscript.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the total number of subscription plans from PHP
            var totalNoOfSubscriptionPlan = <?= $total_no_of_subscription_plan; ?>;

            // Calculate slidesPerView based on the total number of subscription plans
            var slidesPerView = Math.min(totalNoOfSubscriptionPlan, 3); // Ensure we don't exceed 2 slides per view

            // Initialize Swiper
            var swiper = new Swiper('#swiper-multiple-slides', {
                slidesPerView: slidesPerView,
                spaceBetween: 30,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    // when window width is >= 320px
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 20
                    },
                    // when window width is >= 480px
                    480: {
                        slidesPerView: slidesPerView,
                        spaceBetween: 30
                    },
                    // when window width is >= 640px
                    640: {
                        slidesPerView: slidesPerView,
                        spaceBetween: 40
                    }
                }
            });
        });
    </script>

</body>

</html>
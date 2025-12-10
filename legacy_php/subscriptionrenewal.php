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

        if ($days_remaining > 0) :
            $message = "Your subscription expires today.";
            $url = 'dashboard.php'; // Set the URL you want to redirect to
            echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        elseif ($difference_in_seconds < 0) :
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

    <!-- Form Validation -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <!-- <link rel="stylesheet" href="assets/vendor/libs/dropzone/dropzone.css"> -->
    <link rel="stylesheet" href="assets/vendor/libs/swiper/swiper.css" />
    <!-- Page CSS -->
    <script src="assets/vendor/js/template-customizer.js"></script>
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
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
                                    <div class="swiper-wrapper">
                                        <?php
                                        $select_subscribed_plan_details = sqlQUERY_LABEL("SELECT asp.`agent_subscription_plan_ID`, asp.`agent_subscription_plan_title`, asp.`itinerary_allowed`, asp.`subscription_amount`, asp.`admin_count`, asp.`staff_count`, asp.`additional_charge_for_per_staff`, asp.`per_itinerary_cost`, asp.`validity_in_days`, asp.`subscription_notes`, asb.`agent_subscribed_plan_ID`, asb.`additional_staff_count`, asb.`additional_staff_charge` FROM `dvi_agent_subscribed_plans` asb LEFT JOIN `dvi_agent_subscription_plan` asp ON asb.`subscription_plan_ID` = asp.`agent_subscription_plan_ID` WHERE asb.`status` = 1 AND asb.`deleted` = 0 AND asp.`status` = 1 AND asp.`deleted` = 0 AND asb.`agent_ID`= $logged_agent_id ORDER BY asb.`agent_subscribed_plan_ID` ASC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
                                        $count_subscribed_plan_details = sqlNUMOFROW_LABEL($select_subscribed_plan_details);
                                        if ($count_subscribed_plan_details > 0) :
                                            while ($fetch_data = sqlFETCHARRAY_LABEL($select_subscribed_plan_details)) :
                                                $get_agent_subscription_plan_ID = $fetch_data['agent_subscription_plan_ID'];
                                                $agent_subscribed_plan_ID = $fetch_data['agent_subscribed_plan_ID'];
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
                                                $subscription_amount = $subscription_amount + $additional_charge_for_per_staff;
                                                $recommended = 'recommended';
                                                $recommended_badge = '<span class="recommended-badge">Renew Existing Plan</span>';
                                                $recommended_button = 'choose-btn-recommened';
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
                                                            <p class="mb-2"><i class="ti  fs-5 ti-check"></i>Staff login count<span class="text-blue"> (<?= $staff_count; ?>)</span></p>
                                                            <p class="mb-2"><i class="ti  fs-5 ti-check"></i>Added Additional Staff<span class="text-blue"> (<?= $additional_staff_count; ?>)</span></p>
                                                            <p class="mb-2"><i class="ti  fs-5 ti-check"></i>Per cost staff for extra login<span class="text-blue"> (<?= general_currency_symbol; ?><?= number_format($additional_charge_for_per_staff, 2); ?>)</span></p>
                                                        </div>
                                                        <a type="button" target="_blank" onclick="PROCEED_RENEWEL(<?= $agent_subscribed_plan_ID; ?>,<?= $get_agent_subscription_plan_ID ?>,<?= $logged_agent_id ?>);" class="<?= $recommended_button; ?> m-4">Choose Plan</a>
                                                    </div>
                                                </div>
                                        <?php endwhile;
                                        endif; ?>
                                        <?php
                                        $agent_subscribed_plan_ID = "";
                                        $select_subscription = sqlQUERY_LABEL("SELECT `agent_subscription_plan_ID`, `agent_subscription_plan_title`, `itinerary_allowed`, `subscription_type`, `subscription_amount`, `joining_bonus`, `admin_count`, `staff_count`, `additional_charge_for_per_staff`, `per_itinerary_cost`, `validity_in_days`, `recommended_status`, `subscription_notes` FROM `dvi_agent_subscription_plan` WHERE `deleted` = '0' AND `status` = '1' AND `subscription_type` = '1' AND `agent_subscription_plan_ID` != '$get_agent_subscription_plan_ID' ORDER BY `subscription_amount` ASC") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
                                        $total_no_of_subscription_plan = sqlNUMOFROW_LABEL($select_subscription);
                                        $total_no_of_subscription_plan = $total_no_of_subscription_plan + $count_subscribed_plan_details;
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
                                            if ($recommended_status == 1) :
                                                $recommended = 'recommended';
                                                $recommended_badge = '<span class="recommended-badge">Recommended</span>';
                                                $recommended_button = 'choose-btn-recommened';
                                            else :
                                                $recommended = '';
                                                $recommended_badge = '';
                                                $recommended_button = 'choose-btn';
                                            endif;

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
                                                        <p class="mb-2"><i class="ti  fs-5 ti-check"></i>Staff login count<span class="text-blue"> (<?= $staff_count; ?>)</span></p>
                                                        <p class="mb-2"><i class="ti  fs-5 ti-check"></i>Added Additional Staff<span class="text-blue"> (<?= $additional_staff_count; ?>)</span></p>
                                                        <p class="mb-2"><i class="ti  fs-5 ti-check"></i>Per cost staff for extra login<span class="text-blue"> (<?= general_currency_symbol; ?><?= number_format($additional_charge_for_per_staff, 2); ?>)</span></p>
                                                    </div>
                                                    <a type="button" target="_blank" onclick="PROCEED_RENEWEL(0,<?= $agent_subscription_plan_ID ?>,<?= $logged_agent_id ?>);" class="<?= $recommended_button; ?> m-4">Choose Plan</a>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                        <input type="hidden" id="razorpay_payment_id" name="razorpay_payment_id">
                                        <input type="hidden" id="razorpay_order_id" name="razorpay_order_id">
                                        <input type="hidden" id="razorpay_signature" name="razorpay_signature">
                                    </div>
                                    <div class="swiper-pagination"></div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                            </div>

                        </div>
                        </form>
                    </div>
                    <!-- / Content -->
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->

    <!-- Form Validation -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
    <!-- Correctly include Swiper JS -->
    <script src="assets/js/swiper-bundle.min.js"></script>
    <!-- Main JS -->
    <script src="assets/js/footerscript.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the total number of subscription plans from PHP
            var totalNoOfSubscriptionPlan = <?= $total_no_of_subscription_plan; ?>;

            // Calculate slidesPerView based on the total number of subscription plans
            var slidesPerView = Math.min(totalNoOfSubscriptionPlan, 3); // Ensure we don't exceed 3 slides per view

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



        function PROCEED_RENEWEL(agentSubscribedPlanID, agentSubscriptionPlanID, loggedAgentID) {
            $.ajax({
                type: "POST",
                url: 'engine/ajax/ajax_manage_agent_subscription_renewal.php?type=add',
                data: {
                    agent_subscribed_plan_ID: agentSubscribedPlanID,
                    agent_subscription_plan_ID: agentSubscriptionPlanID,
                    agent_ID: loggedAgentID
                },
                dataType: 'json',
                encode: true,
                success: function(response) {
                    if (response.success) {
                        initiateRazorpayPayment(response.order_id, response.amount);
                        $('#addCASHWALLETFORM').modal('hide');
                    } else {
                        if (response.errors && response.errors.cash_amount_required) {
                            TOAST_NOTIFICATION('error', 'Cash Amount Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            $('#cash_amount').focus();
                        }
                        if (response.errors && response.errors.id_error) {
                            TOAST_NOTIFICATION('error', 'Try Again, Something Went Wrong !!!', '', '', '', '', '', '', '', '', '');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    // Optionally handle the error
                }
            });
        }


        function initiateRazorpayPayment(order_id, amount) {
            var options = {
                "key": "<?= API_KEY; ?>",
                "amount": amount,
                "currency": "INR",
                "name": "DVI Holidays",
                "description": "Agent Registration Fee",
                "image": "<?= PUBLICPATH ?>assets/img/logo.png",
                "order_id": order_id,
                "handler": function(paymentResponse) {
                    $('#razorpay_payment_id').val(paymentResponse.razorpay_payment_id);
                    $('#razorpay_order_id').val(paymentResponse.razorpay_order_id);
                    $('#razorpay_signature').val(paymentResponse.razorpay_signature);
                    confirmPayment(paymentResponse);
                },
                "prefill": {
                    "name": "<?= getAGENT_details($logged_agent_id, '', 'label'); ?>",
                    "email": "<?= getAGENT_details($logged_agent_id, '', 'get_agent_email_address'); ?>",
                    "contact": "<?= getAGENT_details($logged_agent_id, '', 'get_agent_mobile_number'); ?>",
                },
                "theme": {
                    "color": "#3399cc"
                },
                "modal": {
                    "ondismiss": function() {
                        location.reload();
                    }
                }
            };
            var rzp = new Razorpay(options);
            rzp.open();
        }

        function confirmPayment(paymentResponse) {
            $.ajax({
                type: "POST",
                url: 'engine/ajax/ajax_manage_agent_subscription_renewal.php?type=confirm_payment',
                data: {
                    razorpay_payment_id: paymentResponse.razorpay_payment_id,
                    razorpay_order_id: paymentResponse.razorpay_order_id,
                    razorpay_signature: paymentResponse.razorpay_signature
                },
                dataType: 'json',
                success: function(response) {
                    if (response.free_result == true) {
                        // Show the toast notification
                        TOAST_NOTIFICATION('success', 'Payment Successful!!!', 'Success !!!', '', '', '', '', '', '', '', '', '', 5000);

                        // Redirect after the toast notification duration
                        setTimeout(function() {
                            window.location.href = response.returnURL;
                        }, 1000);
                    } else if (response.free_result == false) {
                        // Create a temporary DOM element to extract text content
                        var tempDiv = document.createElement('div');
                        tempDiv.innerHTML = response.result_error;
                        var errorMessage = tempDiv.textContent || tempDiv.innerText || '';

                        // Pass the extracted text content to TOAST_NOTIFICATION
                        TOAST_NOTIFICATION('error', errorMessage, 'Error !!!', '', '', '', '', '', '', '', '', '');
                    }
                },
                error: function() {
                    toastr.error('Payment confirmation failed.');
                }
            });
        }
    </script>

</body>

</html>
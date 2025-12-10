<?php

include_once("jackus.php");
admin_reguser_protect();
$current_page = 'hotel_overall_pricebook.php'; // Set the current page variable
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

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="assets/vendor/libs/quill/editor.css" />
    <link rel="stylesheet" href="assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
    <!-- Bootstrap-timepicker CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/bootstarp-timepicker/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="assets/vendor/libs/fullcalendar/fullcalendar.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/app-calendar.css" />
    <link rel="stylesheet" href="assets/css/easy-autocomplete.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <style>
        .fc .fc-more-popover .fc-popover-body {
            max-height: 314px;
            overflow-y: auto;
        }

        /* WebKit (Chrome, Safari) */
        .fc .fc-more-popover .fc-popover-body::-webkit-scrollbar {
            width: 8px;
        }

        .fc .fc-more-popover .fc-popover-body::-webkit-scrollbar-thumb {
            background-color: #dbdade !important;
            border-radius: 6px;
        }

        .fc .fc-more-popover .fc-popover-body::-webkit-scrollbar-track {
            background-color: #f4f4f5;
        }

        /* Firefox */
        .fc .fc-more-popover .fc-popover-body {
            scrollbar-width: 8px;
            scrollbar-color: #dbdade #f4f4f5;
        }

        /* Microsoft Edge */
        .fc .fc-more-popover .fc-popover-body {
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }
    </style>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">

            <!-- Menu -->
            <?php include_once('public/__sidebar.php'); ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?php include_once('public/__topbar.php'); ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class=" d-flex justify-content-between align-items-center">
                            <h4><?php include adminpublicpath('__pagetitle.php'); ?></h4>
                            <?php include adminpublicpath('__breadcrumb.php'); ?>
                        </div>
                        <form class="" id="form_hotel_pricebook" method="post" data-parsley-validate>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card app-calendar-wrapper p-4">
                                        <div class="row g-3">


                                            <div class="col-3">

                                                <label class="form-label" for="vendor_branch_state">State<span class=" text-danger"> *</span></label>
                                                <div class="form-group">
                                                    <select class="form-select vendor_branch_state" name="hotel_state" id="hotel_state" onchange="CHOOSEN_STATE_ADD()" data-parsley-trigger="keyup" data-parsley-errors-container="#state_error_container" required>
                                                        <?php
                                                        echo getSTATELIST('101', '', 'select_state');
                                                        ?>
                                                    </select>
                                                </div>
                                                <div id="state_error_container"></div>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label" for="vendor_branch_city">City<span class=" text-danger"> *</span></label>
                                                <div class="form-group">
                                                    <select class="form-select" name="hotel_city" id="hotel_city" data-parsley-trigger="keyup" data-parsley-errors-container="#city_error_container" onchange="show_category_for_the_hotel('select_hotel', '', '');" required>
                                                        <option value=""> Choose City</option>
                                                    </select>
                                                </div>
                                                <div id="city_error_container"></div>
                                            </div>

                                            <div class="col-3">
                                                <label class="form-label" for="hotel_category">Hotel Category<span class="text-danger"> *</span></label>

                                                <div class="form-group">
                                                    <select id="hotel_category" name="hotel_category" required class="form-select " data-parsley-trigger="keyup" data-parsley-errors-container="#hotel_cat_error_container">
                                                        <?= getHOTEL_CATEGORY_DETAILS($hotel_category, 'select'); ?>
                                                    </select>
                                                </div>
                                                <div id="hotel_cat_error_container"></div>
                                            </div>

                                            <div class="col-3" id="hotelDiv">
                                                <label class="form-label" for="hotel_name">Hotel Name<span class="text-danger"> *</span></label>
                                                <select id="hotel_name" name="hotel_name" required class="form-select form-control">
                                                    <option value=""> Choose Hotel</option>
                                                </select>
                                            </div>
                                            <div class="col-3" id="roomTypeDiv">
                                                <label class="form-label " for="room_type">Room Type<span class=" text-danger"> *</span></label>
                                                <select id="room_type" name="room_type" required class="form-select form-control">
                                                    <option value=""> Choose Room</option>
                                                </select>
                                            </div>
                                            <!--  <div class="col-3">
                                                <label class="form-label " for="year">Year<span class=" text-danger"> *</span></label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Choose year" name="year" id="year" required autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label " for="month">Month<span class=" text-danger"> *</span></label>
                                                <select id="month" name="month" required class="form-select form-control">
                                                    <?= getMONTHS_LIST($month_id, 'select'); ?>
                                                </select>
                                            </div>-->

                                            <div class="col-3">
                                                <label for="selectstartdate" class="form-label">Start Date</label>
                                                <input type="text" class="form-control show_datepicker" placeholder="DD/MM/YYYY" id="selectstartdate" name="selectstartdate" required />
                                            </div>
                                            <div class="col-3">
                                                <label for="selectenddate" class="form-label">End Date</label>
                                                <input type="text" class="form-control show_datepicker" placeholder="DD/MM/YYYY" id="selectenddate" name="selectenddate" required />
                                            </div>

                                            <div class="col-3">
                                                <label class="form-label" for="price">Price ₹</label>
                                                <input type="text" id="price" name="price" required class="form-control" placeholder="Enter Price" autocomplete="off">
                                            </div>

                                            <div class="col-12 d-flex justify-content-end">
                                                <div>
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light pe-3 mt-4">Submit<?= $btn_label ?></button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="pt-4 row" id="show_calendar_div">
                            <div class="col-12">
                                <div class="card app-calendar-wrapper">
                                    <div class="row g-0">
                                        <!-- Calendar Sidebar -->
                                        <div class="col app-calendar-sidebar" id="app-calendar-sidebar">
                                            <div class="p-3">
                                                <!-- Filter -->
                                                <div class="mb-3 ms-3">
                                                    <small class="text-small text-muted text-uppercase align-middle">Filter</small>
                                                </div>

                                                <div class="form-check mb-2 ms-3">
                                                    <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all" checked>
                                                    <label class="form-check-label" for="selectAll">View All</label>
                                                </div>

                                                <div class="app-calendar-events-filter ms-3">
                                                    <div class="form-check form-check-success mb-2">
                                                        <input class="form-check-input input-filter" type="checkbox" id="select-rooms" data-value="rooms" checked>
                                                        <label class="form-check-label" for="select-rooms">Room
                                                            Price</label>
                                                    </div>
                                                    <div class="form-check form-check-warning mb-2">
                                                        <input class="form-check-input input-filter" type="checkbox" id="select-amenities" data-value="amenities" checked>
                                                        <label class="form-check-label" for="select-amenities">Amentites
                                                            Price</label>
                                                    </div>

                                                    <!--  <div class="form-group mt-4" id="">
                                                        <label class="form-label" for="hotelFilter">State </label>
                                                        <select id="stateFilter" name="stateFilter" class="form-control">
                                                            <option value="null">All</option>
                                                            <?php
                                                            echo getSTATELIST('101', '', 'select_state');
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mt-4" id="">
                                                        <label class="form-label" for="cityFilter">City </label>
                                                        <select id="cityFilter" name="cityFilter" class="form-control">
                                                            <option value="null">All</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mt-4" id="">
                                                        <label class="form-label" for="hotelcatFilter">Hotel Category</label>
                                                        <select id="hotelcatFilter" name="hotelcatFilter" class="form-control">
                                                            <option value="null">All</option>
                                                            <?= getHOTEL_CATEGORY_DETAILS('', 'select'); ?>
                                                        </select>
                                                    </div>-->

                                                    <div class="form-group mt-4" id="">
                                                        <label class="form-label" for="hotelFilter">Hotel Name</label>
                                                        <select id="hotelFilter" name="hotelFilter" class="form-control">
                                                            <option value="null">All</option>
                                                            <?= getHOTELDETAILS($_GET['id'], 'SELECT_HOTEL_FROM_LIST'); ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mt-4" id="roomTypeFilterDiv">
                                                        <label class="form-label" for="roomTypeFilter">Room Type</label>
                                                        <select id="roomTypeFilter" name="roomTypeFilter" class="form-control">
                                                            <option value="null">All</option>
                                                            <?= getHOTEL_ROOM_TYPE_DETAIL('', '', '', 'select'); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Calendar Sidebar -->

                                        <!-- Calendar & Modal -->
                                        <div class="col app-calendar-content">
                                            <div class="card shadow-none border-0">
                                                <div class="card-body pb-0">
                                                    <!-- FullCalendar -->
                                                    <div id="calendar"></div>
                                                </div>
                                            </div>
                                            <div class="app-overlay"></div>
                                        </div>
                                        <!-- /Calendar & Modal -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>

    </div>
    <!-- / Layout wrapper -->

    <!-- Enable OTP Modal -->
    <div class="modal fade" id="showPRICEBOOKFORM" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body show-pricebook-form-data">
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
    <script src="assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js"></script>
    <!-- <script src="assets/vendor/libs/select2/select2.js"></script> -->
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="assets/vendor/libs/moment/moment.js"></script>
    <script src="assets/js/selectize/selectize.min.js"></script>
    <script src="assets/vendor/libs/fullcalendar/fullcalendar.js"></script>
    <!--<script src="assets/js/app-hotel-calendar.js?id=<?= $_GET['id']; ?>&rtype_id=<?= $_GET['rtype_id']; ?>"></script>-->
    <script src="assets/js/app-hotel-calendar.js?id=" + hotelFilterValue + &roomTypeFilterValue=+ roomTypeFilterValue>
    </script>
    <link rel="stylesheet" href="assets/css/bootstrap-datepicker3.css" />
    <!-- Page JS -->
    <script src="assets/js/bootstrap-datepicker.js"></script>
    <script src="assets/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/js/jquery.easy-autocomplete.min.js"></script>

    <!-- Main JS -->
    <script src=" assets/js/main.js"></script>

    <script>
        $(document).ready(function() {

            $("select").selectize();

            var startDatePicker = flatpickr("#selectstartdate", {
                dateFormat: "d-m-Y",
                onChange: function(selectedDates, dateStr, instance) {
                    endDatePicker.set("minDate", dateStr);
                }
            });

            var endDatePicker = flatpickr("#selectenddate", {
                dateFormat: "d-m-Y"
            });
        });



        function CHOOSEN_STATE_ADD() {
            var city_selectize = $("#hotel_city")[0].selectize;
            var STATE_ID = $('#hotel_state').val();
            // Get the response from the server.
            $.ajax({
                url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_state&STATE_ID=' + STATE_ID,
                type: "GET",
                success: function(response) {
                    // Append the response to the dropdown.
                    city_selectize.clear();
                    city_selectize.clearOptions();
                    city_selectize.addOption(response);
                    <?php /*if ($vendor_branch_city) : ?>
                city_selectize.setValue('<?= $vendor_branch_city; ?>');
                <?php endif; */ ?>
                }
            });
        }

        function filter_calendar(get_hotel_ID, roomtypeFilterValue) {
            console.log(roomtypeFilterValue);
            // var state_id = $('#stateFilter').val();
            // var city_id = $('#cityFilter').val();
            // var hotel_cat_id = $('#hotelcatFilter').val();

            let date = new Date(),
                nextDay = new Date(new Date().getTime() + 864e5),
                nextMonth =
                11 === date.getMonth() ?
                new Date(date.getFullYear() + 1, 0, 1) :
                new Date(date.getFullYear(), date.getMonth() + 1, 1),
                prevMonth =
                11 === date.getMonth() ?
                new Date(date.getFullYear() - 1, 0, 1) :
                new Date(date.getFullYear(), date.getMonth() - 1, 1);

            let events = []; // Initialize an empty array for events.

            // Function to fetch JSON data based on roomTypeFilter
            // "engine/json/__JSON_hotel_calendar_pricebook_details.php?hotel_id=" + get_hotel_ID + "&roomTypeFilter=" + roomTypeFilterValue + "&state_id=" + state_id + "&city_id=" + city_id + "&hotel_cat_id=" + hotel_cat_id
            function fetchEventsData(roomTypeFilterValue) {
                return fetch(
                        "engine/json/__JSON_hotel_calendar_pricebook_details.php?hotel_id=" +
                        get_hotel_ID + "&roomTypeFilter=" + roomTypeFilterValue
                    )
                    .then((response) => response.json())
                    .catch((error) => {
                        console.error("Error loading events:", error);
                    });
            }

            // document.addEventListener("DOMContentLoaded", function() {
            const v = document.getElementById("calendar");

            const r = new Calendar(v, {
                initialView: "dayGridMonth",
                dayMaxEvents: 4,
                plugins: [dayGridPlugin, interactionPlugin, listPlugin, timegridPlugin],
                editable: true,
                dateClick: function(e) {
                    const clickedDate = e.date;
                    const year = clickedDate.getFullYear();
                    const month = String(clickedDate.getMonth() + 1).padStart(2,
                        "0"); // Adding 1 because months are zero-based
                    const day = String(clickedDate.getDate()).padStart(2, "0");

                    const formattedDate = `${year}-${month}-${day}`;
                    // Show your modal popup here
                    showPRICEBOOK_MODAL(formattedDate);
                },
                headerToolbar: {
                    left: "prev,next",
                    center: "title",
                    right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek", // Include the views you want
                },
                buttonText: {
                    month: "Month",
                    week: "Week",
                    day: "Day",
                    list: "List",
                },
                eventClassNames: function({
                    event: e
                }) {
                    const classNames = [];

                    // Check if 'calendar' property exists in the extendedProps
                    if (e.extendedProps && e.extendedProps.calendar) {
                        const calendarColorMap = {
                            rooms: "fc-event-success",
                            Business: "fc-event-danger",
                            amenities: "fc-event-warning",
                            // Add more calendar-color mappings as needed
                        };

                        const calendarClassName = calendarColorMap[e.extendedProps.calendar];

                        if (calendarClassName) {
                            classNames.push(calendarClassName);
                        }
                    }

                    return classNames;
                },
            });

            const checkboxes = document.querySelectorAll('.input-filter');
            const viewAllCheckbox = document.getElementById('selectAll'); // Assuming you have an "View All" checkbox

            // Function to handle the "View All" checkbox
            function handleViewAllCheckbox() {
                const isChecked = viewAllCheckbox.checked;

                if (isChecked) {
                    r.addEventSource(events);
                    // Iterate through the selected checkboxes and uncheck them
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = true;
                    });
                    viewAllCheckbox.checked = true;
                } else {
                    r.removeAllEvents();
                    // Iterate through the selected checkboxes and uncheck them
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = false;
                    });
                }
            }

            // Attach a click event listener to the "View All" checkbox
            viewAllCheckbox.addEventListener('click', handleViewAllCheckbox);

            // Function to handle the filtering based on checkboxes
            function filterEvents() {

                const selectedFilters = Array.from(checkboxes)
                    .filter(checkbox => checkbox.checked && checkbox.id !== 'selectAll') // Exclude the "View All" checkbox
                    .map(checkbox => checkbox.getAttribute('data-value'));

                if (selectedFilters != 'rooms,amenities') {
                    viewAllCheckbox.checked = false;
                } else {
                    viewAllCheckbox.checked = true;
                }

                const filteredEvents = events.filter(event => {
                    if (selectedFilters.length === 0 || selectedFilters.includes('all')) {
                        // If "View All" is checked or no specific filters are selected, show all events
                        if (selectedFilters.length === 0) {
                            return false;
                        } else {
                            return true;
                        }
                    } else {
                        // Adjust this condition based on your data structure and how you want to filter
                        return selectedFilters.includes(event.extendedProps.calendar.toLowerCase());
                    }
                });

                r.removeAllEvents(); // Remove existing events from the calendar
                r.addEventSource(filteredEvents); // Add filtered events to the calendar
            }

            // Attach a click event listener to each checkbox to trigger the filtering
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('click', filterEvents);
            });
            // Initial filtering on page load (if needed)
            filterEvents();
            console.log($('#roomTypeFilter').val());
            // Fetch initial data and update events
            fetchEventsData($('#roomTypeFilter').val())
                .then((data) => {
                    events = data;
                    r.addEventSource(events);
                    r.render();
                });
            // );
        }

        // Initialize Selectize for both dropdowns
        var hotelFilterSelectize = $('#hotelFilter').selectize()[0].selectize;
        var roomTypeFilterSelectize = $('#roomTypeFilter').selectize()[0].selectize;
        // var stateFilterSelectize = $('#stateFilter').selectize()[0].selectize;
        // var hotelcatFilterSelectize = $('#hotelcatFilter').selectize()[0].selectize;




        // Listen for the change event on Selectize
        hotelFilterSelectize.on('change', function() {
            var hotelFilterValue = hotelFilterSelectize.getValue();
            var roomTypeFilterValue = roomTypeFilterSelectize.getValue();
            console.log("Selected hotelFilter value: " + hotelFilterValue);

            if (hotelFilterValue !== '' && hotelFilterValue !== '0') {
                show_room_for_hotel('select_room', hotelFilterValue);
            }

            filter_calendar(hotelFilterValue, roomTypeFilterValue);
        });

        roomTypeFilterSelectize.on('change', function() {
            var hotelFilterValue = hotelFilterSelectize.getValue();
            var roomTypeFilterValue = roomTypeFilterSelectize.getValue();
            console.log("Selected roomTypeFilter value: " + roomTypeFilterValue);
            filter_calendar(hotelFilterValue, roomTypeFilterValue);
        });

        function show_room_for_hotel(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_hotel_overall_pricebook.php',
                data: {
                    ID: ID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#roomTypeFilterDiv').html(response);
                }
            });
        }



        //trigger hotel name through hotel category
        var hotelCategorySelectize = $('#hotel_category').selectize()[0].selectize;
        var hotelNameSelectize = $('#hotel_name').selectize()[0].selectize;
        // Listen for the change event on Selectize
        hotelCategorySelectize.on('change', function() {
            var hotelCategoryValue = hotelCategorySelectize.getValue();
            var hotelNameValue = hotelNameSelectize.getValue();
            var hotel_city = $('#hotel_city').val();
            console.log("Selected hotelCategory value: " + hotelCategoryValue);
            if (hotelCategoryValue !== '' && hotelCategoryValue !== '0') {
                show_category_for_the_hotel('select_hotel', hotelCategoryValue, hotel_city);
            }
        });

        function show_category_for_the_hotel(TYPE, HOTEL_CAT_ID, CITY_ID) {
            if (HOTEL_CAT_ID == '') {
                var HOTEL_CAT_ID = $('#hotel_category').val();
            }
            if (CITY_ID == '') {
                var CITY_ID = $('#hotel_city').val();
            }
            if (HOTEL_CAT_ID != "" && CITY_ID != "") {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_hotel_overall_pricebook.php',
                    data: {
                        ID: HOTEL_CAT_ID,
                        TYPE: TYPE,
                        CITY_ID: CITY_ID
                    },
                    success: function(response) {
                        $('#hotelDiv').html(response);
                    }
                });
            }
        }

        // var hotelNameSelectize = $('#hotel_name').selectize()[0].selectize;
        // var roomTypeSelectize = $('#room_type').selectize()[0].selectize;
        // // Listen for the change event on Selectize
        // hotelNameSelectize.on('change', function() {
        //     var hotelFilterValue = hotelNameSelectize.getValue();
        //     var roomTypeFilterValue = roomTypeSelectize.getValue();
        //     console.log("Selected hotelFilter value: " + hotelFilterValue);

        //     if (hotelFilterValue !== '' && hotelFilterValue !== '0') {
        //         show_room_for_the_hotel('select_room', hotelFilterValue);
        //     }
        // });

        // function show_room_for_the_hotel(TYPE, ID) {
        //     $.ajax({
        //         type: 'post',
        //         url: 'engine/ajax/__ajax_hotel_overall_pricebook.php',
        //         data: {
        //             ID: ID,
        //             TYPE: TYPE
        //         },
        //         success: function(response) {
        //             $('#roomTypeDiv').html(response);
        //         }
        //     });
        // }

        //AJAX FORM SUBMIT
        $("#form_hotel_pricebook").submit(function(event) {
            var form = $('#form_hotel_pricebook')[0];
            var data = new FormData(form);
            $(this).find("button[id='submit_itinerary_basic_info_btn']").prop('disabled', true);
            $.ajax({
                type: "post",
                url: 'engine/ajax/__ajax_manage_hotel.php?type=hotel_pricebook',
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 80000,
                dataType: 'json',
                encode: true,
            }).done(function(response) {

                if (!response.success) {
                    //NOT SUCCESS RESPONSE
                    if (response.errors.hotel_category_required) {
                        TOAST_NOTIFICATION('error', 'Hotel Category is Required', 'Error !!!', '', '', '',
                            '', '', '', '', '', '');
                    } else if (response.errors.hotel_name_required) {
                        TOAST_NOTIFICATION('error', 'Hotel Name is Required', 'Error !!!', '', '', '', '',
                            '', '', '', '', '');
                    } else if (response.errors.room_type_required) {
                        TOAST_NOTIFICATION('error', 'Room Type is Required', 'Error !!!', '', '', '', '',
                            '', '', '', '', '');
                    } else if (response.errors.year_required) {
                        TOAST_NOTIFICATION('error', 'Year is Required', 'Error !!!', '', '', '', '', '', '',
                            '', '', '');
                    } else if (response.errors.month_required) {
                        TOAST_NOTIFICATION('error', 'Month is Required', 'Error !!!', '', '', '', '', '',
                            '', '', '', '');
                    } else if (response.errors.price_required) {
                        TOAST_NOTIFICATION('error', 'Price is Required', 'Error !!!', '', '', '', '', '',
                            '', '', '', '');
                    } else if (response.errors.selectstartdate_required) {
                        TOAST_NOTIFICATION('error', 'Start date is Required', 'Error !!!', '', '', '', '',
                            '', '', '', '', '');
                    } else if (response.errors.selectenddate_required) {
                        TOAST_NOTIFICATION('error', 'End Date is Required', 'Error !!!', '', '', '', '', '',
                            '', '', '', '');
                    }
                } else {
                    //SUCCESS RESPOSNE

                    if (response.i_result == true) {

                        location.reload();
                        TOAST_NOTIFICATION('success', 'Hotel Room Price Created Successfully',
                            'Success !!!', '', '', '', '', '', '', '', '', '');
                    } else if (response.u_result == true) {
                        //RESULT SUCCESS

                        location.reload();
                        TOAST_NOTIFICATION('success', 'Hotel Room Price Updated', 'Success !!!', '', '', '',
                            '', '', '', '', '', '');
                    } else if (response.i_result == false) {
                        //RESULT FAILED
                        TOAST_NOTIFICATION('error', 'Unable to Add Hotel Room Price', 'Error !!!', '', '',
                            '', '', '', '', '', '', '');
                    } else if (response.u_result == false) {
                        //RESULT FAILED
                        TOAST_NOTIFICATION('error', 'Unable to Update Hotel Room Price', 'Error !!!', '',
                            '', '', '', '', '', '', '', '');
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
    </script>

</body>

</html>
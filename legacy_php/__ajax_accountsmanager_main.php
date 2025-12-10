<?php

/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2023 Touchmark Descience Pvt Ltd
*
*/

include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

?>
        <div class="row g-4 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Payout List</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="nav-align-top mb-4">
                    <ul class="nav nav-pills accountmanager-tab-section mb-0" id="accountmanager-tab-section" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-all" aria-controls="navs-pills-top-all" aria-selected="true">All</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-paid" aria-controls="navs-pills-top-paid" aria-selected="false">Paid</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-due" aria-controls="navs-pills-top-due" aria-selected="false">Due</button>
                        </li>
                    </ul>
                    <div class="tab-content px-2">
                        <div class="tab-pane fade show active" id="navs-pills-top-all" role="tabpanel">
                            <span id="showACCOUNTSMANAGERAllLIST"></span>
                        </div>
                        <div class="tab-pane fade" id="navs-pills-top-paid" role="tabpanel">
                            <span id="showACCOUNTSMANAGERPAIDLIST"></span>
                        </div>
                        <div class="tab-pane fade" id="navs-pills-top-due" role="tabpanel">
                            <span id="showACCOUNTSMANAGERDUELIST"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // Check which tab is active when the page is loaded
                if ($('#navs-pills-top-all').hasClass('active')) {
                    show_ACCOUNTSMANAGER_ALL_LIST();
                } else if ($('#navs-pills-top-paid').hasClass('active')) {
                    show_ACCOUNTSMANAGER_PAID_LIST();
                } else if ($('#navs-pills-top-due').hasClass('active')) {
                    show_ACCOUNTSMANAGER_DUE_LIST();
                }

                // Handle tab click events to load content dynamically
                $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                    const targetTab = $(e.target).data('bs-target');
                    if (targetTab === '#navs-pills-top-all') {
                        show_ACCOUNTSMANAGER_ALL_LIST();
                    } else if (targetTab === '#navs-pills-top-paid') {
                        show_ACCOUNTSMANAGER_PAID_LIST();
                    } else if (targetTab === '#navs-pills-top-due') {
                        show_ACCOUNTSMANAGER_DUE_LIST();
                    }
                });
            });


            // Function to load 'All' list
            function show_ACCOUNTSMANAGER_ALL_LIST() {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_accountsmanager_all_list.php?type=show_form',
                    success: function(response) {
                        $('#showACCOUNTSMANAGERAllLIST').html(response);
                        $('#showACCOUNTSMANAGERPAIDLIST').html('');
                        $('#showACCOUNTSMANAGERDUELIST').html('');
                    }
                });
            }

            // Function to load 'Paid' list
            function show_ACCOUNTSMANAGER_PAID_LIST() {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_accountsmanager_all_list.php?type=show_form_paid',
                    success: function(response) {
                        $('#showACCOUNTSMANAGERPAIDLIST').html(response);
                        $('#showACCOUNTSMANAGERAllLIST').html('');
                        $('#showACCOUNTSMANAGERDUELIST').html('');
                    }
                });
            }

            // Function to load 'Due' list
            function show_ACCOUNTSMANAGER_DUE_LIST() {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_accountsmanager_all_list.php?type=show_form_due',
                    success: function(response) {
                        $('#showACCOUNTSMANAGERDUELIST').html(response);
                        $('#showACCOUNTSMANAGERAllLIST').html('');
                        $('#showACCOUNTSMANAGERPAIDLIST').html('');
                    }
                });
            }
        </script>

<?php
    endif;
endif;
?>
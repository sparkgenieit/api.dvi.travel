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
        $itinerary_plan_ID = $_POST['itinerary_plan_ID'];

?>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card p-0">
                    <div class="card-header pb-3 d-flex justify-content-between">
                        <div class="col-md-auto">
                            <h5 class="card-title mb-3 mt-2">List of Incidental Expenses</h5>
                        </div>
                    </div>

                    <div class="card-body dataTable_select text-nowrap">
                        <div class="text-nowrap overflow-hidden table-bordered">
                            <table id="incidental_LIST" class="table table-hover table-responsive">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <?php if ($logged_user_level == 1): ?>
                                            <th>Action</th>
                                        <?php endif; ?>
                                        <th>Route Date</th>
                                        <th>Component</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Payment Date</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="showDELETEMODAL" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content receiving-delete-form-data">
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $(".form-select").selectize();

                if (!$.fn.dataTable.isDataTable('#incidental_LIST')) {
                    var dataTable = $('#incidental_LIST').DataTable({
                        dom: 'Blfrtip',
                        "bFilter": true,
                        buttons: [{
                            extend: 'excel',
                            text: window.excelButtonTrans,
                            exportOptions: {
                                columns: [0, 2, 3, 4, 5, 6],
                            }
                        }],
                        initComplete: function() {
                            $('.buttons-excel').html(
                            '<a href="javascript:;" class="d-flex align-items-center btn btn-sm  btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>'
                            );
                        },
                        ajax: {
                            "url": "engine/json/__JSONincidentalexpenses.php?itinerary_plan_ID=<?= $itinerary_plan_ID; ?>",
                            "type": "GET"
                        },
                        columns: [{
                                data: "count"
                            },
                            <?php if ($logged_user_level == 1): ?> {
                                    data: "modify"
                                },
                            <?php endif; ?> {
                                data: "route_date"
                            },
                            {
                                data: "component_type"
                            },
                            {
                                data: "component_name"
                            },
                            {
                                data: "incidental_amount"
                            },
                            {
                                data: "date"
                            },
                            {
                                data: "reason"
                            }
                        ],
                        <?php if ($logged_user_level == 1): ?>
                            columnDefs: [{
                                "targets": 1,
                                "data": "modify",
                                "render": function(data, type, full) {
                                    return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showDELETESTAFFMODAL(' + data + ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> </div>';
                                }
                            }],
                        <?php endif; ?>
                    });
                }
            });

            //SHOW DELETE POPUP
            function showDELETESTAFFMODAL(ID) {
                $('.receiving-delete-form-data').load('engine/ajax/ajax_incidentalexpenses_manage.php?type=incidental_delete_modal&ID=' + ID,
                    function() {
                        const container = document.getElementById("showDELETEMODAL");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
            }

            // CONFIRM DELETE POPUP
            function confirmDELETE(ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_incidentalexpenses_manage.php?type=incidental_delete",
                    data: {
                        ID: ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result_success === true) {
                            $('#incidental_LIST').DataTable().ajax.reload(); // Reload the table
                            $('#showDELETEMODAL').modal('hide');
                            TOAST_NOTIFICATION('success', 'Incidental Expenses Delete Successfully', 'Success !!!');
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to delete the hotel', 'Error !!!');
                        }
                    }
                });
            }
        </script>
<?php
    endif;
endif;
?>
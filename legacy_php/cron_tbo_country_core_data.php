<?php

include_once('jackus.php');

$response = callApi(TBO_MASTER_API . '/CountryList', 'GET', [], TBO_API_AUTH_UN, TBO_API_AUTH_PWD);

$countryList = $response['data']['CountryList'];

$inserted_count = 0;
$existing_count = 0;
$failed_count = 0;

foreach ($countryList as $country) :
    $shortname = $country['Code'];
    $name = $country['Name'];

    // Check if country already exists
    $check_sql = "SELECT COUNT(*) AS count FROM `dvi_countries` WHERE `shortname` = '$shortname' AND deleted = 0";
    $check_result = sqlQUERY_LABEL($check_sql);
    $row = sqlNUMOFROW_LABEL($check_result) > 0 ? sqlFETCHARRAY_LABEL($check_result) : ['count' => 0];

    if ($row['count'] == 0) :
        // Prepare insert fields and values
        $country_arrFields = array('`shortname`', '`name`', '`phonecode`', '`createdby`', '`status`');
        $country_arrValues = array("$shortname", "$name", "", "$logged_user_id", "1");

        // Insert into table
        if (sqlACTIONS("INSERT", "dvi_countries", $country_arrFields, $country_arrValues, '')) :
            $log_output .= "<p><strong>Inserted:</strong> $name ($shortname)</p>";
            $inserted_count++;
        else :
            $log_output .= "<p><span style='color:red;'>Failed to insert:</span> $name ($shortname)</p>";
            $failed_count++;
        endif;
    else :
        $log_output .= "<p><span style='color:gray;'>Already exists:</span> $name ($shortname)</p>";
        $existing_count++;
    endif;
endforeach;

// Summary
$log_output .= "<hr>";
$log_output .= "<h4>Summary:</h4>";
$log_output .= "<p><strong>Inserted:</strong> $inserted_count</p>";
$log_output .= "<p><strong>Already Exists:</strong> $existing_count</p>";
if ($failed_count > 0) :
    $log_output .= "<p style='color:red;'><strong>Failed:</strong> $failed_count</p>";
endif;

// Output log
echo $log_output;
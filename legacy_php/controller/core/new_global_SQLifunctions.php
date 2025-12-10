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
include('Encryption.php');

// Use persistent connection to speed up connection times
$conn = mysqli_connect("p:" . DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn == false) :
	die("Failed to connect to MySQL: " . mysqli_connect_error());
endif;

//0. Basic Query label
function sqlQUERY_LABEL($query)
{
	global $conn;
	return mysqli_query($conn, $query);
}

function sqlFETCHARRAY_LABEL($query)
{
	global $conn;
	return mysqli_fetch_array($query, MYSQLI_ASSOC);
}

function sqlERROR_LABEL()
{
	global $conn;
	return mysqli_error($conn);
}

function sqlNUMOFROW_LABEL($query)
{
	global $conn;
	return mysqli_num_rows($query);
}

function sqlDATASEEK_LABEL($query, $offset)
{
	global $conn;
	return mysqli_data_seek($query, $offset);
}

function sqlFETCHASSOC_LABEL($query)
{
	global $conn;
	return mysqli_fetch_assoc($query);
}

function sqlREALESCAPESTRING_LABEL($query)
{
	global $conn;
	return mysqli_real_escape_string($conn, $query);
}

function sqlINSERTID_LABEL()
{
	global $conn;
	return mysqli_insert_id($conn);
}

function sqlFETCHROW_LABEL($query)
{
	global $conn;
	return mysqli_fetch_row($query);
}

function sqlFETCHOBJECT_LABEL($query)
{
	return mysqli_fetch_object($query);
}

function sqlFREE_RESULT($query)
{
	return mysqli_free_result($query);
}

function sqlMORE_RESULT($conn)
{
	return mysqli_more_results($conn);
}

function sqlNEXT_RESULT($conn)
{
	return mysqli_next_result($conn);
}

function sqlSTORE_RESULT($conn)
{
	return mysqli_store_result($conn);
}

//1. SQL Query
function sqlQUERY($query)
{
	global $conn;
	if (mysqli_query($conn, $query)) :
		return true;
	else :
		error_log("SQL Error: " . mysqli_error($conn)); // Log error for debugging
		return false;
	endif;
}

//2. SQL QUERY + Fetch Array
function sqlRETURNROWSET($query)
{
	global $conn;
	if ($results = sqlQUERY($query)) :
		if ($row = mysqli_fetch_array($results)) :
			return $row;
		endif;
	else :
		return $results;
	endif;
}

//3. SQL Total Row Count
function sqlROWCOUNT($query)
{
	global $conn;
	$result = sqlQUERY($query);
	while ($val = mysqli_fetch_array($result)) :
		$row_count = $val['row_count'];
	endwhile;
	return $row_count;
}

//4. Get Single Value
function sqlSINGLEVALUE($sqlFrom, $sqlWhere, $getField)
{
	global $conn;
	$tmp_val = "";
	$query = "SELECT " . $getField . " FROM " . $sqlFrom . " WHERE " . $sqlWhere;
	$row = sqlRETURNROWSET($query);
	if (empty($row)) : return "";
	endif;
	$tmp_val = $row[$getField];
	if (empty($tmp_val)) :
		return "";
	else :
		return $tmp_val;
	endif;
}

function sqlACTIONS($sqlAction, $tableName, $arrFields = [], $arrValues = [], $sqlWhere = "")
{
	global $conn;

	$sqlResult = "";
	$stmt = null;
	$types = "";  // Initialize types string

	// Prepare the action based on query type
	switch ($sqlAction) {
		case "UPDATE":
			$updateFields = array_map(function ($field) {
				return "$field = ?";
			}, $arrFields);
			$sqlResult = "UPDATE $tableName SET " . implode(", ", $updateFields) . ", updatedon = ?";
			$arrValues[] = date('Y-m-d H:i:s');
			break;

		case "INSERT":
			$placeholders = array_fill(0, count($arrFields), "?");
			$sqlResult = "INSERT INTO `$tableName` (" . implode(", ", $arrFields) . ", createdon) VALUES (" . implode(", ", $placeholders) . ", ?)";
			$arrValues[] = date('Y-m-d H:i:s');
			break;

		case "DELETE":
			$sqlResult = "DELETE FROM `$tableName`";
			break;

		case "SELECT":
			$selectFields = $arrFields ? implode(", ", $arrFields) : "*";
			$sqlResult = "SELECT $selectFields FROM `$tableName`";
			break;
	}

	// Add WHERE clause if present
	if ($sqlWhere) {
		$sqlResult .= " WHERE $sqlWhere";
	}

	// Prepare and bind parameters for execution
	$stmt = $conn->prepare($sqlResult);
	if ($stmt === false) {
		error_log("SQL Error: " . $conn->error);
		return false;
	}

	// Bind parameters if values are provided
	if (!empty($arrValues)) {
		$types = str_repeat("s", count($arrValues)); // Assume all fields are strings for simplicity
		$stmt->bind_param($types, ...$arrValues);
	}

	// Execute the statement and handle results
	$stmt->execute();
	if ($sqlAction == "SELECT") {
		$result = $stmt->get_result();
		return $result->fetch_all(MYSQLI_ASSOC);
	} else {
		return $stmt->affected_rows > 0; // Return whether the query was successful
	}
}

//6. Batch Insert Function
function sqlBATCHINSERT($tableName, $arrFields, $arrValuesList)
{
	global $conn;
	$placeholders = array_fill(0, count($arrFields), "?");
	$sqlResult = "INSERT INTO `$tableName` (" . implode(", ", $arrFields) . ") VALUES ";

	// Generate placeholders for all rows
	$placeholdersStr = implode(", ", array_fill(0, count($arrValuesList), "(" . implode(", ", $placeholders) . ")"));
	$sqlResult .= $placeholdersStr;

	// Flatten the values array for binding
	$flattenedValues = [];
	foreach ($arrValuesList as $values) {
		$flattenedValues = array_merge($flattenedValues, $values);
	}

	// Prepare and bind parameters
	$stmt = $conn->prepare($sqlResult);
	if ($stmt === false) {
		error_log("SQL Error: " . $conn->error);
		return false;
	}

	// Assuming all values are strings (adjust types if needed)
	$types = str_repeat("s", count($flattenedValues));
	$stmt->bind_param($types, ...$flattenedValues);

	$stmt->execute();
	return $stmt->affected_rows > 0;
}

//7. Write to a File
function writeFile($stringValue, $outFile)
{
	file_put_contents($outFile, $stringValue); // Use file_put_contents for better performance
}

//8. Single Quote for Updating Records
function singleQuote($value, $sqlAction)
{
	global $conn;
	$value = htmlentities($value); // Sanitize input
	if ($sqlAction == "UPDATE") :
		if (substr($value, 0, 1) == '[') : return substr($value, 1, strlen($value) - 2);
		else : return $value = " '" . $value . "'";
		endif;
	else :
		if (trim($value) != "") :
			if (substr($value, 0, 1) == '[') : return substr($value, 1, strlen($value) - 2);
			else : return $value = " '" . $value . "'";
			endif;
		endif;
	endif;
}

// Close database connection
function db_close($conn)
{
	return mysqli_close($conn);
}

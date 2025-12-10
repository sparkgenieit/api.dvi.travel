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

/* $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn == false) :
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
endif; */

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
	// Create a MySQL connection
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	// Check if connection failed
	if (!$conn) {
		throw new Exception("Connection failed: " . mysqli_connect_error());
	}

	/* echo "Connected successfully!"; */
} catch (Exception $e) {
	// Handle connection error
	die("Database connection error: " . $e->getMessage());
}

// ====================== SQL EXECUTION LOGGER ======================
define('SQL_LOG_ENABLED', true);                 // set false in prod if you like
define('SQL_LOG_FILE', __DIR__ . '/logs/sql.log'); // make sure directory exists & is writable
define('SQL_LOG_SLOW_MS', 300);                  // mark queries slower than this many ms
// Keys (param names) to mask if you pass named arrays; positional params are also masked by heuristic
$__SQL_LOG_MASK_KEYS = ['password','pass','pwd','secret','token','apikey','api_key','authorization','auth'];

if (SQL_LOG_ENABLED) {
    // best-effort: create logs dir
    @is_dir(dirname(SQL_LOG_FILE)) || @mkdir(dirname(SQL_LOG_FILE), 0775, true);
}

function _sql_mask_value($k, $v) {
    global $__SQL_LOG_MASK_KEYS;
    $isSensitive = is_string($k) && in_array(strtolower($k), $__SQL_LOG_MASK_KEYS, true);
    if ($isSensitive) return '***';
    // Heuristic: very long strings may be tokens; mask in middle
    if (is_string($v) && strlen($v) > 128) {
        return substr($v, 0, 16) . '…' . substr($v, -4);
    }
    return $v;
}

function _sql_sanitize_params($params) {
    if (!is_array($params)) return $params;
    $out = [];
    $i = 0;
    foreach ($params as $k => $v) {
        $key = is_string($k) ? $k : $i++;
        $out[$key] = _sql_mask_value($key, $v);
    }
    return $out;
}

function _sql_backtrace_loc() {
    $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
    // Find the first frame outside this file's logger helpers
    foreach ($bt as $f) {
        if (!isset($f['file'])) continue;
        if (strpos($f['file'], 'sql.log') !== false) continue;
        // skip internal logger functions themselves
        if (!empty($f['function']) && strpos($f['function'], '_sql_') === 0) continue;
        return basename($f['file']) . ':' . ($f['line'] ?? '?');
    }
    return '?:?';
}

function _sql_log(string $sql, array $params, string $types, float $start, $resultMeta, ?string $error = null) {
    if (!SQL_LOG_ENABLED) return;

    $ms = (microtime(true) - $start) * 1000.0;
    $record = [
        'ts'        => date('c'),
        'loc'       => _sql_backtrace_loc(),
        'duration_ms' => round($ms, 2),
        'slow'      => $ms >= SQL_LOG_SLOW_MS,
        'sql'       => $sql,
        'params'    => _sql_sanitize_params($params),
        'types'     => $types,
        'result'    => $resultMeta,  // e.g., ['kind'=>'SELECT','rows'=>123] or ['kind'=>'INSERT','id'=>55] etc.
        'error'     => $error,
    ];

    // newline-delimited JSON (NDJSON)
    // Use FILE_APPEND | LOCK_EX to avoid interleaving on concurrent requests
    @file_put_contents(SQL_LOG_FILE, json_encode($record, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND | LOCK_EX);
}
// ==================== END SQL EXECUTION LOGGER ====================
//0. Basic Query label
// 0. Basic Query label + SQL LOG (NO bind params)
function sqlQUERY_LABEL($query)
{
    global $conn;

    $t0  = microtime(true);
    $err = null;

    try {
        // Detect first SQL keyword safely (handles comments, parentheses, WITH, etc.)
        $q = (string)$query;

        // strip leading whitespace
        $q2 = ltrim($q);

        // strip leading /* ... */ comments (repeat)
        while (preg_match('/^\/\*.*?\*\//s', $q2, $m)) {
            $q2 = ltrim(substr($q2, strlen($m[0])));
        }

        // strip leading -- and # comment lines (repeat)
        while (preg_match('/^(--|#)[^\r\n]*[\r\n]+/', $q2, $m)) {
            $q2 = ltrim(substr($q2, strlen($m[0])));
        }

        // strip opening parentheses/spaces
        $q2 = ltrim($q2, " \t\n\r(");

        // first keyword
        preg_match('/^([a-zA-Z]+)/', $q2, $m);
        $firstWord = strtoupper($m[1] ?? 'UNKNOWN');

        $result = mysqli_query($conn, $q);

        if ($result === false) {
            $err = mysqli_error($conn);
            _sql_log($q, [], '', $t0, ['kind' => $firstWord], $err);
            return false;
        }

        // SELECT-like: return mysqli_result (so mysqli_num_rows works)
        if (in_array($firstWord, ['SELECT','SHOW','DESCRIBE','EXPLAIN','CALL','WITH'], true)) {
            _sql_log($q, [], '', $t0, ['kind' => $firstWord, 'rows' => '(mysqli_result)']);
            return $result; // mysqli_result (for SELECT/UNION/CTE)
        }

        // Non-select: return TRUE
        _sql_log($q, [], '', $t0, ['kind' => $firstWord, 'ok' => true, 'affected' => mysqli_affected_rows($conn)]);
        return true;

    } catch (Throwable $e) {
        $err = $e->getMessage();
        _sql_log((string)$query, [], '', $t0, ['kind' => 'EXCEPTION'], $err);
        return false;
    }
}

function sqlFETCHARRAY_LABEL($query)
{
	global $conn;
	return  mysqli_fetch_array($query, MYSQLI_ASSOC);
}

function sqlERROR_LABEL()
{
	global $conn;
	return  mysqli_error($conn);
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
		return die(mysqli_error($conn));
		exit();
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
//$row = return_row_set($query);

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

//5. INSERT || UPDATE || DELETE || SELECT Query
function sqlACTIONS($sqlAction, $tableName, $arrFields = "", $arrValues = "", $sqlWhere = "")
{
	global $conn;
	$sqlResult = "";

	if ($sqlAction == "UPDATE") :
		foreach ($arrFields as $ind => $field) :
			$sqlResult = $sqlResult . $field . "=" . singleQuote($arrValues[$ind], $sqlAction) . ",";
		endforeach;
		$sqlResult = "UPDATE " . $tableName . " SET " . substr($sqlResult, 0, strlen($sqlResult) - 1);
		// sub string is used to strip the last como
		$sqlResult =  $sqlResult . ", updatedon='" . date('Y-m-d H:i:s') . "'";
	/* echo $sqlResult;
		echo "<br>";
		echo "<br>"; */
	//exit();
	endif; //End of Update

	if ($sqlAction == "INSERT") :
		$tmpField = "";
		$tmpValue = "";
		foreach ($arrFields as $ind => $field) :
			if (($arrValues[$ind]) && trim($arrValues[$ind]) != "") :
				$tmpField = $tmpField . ", " . $field;
				$tmpValue = $tmpValue . ", " . singleQuote($arrValues[$ind], $sqlAction);
			endif;
		endforeach;
		$sqlResult = "INSERT INTO `" . $tableName . "` ( " . substr($tmpField, 1) . ", `createdon` ) VALUES (" . substr($tmpValue, 1) . ",'" . date('Y-m-d H:i:s') . "')";
	// echo "$sqlResult";
	// exit;
	endif; //End of Insert

	if ($sqlAction == "DELETE") :
		$sqlResult = "DELETE FROM " . $tableName . "";
	endif; //End of Delete

	if ($sqlAction == "SELECT") :
		$retfields = "*";
		if (is_array($arrFields))
			$retfields = implode(",", $arrFields);
		$sqlResult = "SELECT * FROM " . $tableName . "";
		if ($result = sqlQUERY($sqlResult)) : // executs the SQl query and give result.	    
			return true;
		endif;
	endif;

	if (!empty($sqlWhere)) :
		$sqlResult = $sqlResult . " WHERE " . $sqlWhere;
	endif;

	/* echo $sqlResult;
	echo "<br>";
	echo "<br>"; */

	//writeFile($sqlResult, "sqlQuery.txt");

	if (sqlQUERY($sqlResult)) : // executs the SQl query and give result.
		return true;
	else :
		return false;
	endif;
}

//6. write into text file 
function writeFile($stringValue, $outFile)
{
	global $conn;
	$file = fopen($outFile, "w");
	fwrite($file, $stringValue);
	fclose($file);
}

//7. Single Quote for Updating Records
function singleQuote($value, $sqlAction)
{
	global $conn;
	//$value = htmlentities(addslashes(trim($value)));
	$value = htmlentities($value);
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

function db_close($conn)
{
	return mysqli_close($conn);
}

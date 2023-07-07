<?php
require_once './config/config.php';
error_reporting(-1);
ini_set('display_errors', 1);

$mysqli = new mysqli($servername, $username, $password, $dbname);
if ($mysqli->connect_errno) {
	echo 'Failed to connect to MySQL: ' . $mysqli->connect_error;
	exit();
}

$time = $_SERVER['REQUEST_TIME_FLOAT'];
$uid = uniqid($time, TRUE);

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$req = fopen('request.txt', 'a');
	fwrite($req, json_encode($_REQUEST) . PHP_EOL);
	fclose($req);
}

if (isset($_POST['namenode'])) {
	$log = fopen('idnode.txt', 'a');
	fwrite($log, $uid . '|');
	$txt1 = $mysqli->real_escape_string($_POST['idnode']);
	fwrite($log, $txt1 . '|');
	$txt2 = $mysqli->real_escape_string($_POST['namenode']);
	fwrite($log, $txt2 . '|');
	$txt3 = $mysqli->real_escape_string($_POST['coord']);
	fwrite($log, $txt3 . '|');
	$txt4 = $mysqli->real_escape_string($_POST['tipe']);
	fwrite($log, $txt4 . '|');
	$txt5 = $mysqli->real_escape_string($_POST['tair']);
	fwrite($log, $txt5 . '|');
	$txt6 = $mysqli->real_escape_string($_POST['tudara']);
	fwrite($log, $txt6 . '|');
	$txt7 = $mysqli->real_escape_string($_POST['hum']);
	fwrite($log, $txt7 . '|');
	$txt8 = $mysqli->real_escape_string($_POST['ph']);
	fwrite($log, $txt8 . '|');
	$txt9 = $mysqli->real_escape_string($_POST['ec']);
	fwrite($log, $txt9 . '|');
	$txt10 = $mysqli->real_escape_string($_POST['co2']);
	fwrite($log, $txt10 . '|');
	$txt11 = $mysqli->real_escape_string($_POST['vair']);
	fwrite($log, $txt11 . '|');
	fclose($log);

	$dataSIM = explode(',', $txt3);
	$longlat = $dataSIM[1] . ',' . $dataSIM[2];
	$longitude = $dataSIM[1];
	$latitude = $dataSIM[2];
	$date = $dataSIM[3];
	$time = $dataSIM[4];
	$date_server = date('Y-m-d H:i:s');

	$sql = "INSERT INTO arduino_id (id, id_node, name_node, coord, tipe, temp_air, temp_udara, humid, ph, ec, co2, vol_air, `date`, `timestamp`, date_server, longitude, latitude) VALUES ('$uid', '$txt1', '$txt2', '$longlat', '$txt4', '$txt5', '$txt6', '$txt7', '$txt8', '$txt9', '$txt10', '$txt11', '$date', '$time', '$date_server', '$longitude', '$latitude')";
	if ( ! $mysqli->query($sql))
		echo 'Error: ' . PHP_EOL . $sql . PHP_EOL . '(' . $mysqli->errno . ') ' . $mysqli->error . PHP_EOL;
	echo 'Saving Data Sensor to Database';
	$mysqli->close();
} else if (isset($_POST['namenode_IO'])) {
	$log = fopen('namenode_IO.txt', 'a');
	fwrite($log, $uid . '|');
	$namenode = $mysqli->real_escape_string($_POST['namenode_IO']);
	fwrite($log, $namenode . '|');
	$status_led1a = $mysqli->real_escape_string($_POST['sts_lt_1a']);
	fwrite($log, $status_led1a . '|');
	$status_led2a = $mysqli->real_escape_string($_POST['sts_lt_2a']);
	fwrite($log, $status_led2a . '|');
	$status_led3a = $mysqli->real_escape_string($_POST['sts_lt_3a']);
	fwrite($log, $status_led3a . '|');
	$status_led1b = $mysqli->real_escape_string($_POST['sts_lt_1b']);
	fwrite($log, $status_led1b . '|');
	$status_led2b = $mysqli->real_escape_string($_POST['sts_lt_2b']);
	fwrite($log, $status_led2b . '|');
	$status_led3b = $mysqli->real_escape_string($_POST['sts_lt_3b']);
	fwrite($log, $status_led3b . '|');
	$status_vl1b = $mysqli->real_escape_string($_POST['sts_vl_1b']);
	fwrite($log, $status_vl1b . '|');
	$status_vl2b = $mysqli->real_escape_string($_POST['sts_vl_2b']);
	fwrite($log, $status_vl2b . '|');
	$status_vl3b = $mysqli->real_escape_string($_POST['sts_vl_3b']);
	fwrite($log, $status_vl3b . '|');
	$status_vl4b = $mysqli->real_escape_string($_POST['sts_vl_4b']);
	fwrite($log, $status_vl4b . '|');
	$status_vl1a = $mysqli->real_escape_string($_POST['sts_vl_1a']);
	fwrite($log, $status_vl1a . '|');
	$status_vl2a = $mysqli->real_escape_string($_POST['sts_vl_2a']);
	fwrite($log, $status_vl2a . '|');
	$status_vl3a = $mysqli->real_escape_string($_POST['sts_vl_3a']);
	fwrite($log, $status_vl3a . '|');
	$status_vl4a = $mysqli->real_escape_string($_POST['sts_vl_4a']);
	fwrite($log, $status_vl4a . '|');
	$status_pmva = $mysqli->real_escape_string($_POST['sts_pm_va']);
	fwrite($log, $status_pmva . '|');
	$status_pmvb = $mysqli->real_escape_string($_POST['sts_pm_vb']);
	fwrite($log, $status_pmvb . '|');
	$status_pmua = $mysqli->real_escape_string($_POST['sts_pm_ua']);
	fwrite($log, $status_pmua . '|');
	$status_pmub = $mysqli->real_escape_string($_POST['sts_pm_ub']);
	fwrite($log, $status_pmub . '|');
	$status_pmda = $mysqli->real_escape_string($_POST['sts_pm_da']);
	fwrite($log, $status_pmda . '|');
	$status_pmdb = $mysqli->real_escape_string($_POST['sts_pm_db']);
	fwrite($log, $status_pmdb . '|');
	$status_pmia = $mysqli->real_escape_string($_POST['sts_pm_ia']);
	fwrite($log, $status_pmia . '|');
	$status_pmib = $mysqli->real_escape_string($_POST['sts_pm_ib']);
	fwrite($log, $status_pmib . '|');
	fclose($log);
	echo 'Saving Data Status to Database';
} else { echo 'None Post or Get'; }


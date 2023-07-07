<?php
	include "./config/config.php";
	session_start();
	error_reporting(-1);
	ini_set('display_errors',1);

	/*$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die ("Error: ".$conn->connect_error);
		exit();
	}*/

	$sec = "5";
	$page = $_SERVER['PHP_SELF'];
	$time = $_SERVER['REQUEST_TIME_FLOAT'];
	$uid = uniqid($time,true);

	/*if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
		$req = fopen('request.txt', 'a');
		fwrite($req, json_encode($_REQUEST) . PHP_EOL);
		fclose($req);
	}*/

	$myfile = fopen("logdata.txt", "a");
	/*=========Record Log Data=========*/
	if(isset($_POST['namenode'])) {
		fwrite($myfile, $uid."|");
		$txt1=$conn->real_escape_string($_POST['idnode']);
		fwrite($myfile, $txt1."|");
		$txt2=$conn->real_escape_string($_POST['namenode']);
		fwrite($myfile, $txt2."|");
		$txt3=$conn->real_escape_string($_POST['coord']);
		fwrite($myfile, $txt3."|");
		$txt4=$conn->real_escape_string($_POST['tipe']);
		fwrite($myfile, $txt4."|");
		$txt5=$conn->real_escape_string($_POST['tbody']); // Sensor Suhu Tubuh
		fwrite($myfile, $txt5."|");
		$txt6=$conn->real_escape_string($_POST['tudara']);
		fwrite($myfile, $txt6."|");
		$txt7=$conn->real_escape_string($_POST['hum']);
		fwrite($myfile, $txt7."|");
		$txt8=$conn->real_escape_string($_POST['heart']); // detak jantung
		fwrite($myfile, $txt8."|");
		$txt9=$conn->real_escape_string($_POST['lum']); // luminance
		fwrite($myfile, $txt9."\n");
		fclose($myfile);

		/*=========Storing Database=========*/
		$dataSIM=explode(",",$txt3);
		$longlat=$dataSIM[1].",".$dataSIM[2];
		$longitude=$dataSIM[1];
		$latitude=$dataSIM[2];
		$date=$dataSIM[3];
		$time=$dataSIM[4];
		$date_server = date('Y-m-d H:i:s');

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die ("Error: ".$conn->connect_error);
		}
		$sql = "INSERT INTO arduino_id (id, id_node, name_node, coord, tipe, temp_body, temp_udara, humid, heart_rate, luminance, date, timestamp, date_server, longitude, latitude) VALUES ('".$uid."', '".$txt1."', '".$txt2."', '".$longlat."', '".$txt4."', '".$txt5."', '".$txt6."', '".$txt7."', '".$txt8."', '".$txt9."', '".$date."','".$time."', '". $date_server ."' , '".$longitude."', '".$latitude."')";
		if ($conn->query($sql) === TRUE) {
			echo "Saving Data Sensor to Database - Success ";
			//$id = $conn->query("select max ('id') from arduino_id")+1; //$id = $conn->insert_id;
			//$conn->query("INSERT INTO arduino_id (id) VALUES ('".$id."')");
		}	else echo "Error: " . PHP_EOL . $sql . PHP_EOL . '(' . $mysqli->errno . ') ' . "<br>" . $conn->error . PHP_EOL;
		$conn->close();

} elseif (isset($_POST['namenode_IO'])) {
	$myfile = fopen("logdata_IO.txt", "a");
	fwrite($myfile, $uid."|");
	$idnode =  $conn->real_escape_string($_POST['idnode']);
	fwrite($myfile, $idnode."|");
	$namenode =  $conn->real_escape_string($_POST['namenode_IO']);
	fwrite($myfile, $namenode."|");
	$coord = $conn->real_escape_string($_POST['coord']);
	fwrite($myfile, $coord."|");
	$status_leduv = $conn->real_escape_string($_POST['leduv']); //Led UV
	fwrite($myfile, $status_leduv."|");
	$status_ac = $conn->real_escape_string($_POST['ac']); //AC
	fwrite($myfile, $status_ac."|");
	$status_heater = $conn->real_escape_string($_POST['heater']); //Heater
	fwrite($myfile, $status_heater."|");
	$status_bck = $conn->real_escape_string($_POST['bck']); //Backup 0
	fwrite($myfile, $status_bck."|");
	$status_bck1 = $conn->real_escape_string($_POST['bck1']); //Backup 1
	fwrite($myfile, $status_bck1."\n");
	fclose($myfile);

	//=========Storing Database=========
	$dataSIM=explode(",",$coord);
	$longlat=$dataSIM[1].",".$dataSIM[2];
	$longitude=$dataSIM[1];
	$latitude=$dataSIM[2];
	$date=$dataSIM[3];
	$time=$dataSIM[4];
	$date_server = date('Y-m-d H:i:s');

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die ("Error: ".$conn->connect_error);
	}
	$sql = "INSERT INTO arduino_IO_id (id, id_node, name_node, coord, led_uv, ac, heater, bck, bck1, date, timestamp, date_server, longitude, latitude) VALUES ('".$uid."', '".$idnode."', '".$namenode."', '".$longlat."', '".$status_leduv."', '".$status_ac."', '".$status_heater."', '".$status_bck."', '".$status_bck1."', '".$date."','".$time."', '". $date_server ."' , '".$longitude."', '".$latitude."')";
	if ($conn->query($sql) === TRUE) {
		echo "Saving Data Status Switch to Database - Success";
	}	else echo "Error: " . $sql . "<br>" . $conn->error;
	$conn->close();

} elseif (isset($_POST['namenode_rule'])) {
	$status_rule = $conn->real_escape_string($_POST['rule']);
	$namenode = $conn->real_escape_string($_POST['namenode_rule']);
	//=========Storing Database=========
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die ("Error: ".$conn->connect_error);
	}
	$sql = "UPDATE arduino_rule SET rule = '".$status_rule."' WHERE name_node = '".$namenode."'";
	if ($conn->query($sql) === TRUE) {
		echo "Saving Data Status Switch to Database - Success";
	}	else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	$conn->close();

} elseif (isset($_GET['namenode_getIO'])) {
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_errno) {
		die ("Error: ".$conn->connect_error);
	}
	$namenode = $_GET['namenode_getIO'];
	$cekDeviceQuery = "SELECT nama_device FROM tb_device WHERE id_device='".$namenode."'";
	$cekDevice = $conn->query($cekDeviceQuery);

	//if ($namenode == "S0000001" || $namenode == "S0000002") {
	if ($cekDevice) {
		//$status_notif = array('report' => 'true');
		/*=========Reading Database=========*/
		$sql = "SELECT id_node, leduv, ac, heater, bck, bck1 FROM arduino_statusIO WHERE name_node='".$namenode."'";
		$report = $conn->query($sql);
		$status = $report->fetch_array(MYSQLI_ASSOC);

		//var_dump($status);
		/* $panjang = strlen(json_encode($status, JSON_NUMERIC_CHECK))+20;//filesize($status);
		header('Content-Length: '.$panjang);
		header('Content-Type: application/json;charset=utf-8');
		echo json_encode($status)."\n";
		$report->free();
		$conn->close(); */
		$report->free();
		$conn->close();
		header('Content-Type: application/json;charset=utf-8');
		$statusJSON = json_encode($status, JSON_NUMERIC_CHECK);
		//header('Content-Length: ' . strlen($statusJSON) + 20);
		echo $statusJSON;
	} else {
		$report = array('status' => 'not identified');
		header('Content-Type: application/json;charset=utf-8');
		echo json_encode($report);
	}
} else {
	var_dump($_POST);
	echo "None Post or Get";
}

?>

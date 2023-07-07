<?php
	include "./config/config.php";
	session_start();
	error_reporting(-1);
	ini_set('display_errors',1);

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die ("Error: ".$conn->connect_error);
		exit();
	}

	function urlsafeB64Encode($var) { return str_replace(['+','/','='], ['-','_',''], base64_encode($var)); }
	function curlExec($url = '', $data = NULL, $method = 'GET', $head = array()) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		if (is_array($head) && count($head) > 0)
			curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
		if (($a = strtoupper($method)) !== 'GET')
			($a === 'POST') ? curl_setopt($ch, CURLOPT_POST, TRUE): curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $a);
		if ($data !== NULL)
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_URL, $url);
		$b = curl_exec($ch);
		$c = curl_getinfo($ch);
		$e = curl_error($ch);
		curl_close($ch);
		return array('code' => $c['http_code'], 'body' => trim(substr($b, $c['header_size'])), 'head' => trim(substr($b, 0, $c['header_size'])), 'info' => $c, 'err' => $e);
	}

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
		$txt5=$conn->real_escape_string($_POST['volt']); // tegangan
		fwrite($myfile, $txt5."|");
		$txt6=$conn->real_escape_string($_POST['tudara']);
		fwrite($myfile, $txt6."|");
		$txt7=$conn->real_escape_string($_POST['hum']);
		fwrite($myfile, $txt7."|");
		$txt8=$conn->real_escape_string($_POST['arus']); // arus
		fwrite($myfile, $txt8."|");
		$txt9=$conn->real_escape_string($_POST['lum']); // luminance
		fwrite($myfile, $txt9."|");
		$txt10=$conn->real_escape_string($_POST['kwh']); // kwh
		fwrite($myfile, $txt10."|");
		$txt11=$conn->real_escape_string($_POST['bck']); // bck
		fwrite($myfile, $txt11."\n");
		$txt12=$conn->real_escape_string($_POST['bck1']); // bck1
		fwrite($myfile, $txt12."\n");
		$txt13=$conn->real_escape_string($_POST['bck2']); // bck1
		fwrite($myfile, $txt13."\n");
		$txt14=$conn->real_escape_string($_POST['bck3']); // bck1
		fwrite($myfile, $txt14."\n");
		fclose($myfile);

		/*=========Storing Database=========*/
		$dataSIM=explode(",",$txt3);
		$site=$dataSIM[0];
		$longlat=$dataSIM[1].",".$dataSIM[2];
		$longitude=$dataSIM[1];
		$latitude=$dataSIM[2];
		$date=$dataSIM[3];
		$time=$dataSIM[4];
		$date_server = date('Y-m-d H:i:s');

		$sql = "INSERT INTO arduino_id (id, id_node, name_node, coord, tipe, temp_udara, volt, humid, arus, luminance, kwh, bck, date, timestamp, date_server, longitude, latitude, bck1, bck2, bck3) VALUES ('".$uid."', '".$txt1."', '".$txt2."', '".$site."', '".$txt4."', '".$txt6."', '".$txt5."', '".$txt7."', '".$txt8."', '".$txt9."', '".$txt10."', '".$txt11."', '".$date."','".$time."', '". $date_server ."' , '".$longitude."', '".$latitude."', '" . $txt12 . "', '" . $txt13 . "', '" . $txt14 . "')";
		if ($conn->query($sql) === TRUE) {
			$url = 'https://smartoffice.konekthing.com/api/rule'; // Trigger untuk rule di Konekthing
			// $url = 'http://10.10.3.2:9001/api/rule'; // Trigger untuk rule di Sampang
			// Uncomment dibawah (97-99) agar Rule berjalan normal
			/* $arr = ['id_node' => $txt1, 'name_node' => $txt2, 'temp_udara' => $txt6, 'volt' => $txt5, 'humid' => $txt7, 'arus' => $txt8, 'luminance' => $txt9, 'kwh' => $txt10, 'bck' => $txt11, 'bck1' => $txt12, 'bck2' => $txt13, 'bck3' => $txt14];
			$var = urlsafeB64Encode(json_encode($arr));
			exec('curl -d "var=' . $var . '" --insecure -H "Content-Type: application/x-www-form-urlencoded" -X POST ' . $url . ' &', $out); */
			echo 'Saving Data Sensor to Database - Success';
		} else { echo "Error: " . PHP_EOL . $sql . PHP_EOL . '(' . $mysqli->errno . ') ' . "<br>" . $conn->error . PHP_EOL; }
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
	$status_kipas = $conn->real_escape_string($_POST['kipas']); //Led UV
	fwrite($myfile, $status_kipas."|");
	$status_led1 = $conn->real_escape_string($_POST['led1']); //AC
	fwrite($myfile, $status_led1."|");
	$status_led2 = $conn->real_escape_string($_POST['led2']); //Heater
	fwrite($myfile, $status_led2."|");
	$status_led3 = $conn->real_escape_string($_POST['led3']); //Heater
	fwrite($myfile, $status_led3."|");
	$status_led4 = $conn->real_escape_string($_POST['led4']); //Heater
	fwrite($myfile, $status_led4."|");
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

	$sql = "INSERT INTO arduino_IO_id (id, id_node, name_node, coord, kipas, led1, led2, led2, led2, bck, bck1, date, timestamp, date_server, longitude, latitude) VALUES ('".$uid."', '".$idnode."', '".$namenode."', '".$longlat."', '".$status_kipas."', '".$status_led1."', '".$status_led2."', '".$status_led3."', '".$status_led4."', '".$status_bck."', '".$status_bck1."', '".$date."','".$time."', '". $date_server ."' , '".$longitude."', '".$latitude."')";
	if ($conn->query($sql) === TRUE) {
		echo "Saving Data Status Switch to Database - Success";
	}	else echo "Error: " . $sql . "<br>" . $conn->error;
	$conn->close();

} elseif (isset($_POST['namenode_rule'])) {
	$status_rule = $conn->real_escape_string($_POST['rule']);
	$namenode = $conn->real_escape_string($_POST['namenode_rule']);
	//=========Storing Database=========
	$sql = "UPDATE arduino_rule SET rule = '".$status_rule."' WHERE name_node = '".$namenode."'";
	if ($conn->query($sql) === TRUE) {
		if ($status_rule == 1) {
			$conn->query("UPDATE tb_rule SET rule_status = 1, rule_start_run = NULL, rule_last_run = NULL WHERE rule_node = '$namenode'");
			//$conn->query("UPDATE tb_rule SET rule_start_run='" . date('Y-m-d H:i:s') . "' WHERE rule_id=1");
		} else if ($status_rule == '0') { $conn->query("UPDATE tb_rule SET rule_status = 0 WHERE rule_node = '$namenode'"); }
		echo "Saving Data Status Switch to Database - Success";
	}	else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	$conn->close();

} elseif (isset($_GET['namenode_getIO'])) {
	$namenode = $_GET['namenode_getIO'];
	$cekDeviceQuery = "SELECT id_device FROM tb_device WHERE id_device='".$namenode."'";
	$cekDevice = $conn->query($cekDeviceQuery);

	//if ($namenode == "S0000001" || $namenode == "S0000002") {
	if ($cekDevice->num_rows > 0) {
		//$status_notif = array('report' => 'true');
		/*=========Reading Database=========*/
		$sql = "SELECT id_node, coord, kipas, led1, led2, led3, led4, bck, bck1 FROM arduino_statusIO WHERE name_node='".$namenode."'";
		$report = $conn->query($sql);
		$status = $report->fetch_array(MYSQLI_ASSOC);

		//var_dump($status);
		$panjang = strlen(json_encode($status, JSON_NUMERIC_CHECK))+17;//filesize($status);
		header('Content-Length: '.$panjang);
		header('Content-Type: application/json;charset=utf-8');
		echo json_encode($status)."\n";
		$report->free();
		$conn->close();
	} else {
		$report = array('status' => 'not identified');
		header('Content-Type: application/json;charset=utf-8');
		echo json_encode($report);
	}
} elseif (isset($_GET['namenode_getRule'])) {
	$namenode = $conn->real_escape_string($_GET['namenode_getRule']);
	$cekDeviceQuery = "SELECT id_device FROM tb_device WHERE id_device='".$namenode."'";
	$cekDevice = $conn->query($cekDeviceQuery);
	//var_dump($cekDevice);

	//if ($namenode == "S0000001" || $namenode == "S0000002") {
	if ($cekDevice->num_rows > 0) {
		//$status_notif = array('report' => 'true');
		/*=========Reading Database=========*/
		$sql = "SELECT id_node, rule FROM arduino_rule WHERE id_node='".$namenode."'";
		$report = $conn->query($sql);
		$status = $report->fetch_array(MYSQLI_ASSOC);

		$panjang = strlen(json_encode($status, JSON_NUMERIC_CHECK))+2;//filesize($status);
		header('Content-Length: '.$panjang);
		header('Content-Type: application/json;charset=utf-8');
		echo json_encode($status)."\n";
		$report->free();
		$conn->close();
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

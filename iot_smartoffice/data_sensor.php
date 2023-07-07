<?php
	include "./config/config.php";
	/*=========Read LongLat Database=========*/
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die ("Error: ".$conn->connect_error);
	}
	// $sql = "select id,id_node,timestamp,date,date_server,temp_air,temp_udara,humid,ph,ec,co2,vol_air from arduino_id where id_node='S0000002' or id_node='S0000001' ORDER BY date_server DESC";
	// $dataSensor=array();
	// if ($result=$conn->query($sql)) {
	// 	while ($kolom=mysqli_fetch_array($result)) {
	// 		if ($kolom['date']!=null) {
	// 			$dataSensor[]=$kolom;
	// 		}
	// 	}
	// 	mysqli_free_result($result);
	// }else {
	// 	echo "Error: " . $sql . "<br>" . $conn->error;
	// }
	// $conn->close();
	

	$sql = "SELECT id_node, dosen, date_server FROM arduino_id WHERE  id_node = 'S0000002' OR id_node = 'S0000001' ORDER BY date_server DESC LIMIT 1";
	$dataSensor = array();
	if ( $result = $conn->query($sql) ) {
		while ($kolom = mysqli_fetch_array($result)) {			
			$dataSensor[] = $kolom;
		}
		mysqli_free_result($result);
	}
	$conn->close();
	/*=========Send JSON Data=======*/
	header('Content-Type: application/json');
	echo json_encode($dataSensor);
?>

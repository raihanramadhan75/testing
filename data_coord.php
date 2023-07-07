<?php
	include "./config/config.php";
	/*=========Read LongLat Database=========*/
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die ("Error: ".$conn->connect_error);
	}
	$sql = "select id,id_node,timestamp,coord,date, date_server,longitude,latitude from arduino_id where id_node='S0000002' or id_node='S0000001'";
	$dataCoord=array();
	if ($result=$conn->query($sql)) {
		while ($kolom=mysqli_fetch_array($result)) {
			if ($kolom['longitude']!=null) {
				$dataCoord[]=$kolom;
			}
		}
		mysqli_free_result($result);
	}else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	$conn->close();
	
	/*=========Send JSON Data=======*/
	header('Content-Type: application/json');
	echo json_encode($dataCoord);
?>

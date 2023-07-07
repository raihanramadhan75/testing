<!-- 
/*
*****	Sabacota Dashboard	  ****** 
Dashboard agent untuk menampilkan data sabacota client yang sudah disimpan di sabacota server
Author : Budiarto 2018
Copyright : PT Konekthing Benda Pintar
*/
-->

<?php
	include "./config/config.php";
	$timestamp = time();
	$date = date ("Y-m-d",$timestamp);
	//var_dump ($date);
?>
	
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Sabacota Dashboard</title>
		<!-- Script Tag ReactJS -->
		<!-- <script src="https://unpkg.com/react@16/umd/react.development.js" crossorigin></script> -->
		<!-- <script src="https://unpkg.com/react-dom@16/umd/react-dom.development.js" crossorigin></script> -->
		<script src="https://unpkg.com/react@16/umd/react.production.min.js" crossorigin></script>
		<script src="https://unpkg.com/react-dom@16/umd/react-dom.production.min.js" crossorigin></script>
		<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.3.1/react.min.js"></script> -->
		<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.3.1/react-dom.min.js"></script> -->
		<script src="https://npmcdn.com/babel-core@5.8.38/browser.min.js"></script>
		
		<!-- Ajax Post Data -->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
		<script type="text/javascript">
		$(document).ready( function() {
			$('.button').click( function() {
				//var formdata = $(this).serialize();
				var data1 = $("input#data1").val();
				var data2 = $("input#data2").val();
				var data3 = "<?php echo $timestamp; ?>";
				var data4 = "<?php echo $date; ?>";
				var ipserver = "<?php echo $ipserver; ?>";
				var datastring = "data1=" + data1 + "&data2=" + data2 + "&data3=" + data3 + "&data4=" + data4;
				$.ajax({
					type: "POST",
					url: "http://"+ipserver+"/sabacota_server.php",
					data: datastring,
					success: function(response,status) {
						alert("Data Rsponse : "+response+" - Data Status : "+status);
						$("form")[0].reset();
					}
				 });
				return false;
				/*$.post('http://192.168.0.100/sabacota_server.php', {data1:vdata1,data2:vdata2}, function(response,status) {
				$('#notification').show();});
				alert("Data Rsponse : "+response+" - Data Status : "+status);
				$("#form")[0].reset();*/
			});
		});
		</script>			
	</head>
   <body>
		<!-- Initial Header -->
		<div id="header"></div>
		
		<!-- Get Data Button -->
		<div id="button"></div>
		
		<!-- Get Data Menubar -->
		<div id="menubar"></div>
		
		<!-- Generate Header -->
		<script type="text/babel" src="./js/Header.js"></script>
		
		<!-- Generate Button -->
		<script type="text/babel" src="./js/Button.js"></script>
		
		<!-- Generate Menubar -->
		<script type="text/babel" src="./js/Menubar.js"></script>
   </body>
</html>

<!--
/* 
*****	Sabacota Data	  ****** 
Get Tampilan data  file untuk log dan debug data yang dikirim device 
Author : Budiarto 2018
Copyright : PT Konekthing Benda Pintar
*/ 
-->

<?php
	include "./config/config.php";
	
	$log = file_get_contents($data);
	echo trim($log);
?>

<?php 
	date_default_timezone_set("Africa/Lagos");
	/*this public key is for all payment for flutterwave*/

	$token_name = "DPC";

	$con = mysqli_connect("localhost","destfpki_dpcms","destfpki_dpcms","destfpki_dpcms");
	if (!$con) { 
		echo "Database not Connected to the server"; 
	}
	else{
		/////do nothing
	}
?>